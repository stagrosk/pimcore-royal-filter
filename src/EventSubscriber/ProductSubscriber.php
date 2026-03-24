<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Pimcore\Event\DataObjectEvents;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Concrete;
use Pimcore\Model\DataObject\Fieldcollection;
use Pimcore\Model\DataObject\PriceList;
use Pimcore\Model\DataObject\Product;

class ProductSubscriber extends AbstractWebhookSubscriber
{
    private bool $inheritanceBackup = false;

    protected function getObjectClass(): string
    {
        return Product::class;
    }

    protected function getLogPrefix(): string
    {
        return 'ProductSubscriber';
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

    protected function onBeforeProcess(Concrete $object): void
    {
        $this->inheritanceBackup = AbstractObject::getGetInheritedValues();
        AbstractObject::setGetInheritedValues(true);
    }

    protected function onAfterProcess(Concrete $object): void
    {
        AbstractObject::setGetInheritedValues($this->inheritanceBackup);
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
}
