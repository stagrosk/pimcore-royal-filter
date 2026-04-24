<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use OpenDxp\Event\DataObjectEvents;
use OpenDxp\Event\Model\DataObjectEvent;
use OpenDxp\Model\DataObject\AbstractObject;
use OpenDxp\Model\DataObject\Concrete;
use OpenDxp\Model\DataObject\CustomerGroup;
use OpenDxp\Model\DataObject\Fieldcollection;
use OpenDxp\Model\DataObject\PriceList;
use OpenDxp\Model\DataObject\Product;

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
            DataObjectEvents::PRE_ADD => ['onPreAdd'],
            DataObjectEvents::PRE_UPDATE => ['onPreUpdate'],
            DataObjectEvents::POST_UPDATE => ['onPostUpdate'],
            DataObjectEvents::POST_DELETE => ['onPostDelete'],
        ];
    }

    public function onPreAdd(DataObjectEvent $event): void
    {
        $object = $event->getObject();

        if (!$object instanceof Product) {
            return;
        }

        $existingGroups = $object->getCustomerGroups();
        if (!empty($existingGroups)) {
            return;
        }

        $autoGroups = $this->getAutoAssignGroups();
        if (!empty($autoGroups)) {
            $object->setCustomerGroups($autoGroups);
        }
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

        // Master products with variants don't need their own price list
        if ($this->hasVariants($object)) {
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

    private function hasVariants(Product $object): bool
    {
        $listing = Product::getList();
        $listing->setCondition('parentId = ?', [$object->getId()]);
        $listing->setLimit(1);

        return $listing->getTotalCount() > 0;
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
     * @return CustomerGroup[]
     */
    private function getAutoAssignGroups(): array
    {
        $listing = CustomerGroup::getList();
        $listing->setCondition('addAutomaticallyOnProduct = 1');

        return $listing->load();
    }
}
