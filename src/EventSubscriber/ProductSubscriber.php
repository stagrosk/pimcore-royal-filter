<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Vendure\WebhookClient;
use Pimcore\Event\DataObjectEvents;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Logger;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Product;
use Pimcore\Tool;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductSubscriber implements EventSubscriberInterface
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

        // Enable inheritance for getter
        $backup = AbstractObject::getGetInheritedValues();
        AbstractObject::setGetInheritedValues(true);

        /** @var Product $object */
        $object = $event->getObject();

        if (!$object instanceof Product) {
            return;
        }

        // Process object
        $this->processObject($object);

        // Process all children of object
        foreach ($object->getChildren([AbstractObject::OBJECT_TYPE_OBJECT, AbstractObject::OBJECT_TYPE_VARIANT]) as $child) {
            if (!$child instanceof Product) {
                continue;
            }

            $this->processObject($child);
        }

        // If is variant -> process parent if is product
        if ($object->getParent() instanceof Product) {
            $this->processObject($object->getParent());
        }

        AbstractObject::setGetInheritedValues($backup);
    }

    /**
     * @param DataObjectEvent $event
     *
     * @return void
     */
    public function onPostDelete(DataObjectEvent $event): void
    {
        /** @var Product $object */
        $object = $event->getObject();

        if (!$object instanceof Product || $this->skipPushToQueue) {
            return;
        }

        $this->sendWebhookDelete($object);
    }

    /**
     * Process a single object - determine if update or delete webhook should be sent
     *
     * @param Product $object
     *
     * @return void
     */
    private function processObject(Product $object): void
    {
        $adminUser = Tool\Admin::getCurrentUser();

        // If not published or status is not active -> send delete
        if ($object->isPublished() === false
            || ($object->getParent() instanceof Product && $object->getParent()->isPublished() === false)
        ) {
            Logger::notice(sprintf(
                '[ProductSubscriber] DELETE event - objectId: %d Path: %s is not published! ... DELETE webhook',
                $object->getId(),
                $object->getFullPath()
            ));
            $this->sendWebhookDelete($object);

            return;
        }

        // Skip if saved via CLI
        if (PHP_SAPI === 'cli' && $adminUser === null) {
            Logger::notice(sprintf(
                '[ProductSubscriber] ObjectId: %d Path: %s saved via CLI ... SKIPPING',
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
     * @param Product $object
     *
     * @return void
     */
    private function sendWebhookUpdate(Product $object): void
    {
        Logger::info(sprintf(
            '[ProductSubscriber] UPDATE event - objectId: %d Path: %s',
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
     * @param Product $object
     *
     * @return void
     */
    private function sendWebhookDelete(Product $object): void
    {
        Logger::info(sprintf(
            '[ProductSubscriber] DELETE event - objectId: %d Path: %s',
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
