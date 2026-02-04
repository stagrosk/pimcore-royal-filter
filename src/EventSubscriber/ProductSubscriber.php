<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Vendure\WebhookClient;
use Pimcore\Event\DataObjectEvents;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Logger;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Fieldcollection;
use Pimcore\Model\DataObject\PriceList;
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
            DataObjectEvents::PRE_UPDATE => ['onPreUpdate'],
            DataObjectEvents::POST_UPDATE => ['onPostUpdate'],
            DataObjectEvents::POST_DELETE => ['onPostDelete'],
        ];
    }

    public function onPreUpdate(DataObjectEvent $event): void
    {
        $object = $event->getObject();

        if (!$object instanceof Product) {
            return;
        }

        $this->validateBasePriceList($object);
    }

    private function validateBasePriceList(Product $object): void
    {
        $basePriceList = $this->getBasePriceList();
        if (!$basePriceList) {
            return;
        }

        $prices = $object->getPrices();
        if (!$prices instanceof Fieldcollection) {
            throw new \RuntimeException(sprintf(
                'Product "%s" musí mať priradený základný cenník "%s"',
                $object->getKey(),
                $basePriceList->getName()
            ));
        }

        $hasBasePriceList = false;
        foreach ($prices as $priceItem) {
            $priceList = method_exists($priceItem, 'getPriceList') ? $priceItem->getPriceList() : null;
            if ($priceList && $priceList->getId() === $basePriceList->getId()) {
                $hasBasePriceList = true;
                break;
            }
        }

        if (!$hasBasePriceList) {
            throw new \RuntimeException(sprintf(
                'Product "%s" musí mať priradený základný cenník "%s"',
                $object->getKey(),
                $basePriceList->getName()
            ));
        }
    }

    private function getBasePriceList(): ?PriceList
    {
        $listing = PriceList::getList();
        $listing->setCondition('basePricelist = 1');
        $listing->setLimit(1);
        $priceLists = $listing->load();

        return $priceLists[0] ?? null;
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

        // Process only the updated object - Vendure handles parent/children sync automatically
        $this->processObject($object);

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
