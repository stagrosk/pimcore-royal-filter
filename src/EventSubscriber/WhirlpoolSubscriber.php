<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Service\Generator\BaseProductGenerator;
use Pimcore\Event\DataObjectEvents;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\Whirlpool;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class WhirlpoolSubscriber implements EventSubscriberInterface
{
    /**
     * @param \App\Service\Generator\BaseProductGenerator $productGenerator
     */
    public function __construct(
        private BaseProductGenerator $productGenerator
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            DataObjectEvents::PRE_UPDATE => ['onPreUpdate'],
        ];
    }

    /**
     * @param \Pimcore\Event\Model\DataObjectEvent $event
     *
     * @throws \Exception
     * @return void
     */
    public function onPreUpdate(DataObjectEvent $event): void
    {
        /** @var \Pimcore\Model\DataObject\Whirlpool $object */
        $object = $event->getObject();

        // check object type
        if (!$object instanceof Whirlpool) {
            return;
        }

        if ($object->isPublished() && $object->getGenerateAsProduct() === true) {
            $this->productGenerator->generateProductForObject($object);
        }
    }
}
