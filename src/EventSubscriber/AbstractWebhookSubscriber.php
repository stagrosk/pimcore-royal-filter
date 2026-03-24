<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Vendure\WebhookClient;
use Pimcore\Event\DataObjectEvents;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Logger;
use Pimcore\Model\DataObject\Concrete;
use Pimcore\Tool;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class AbstractWebhookSubscriber implements EventSubscriberInterface
{
    public const ACTION_UPDATE = 'update';
    public const ACTION_DELETE = 'delete';

    private bool $skipPushToQueue = false;

    public function __construct(
        protected readonly WebhookClient $webhookClient
    ) {
    }

    abstract protected function getObjectClass(): string;

    abstract protected function getLogPrefix(): string;

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
        $class = $this->getObjectClass();

        if (!$object instanceof $class) {
            return;
        }

        $this->onBeforeProcess($object);
        $this->processObject($object);
        $this->onAfterProcess($object);
    }

    public function onPostDelete(DataObjectEvent $event): void
    {
        $object = $event->getObject();
        $class = $this->getObjectClass();

        if (!$object instanceof $class || $this->skipPushToQueue) {
            return;
        }

        $this->sendWebhookDelete($object);
    }

    protected function processObject(Concrete $object): void
    {
        $adminUser = Tool\Admin::getCurrentUser();

        if (!$this->isObjectPublished($object)) {
            Logger::notice(sprintf(
                '[%s] DELETE event - objectId: %d Path: %s is not published! ... DELETE webhook',
                $this->getLogPrefix(),
                $object->getId(),
                $object->getFullPath()
            ));
            $this->sendWebhookDelete($object);

            return;
        }

        if (PHP_SAPI === 'cli' && $adminUser === null) {
            Logger::notice(sprintf(
                '[%s] ObjectId: %d Path: %s saved via CLI ... SKIPPING',
                $this->getLogPrefix(),
                $object->getId(),
                $object->getFullPath()
            ));
            return;
        }

        $this->sendWebhookUpdate($object);
    }

    protected function isObjectPublished(Concrete $object): bool
    {
        $class = $this->getObjectClass();

        return $object->isPublished()
            && !($object->getParent() instanceof $class && $object->getParent()->isPublished() === false);
    }

    protected function sendWebhookUpdate(Concrete $object): void
    {
        Logger::info(sprintf(
            '[%s] UPDATE event - objectId: %d Path: %s',
            $this->getLogPrefix(),
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

    protected function sendWebhookDelete(Concrete $object): void
    {
        Logger::info(sprintf(
            '[%s] DELETE event - objectId: %d Path: %s',
            $this->getLogPrefix(),
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

    protected function onBeforeProcess(Concrete $object): void
    {
    }

    protected function onAfterProcess(Concrete $object): void
    {
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
