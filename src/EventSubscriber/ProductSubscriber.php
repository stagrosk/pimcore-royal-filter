<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\OpenDxp\Helpers\VersionHelper;
use OpenDxp\Event\DataObjectEvents;
use OpenDxp\Event\Model\DataObjectEvent;
use OpenDxp\Logger;
use OpenDxp\Model\DataObject\AbstractObject;
use OpenDxp\Model\DataObject\Concrete;
use OpenDxp\Model\DataObject\CustomerGroup;
use OpenDxp\Model\DataObject\Fieldcollection;
use OpenDxp\Model\DataObject\Fieldcollection\Data\Price;
use OpenDxp\Model\DataObject\PriceList;
use OpenDxp\Model\DataObject\Product;
use OpenDxp\Model\DataObject\RoyalFilter;
use OpenDxp\Model\DataObject\Whirlpool;

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

    /**
     * Source object IDs whose POST_UPDATE we want to skip in this request.
     * Set when we manually disable generation during a Product deletion so the
     * subsequent source save (triggered by clearing the product relation) does not
     * fire generation/cleanup logic in the RoyalFilter/Whirlpool subscribers.
     *
     * @var array<int, true>
     */
    public static array $skipSourceUpdateForObjectIds = [];

    public static function getSubscribedEvents(): array
    {
        return [
            DataObjectEvents::PRE_ADD => ['onPreAdd'],
            DataObjectEvents::PRE_UPDATE => ['onPreUpdate'],
            DataObjectEvents::POST_UPDATE => ['onPostUpdate'],
            DataObjectEvents::PRE_DELETE => ['onPreDelete'],
            DataObjectEvents::POST_DELETE => ['onPostDelete'],
        ];
    }

    public function onPreAdd(DataObjectEvent $event): void
    {
        $object = $event->getObject();

        if (!$object instanceof Product) {
            return;
        }

        $this->ensureUniqueSku($object);
        $this->applyDiscountLogic($object);

        $existingGroups = $object->getCustomerGroups();
        if (!empty($existingGroups)) {
            return;
        }

        $autoGroups = $this->getAutoAssignGroups();
        if (!empty($autoGroups)) {
            $object->setCustomerGroups($autoGroups);
        }
    }

    /**
     * On copy/duplicate Pimcore should null unique fields, but in practice the SKU often
     * still ends up identical (e.g. when the field gets re-populated by another listener
     * or when copy goes through a path that skips the nulling). Auto-suffix here so the
     * unique constraint never blocks a user copy.
     */
    private function ensureUniqueSku(Product $product): void
    {
        $sku = $product->getSku();
        if ($sku === null || $sku === '') {
            return;
        }

        $candidate = $sku;
        $suffix = 1;
        while ($this->skuExists($candidate, $product)) {
            $suffix++;
            $candidate = $sku . '-copy' . ($suffix > 2 ? '-' . ($suffix - 1) : '');
        }

        if ($candidate !== $sku) {
            $product->setSku($candidate);
        }
    }

    private function skuExists(string $sku, Product $exclude): bool
    {
        $listing = Product::getList();
        $listing->setUnpublished(true);
        $listing->setObjectTypes([
            AbstractObject::OBJECT_TYPE_OBJECT,
            AbstractObject::OBJECT_TYPE_VARIANT,
        ]);

        $excludeId = $exclude->getId();
        if ($excludeId) {
            $listing->setCondition('sku = ? AND oo_id != ?', [$sku, $excludeId]);
        } else {
            $listing->setCondition('sku = ?', [$sku]);
        }
        $listing->setLimit(1);

        return $listing->getTotalCount() > 0;
    }

    public function onPreUpdate(DataObjectEvent $event): void
    {
        $object = $event->getObject();

        if (!$object instanceof Product) {
            return;
        }

        $this->applyDiscountLogic($object);
        $this->validateBasePriceList($object);
    }

    /**
     * Recompute price/compareAtPrice on every Price item based on applyDiscountPercentage.
     *
     * - discount set, no compareAtPrice yet: snapshot current price into compareAtPrice and
     *   compute new discounted price.
     * - discount changed and compareAtPrice already set: recompute price from compareAtPrice.
     * - discount cleared: restore price from compareAtPrice and clear compareAtPrice.
     */
    private function applyDiscountLogic(Product $object): void
    {
        $prices = $object->getPrices();
        if (!$prices instanceof Fieldcollection) {
            return;
        }

        foreach ($prices->getItems() as $priceItem) {
            if ($priceItem instanceof Price) {
                $this->recalculatePriceFromDiscount($priceItem);
            }
        }
    }

    private function recalculatePriceFromDiscount(Price $item): void
    {
        $discount = $item->getApplyDiscountPercentage();
        $price = $item->getPrice();
        $compareAt = $item->getCompareAtPrice();

        // discount cleared - restore original price from compareAtPrice
        if ($discount === null || $discount <= 0.0) {
            if ($compareAt !== null && $compareAt > 0.0) {
                $item->setPrice($compareAt);
                $item->setCompareAtPrice(null);
            }
            return;
        }

        if ($discount >= 100.0) {
            return;
        }

        // first-time discount: capture current price as the original
        if ($compareAt === null || $compareAt <= 0.0) {
            if ($price === null || $price <= 0.0) {
                return;
            }
            $compareAt = $price;
        }

        $newPrice = round($compareAt * (1 - $discount / 100), 2);
        $item->setPrice($newPrice);
        $item->setCompareAtPrice($compareAt);
    }

    /**
     * When a product is deleted manually (not as part of a regeneration cycle),
     * also disable auto-regeneration on the source RoyalFilter/Whirlpool.
     * Otherwise the next save on the source - including pimcore's relation cleanup
     * after deletion - re-runs the generator and recreates the product.
     */
    public function onPreDelete(DataObjectEvent $event): void
    {
        $product = $event->getObject();
        if (!$product instanceof Product) {
            return;
        }

        // variants are owned by their master product's generator, leave them alone
        if ($product->getType() === AbstractObject::OBJECT_TYPE_VARIANT) {
            return;
        }

        $source = $product->getGeneratedFromObject();
        if (!$source instanceof RoyalFilter && !$source instanceof Whirlpool) {
            return;
        }

        // Only react when the source actually points at this product. The generator
        // also calls delete() on stale/orphan products it finds via getByGeneratedFromObject -
        // in that case the source's `product` field is null (or another product) and we must
        // not touch generateAsProduct, otherwise the in-progress generation gets cancelled.
        $currentProduct = $source->getProduct();
        if (!$currentProduct instanceof Product || $currentProduct->getId() !== $product->getId()) {
            return;
        }

        if ($source->getGenerateAsProduct() !== true) {
            return;
        }

        Logger::notice(sprintf(
            '[ProductSubscriber] Disabling generateAsProduct on source #%d after manual delete of product #%d',
            $source->getId(),
            $product->getId()
        ));

        self::$skipSourceUpdateForObjectIds[$source->getId()] = true;
        $source->setGenerateAsProduct(false);
        $source->setProduct(null);
        VersionHelper::useVersioning(static fn () => $source->save(), false);
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
