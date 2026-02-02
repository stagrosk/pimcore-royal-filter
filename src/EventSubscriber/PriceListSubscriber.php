<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Vendure\WebhookClient;
use Pimcore\Event\DataObjectEvents;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Logger;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\PriceList;
use Pimcore\Tool;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PriceListSubscriber implements EventSubscriberInterface
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

    public function onPostUpdate(DataObjectEvent $event): void
    {
        if ($this->skipPushToQueue) {
            return;
        }

        $object = $event->getObject();

        if (!$object instanceof PriceList) {
            Logger::debug(sprintf(
                '[PriceListSubscriber] Object is not PriceList, class: %s',
                get_class($object)
            ));
            return;
        }

        Logger::info(sprintf(
            '[PriceListSubscriber] Processing PriceList: %d - %s',
            $object->getId(),
            $object->getFullPath()
        ));

        // Process object
        $this->processObject($object);

        // Process all descendants (children, grandchildren, etc.)
        $this->processAllDescendants($object);

        // If has parent PriceList -> process parent
        if ($object->getParent() instanceof PriceList) {
            $this->processObject($object->getParent());
        }
    }

    private function processAllDescendants(PriceList $object): void
    {
        foreach ($object->getChildren([AbstractObject::OBJECT_TYPE_OBJECT]) as $child) {
            if (!$child instanceof PriceList) {
                continue;
            }

            $this->processObject($child);
            $this->processAllDescendants($child);
        }
    }

    public function onPostDelete(DataObjectEvent $event): void
    {
        $object = $event->getObject();

        if (!$object instanceof PriceList || $this->skipPushToQueue) {
            return;
        }

        $this->sendWebhookDelete($object);
    }

    private function processObject(PriceList $object): void
    {
        $adminUser = Tool\Admin::getCurrentUser();

        // If not published or parent is not published -> send delete
        if ($object->isPublished() === false
            || ($object->getParent() instanceof PriceList && $object->getParent()->isPublished() === false)
        ) {
            Logger::notice(sprintf(
                '[PriceListSubscriber] DELETE event - objectId: %d Path: %s is not published! ... DELETE webhook',
                $object->getId(),
                $object->getFullPath()
            ));
            $this->sendWebhookDelete($object);

            return;
        }

        if (PHP_SAPI === 'cli' && $adminUser === null) {
            Logger::notice(sprintf(
                '[PriceListSubscriber] ObjectId: %d Path: %s saved via CLI ... SKIPPING',
                $object->getId(),
                $object->getFullPath()
            ));
            return;
        }

        $this->sendWebhookUpdate($object);
    }

    private function sendWebhookUpdate(PriceList $object): void
    {
        Logger::info(sprintf(
            '[PriceListSubscriber] UPDATE event - objectId: %d Path: %s',
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

    private function sendWebhookDelete(PriceList $object): void
    {
        Logger::info(sprintf(
            '[PriceListSubscriber] DELETE event - objectId: %d Path: %s',
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

    public function setSkipPushToQueue(bool $skip): void
    {
        $this->skipPushToQueue = $skip;
    }

    public function isSkipPushToQueue(): bool
    {
        return $this->skipPushToQueue;
    }
}