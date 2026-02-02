<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Vendure\WebhookClient;
use Pimcore\Event\DataObjectEvents;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Logger;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Collection;
use Pimcore\Tool;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CollectionSubscriber implements EventSubscriberInterface
{
    public const ACTION_UPDATE = 'update';
    public const ACTION_DELETE = 'delete';

    private bool $skipPushToQueue = false;

    public function __construct(
        private readonly WebhookClient $webhookClient
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            DataObjectEvents::POST_UPDATE => ['onPostUpdate'],
            DataObjectEvents::POST_DELETE => ['onPostDelete'],
        ];
    }

    /**
     * @param DataObjectEvent $event
     *
     * @return void
     */
    public function onPostUpdate(DataObjectEvent $event): void
    {
        if ($this->skipPushToQueue) {
            return;
        }

        /** @var Collection $object */
        $object = $event->getObject();

        if (!$object instanceof Collection) {
            return;
        }

        // Process object
        $this->processObject($object);

        // Process all children of object
        foreach ($object->getChildren([AbstractObject::OBJECT_TYPE_OBJECT]) as $child) {
            if (!$child instanceof Collection) {
                continue;
            }

            $this->processObject($child);
        }

        // If has parent collection -> process parent
        if ($object->getParent() instanceof Collection) {
            $this->processObject($object->getParent());
        }
    }

    /**
     * @param DataObjectEvent $event
     *
     * @return void
     */
    public function onPostDelete(DataObjectEvent $event): void
    {
        /** @var Collection $object */
        $object = $event->getObject();

        if (!$object instanceof Collection || $this->skipPushToQueue) {
            return;
        }

        $this->sendWebhookDelete($object);
    }

    /**
     * Process a single object - determine if update or delete webhook should be sent
     *
     * @param Collection $object
     *
     * @return void
     */
    private function processObject(Collection $object): void
    {
        $adminUser = Tool\Admin::getCurrentUser();

        // If not published or parent is not published -> send delete
        if ($object->isPublished() === false
            || ($object->getParent() instanceof Collection && $object->getParent()->isPublished() === false)
        ) {
            Logger::notice(sprintf(
                '[CollectionSubscriber] DELETE event - objectId: %d Path: %s is not published! ... DELETE webhook',
                $object->getId(),
                $object->getFullPath()
            ));
            $this->sendWebhookDelete($object);

            return;
        }

        // Skip if saved via CLI
        if (PHP_SAPI === 'cli' && $adminUser === null) {
            Logger::notice(sprintf(
                '[CollectionSubscriber] ObjectId: %d Path: %s saved via CLI ... SKIPPING',
                $object->getId(),
                $object->getFullPath()
            ));
            return;
        }

        $this->sendWebhookUpdate($object);
    }

    /**
     * Send update webhook to Vendure
     *
     * @param Collection $object
     *
     * @return void
     */
    private function sendWebhookUpdate(Collection $object): void
    {
        Logger::info(sprintf(
            '[CollectionSubscriber] UPDATE event - objectId: %d Path: %s',
            $object->getId(),
            $object->getFullPath()
        ));

        $this->webhookClient->sendToVendureWebhook([
            'class' => get_class($object),
            'type' => $object->getType(),
            'id' => $object->getId(),
            'action' => self::ACTION_UPDATE
        ]);
    }

    /**
     * Send delete webhook to Vendure
     *
     * @param Collection $object
     *
     * @return void
     */
    private function sendWebhookDelete(Collection $object): void
    {
        Logger::info(sprintf(
            '[CollectionSubscriber] DELETE event - objectId: %d Path: %s',
            $object->getId(),
            $object->getFullPath()
        ));

        $this->webhookClient->sendToVendureWebhook([
            'class' => get_class($object),
            'type' => $object->getType(),
            'id' => $object->getId(),
            'action' => self::ACTION_DELETE
        ]);
    }

    /**
     * @param bool $skip
     *
     * @return void
     */
    public function setSkipPushToQueue(bool $skip): void
    {
        $this->skipPushToQueue = $skip;
    }

    /**
     * @return bool
     */
    public function isSkipPushToQueue(): bool
    {
        return $this->skipPushToQueue;
    }
}