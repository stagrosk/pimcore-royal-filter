<?php

namespace PimcoreVendureBridgeBundle\EventListeners;

use JetBrains\PhpStorm\ArrayShape;
use OpenDxp\Event\Model\DataObjectEvent;
use PimcoreVendureBridgeBundle\Model\PimcoreVendureInterface;
use PimcoreVendureBridgeBundle\Service\AmqpService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PimcoreVendureListener implements EventSubscriberInterface
{
    const AMQP_QUEUE_UPDATE = 'update';
    const AMQP_QUEUE_DELETE = 'delete';

    protected AmqpService $amqpService;

    private bool $skipPushToQueue = false;

    /**
     * @param \PimcoreVendureBridgeBundle\Service\AmqpService $amqpService
     */
    public function __construct(
        AmqpService $amqpService
    ) {
        $this->amqpService = $amqpService;
    }

    #[ArrayShape(['pimcore.dataobject.postUpdate' => "string[]", 'pimcore.dataobject.postDelete' => "string[]"])]
    /**
     * @return string[][]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'pimcore.dataobject.postUpdate' => ['onPostUpdate'],
            'pimcore.dataobject.postDelete' => ['onPostDelete'],
        ];
    }

    /**
     * @param \OpenDxp\Event\Model\DataObjectEvent $event
     *
     * @throws \Exception
     */
    public function onPostUpdate(DataObjectEvent $event): void
    {
        $object = $event->getObject();
        if (!$object instanceof PimcoreVendureInterface || $this->skipPushToQueue) {
            return;
        }

        $this->amqpService->sendToAmqp(self::AMQP_QUEUE_UPDATE, [
            'class' => get_class($object),
            'type' => $object->getType(),
            'id' => $object->getId()
        ]);
    }

    /**
     * @param \OpenDxp\Event\Model\DataObjectEvent $event
     *
     * @throws \Exception
     * @return void
     */
    public function onPostDelete(DataObjectEvent $event): void
    {
        $object = $event->getObject();
        if (!$object instanceof PimcoreVendureInterface || $this->skipPushToQueue) {
            return;
        }

        $this->amqpService->sendToAmqp(self::AMQP_QUEUE_DELETE, [
            'class' => get_class($object),
            'type' => $object->getType(),
            'id' => $object->getId()
        ]);
    }

    /**
     * @param bool $skipPushToQueue
     */
    public function setSkipPushToQueue(bool $skipPushToQueue): void
    {
        $this->skipPushToQueue = $skipPushToQueue;
    }

    /**
     * @return bool
     */
    public function isSkipPushToQueue(): bool
    {
        return $this->skipPushToQueue;
    }
}
