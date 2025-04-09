<?php

namespace App\Shopify\Model\Product\Variant;

use App\Shopify\Model\IShopifyModel;
use App\Shopify\Model\Metafields\MetafieldInputs;
use App\Shopify\Model\Price\MoneyInput;
use App\Shopify\Model\Product\Inventory\InventoryItemInput;
use App\Shopify\Model\Product\Inventory\InventoryLevelInput;
use App\Shopify\Model\Product\Inventory\ProductVariantInventoryPolicyEnum;

class ProductVariantsBulkInput implements IShopifyModel
{
    /**
     * @param string|null $id
     * @param string|null $barcode
     * @param float|null $compareAtPrice
     * @param \App\Shopify\Model\Product\Inventory\InventoryItemInput|null $inventoryItem
     * @param \App\Shopify\Model\Product\Inventory\ProductVariantInventoryPolicyEnum $inventoryPolicy
     * @param \App\Shopify\Model\Product\Inventory\InventoryLevelInput|null $inventoryQuantities
     * @param string|null $mediaId
     * @param array|null $mediaSrc
     * @param \App\Shopify\Model\Metafields\MetafieldInputs|null $metafields
     * @param array|null $optionValues
     * @param float|null $price
     * @param bool $requiresComponents
     * @param bool|null $taxable
     * @param string|null $taxCode
     */
    public function __construct(
        private ?string                           $id = null,
        private ?string                           $barcode = null,
        private ?float                            $compareAtPrice = null,
        private ?InventoryItemInput               $inventoryItem = null,
        private ProductVariantInventoryPolicyEnum $inventoryPolicy = ProductVariantInventoryPolicyEnum::DENY,
        private ?InventoryLevelInput              $inventoryQuantities = null,
        private ?string                           $mediaId = null,
        private ?array                            $mediaSrc = null,
        private ?MetafieldInputs                  $metafields = null,
        private ?array                            $optionValues = null,
        private ?float                            $price = null,
        private bool                              $requiresComponents = false,
        private ?bool                             $taxable = null,
        private ?string                           $taxCode = null
    ) {
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        $data = [];

        if ($this->getId() !== null) {
            $data['id'] = $this->getId();
        }

        if ($this->getBarcode() !== null) {
            $data['barcode'] = $this->getBarcode();
        }

        if ($this->getCompareAtPrice() !== null) {
            $data['compareAtPrice'] = $this->getCompareAtPrice();
        }

        if ($this->getInventoryItem() !== null) {
            $data['inventoryItem'] = $this->getInventoryItem()->getAsArray();
        }

        $data['inventoryPolicy'] = $this->getInventoryPolicy()->value;

        if ($this->getInventoryQuantities() !== null) {
            $data['inventoryQuantities'] = $this->getInventoryQuantities()->getAsArray();
        }

        if ($this->getMediaId() !== null) {
            $data['mediaId'] = $this->getMediaId();
        }

        if ($this->getMediaSrc() !== null) {
            $data['mediaSrc'] = $this->getMediaSrc();
        }

        if ($this->getMetafields() !== null) {
            $data['metafields'] = $this->getMetafields()->getAsArray();
        }

        if ($this->getOptionValues() !== null) {
            $data['optionValues'] = $this->getOptionValues();
        }

        if ($this->getPrice() !== null) {
            $data['price'] = $this->getPrice();
        }

        if ($this->isRequiresComponents() !== false) {
            $data['requiresComponents'] = $this->isRequiresComponents();
        }

        if ($this->getTaxable() !== null) {
            $data['taxable'] = $this->getTaxable();
        }

        if ($this->getTaxCode() !== null) {
            $data['taxCode'] = $this->getTaxCode();
        }

        return $data;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     *
     * @return void
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    /**
     * @param string|null $barcode
     *
     * @return void
     */
    public function setBarcode(?string $barcode): void
    {
        $this->barcode = $barcode;
    }

    /**
     * @return float|null
     */
    public function getCompareAtPrice(): ?float
    {
        return $this->compareAtPrice;
    }

    /**
     * @param float|null $compareAtPrice
     *
     * @return void
     */
    public function setCompareAtPrice(?float $compareAtPrice): void
    {
        $this->compareAtPrice = $compareAtPrice;
    }

    /**
     * @return \App\Shopify\Model\Product\Inventory\InventoryItemInput|null
     */
    public function getInventoryItem(): ?InventoryItemInput
    {
        return $this->inventoryItem;
    }

    /**
     * @param \App\Shopify\Model\Product\Inventory\InventoryItemInput|null $inventoryItem
     *
     * @return void
     */
    public function setInventoryItem(?InventoryItemInput $inventoryItem): void
    {
        $this->inventoryItem = $inventoryItem;
    }

    /**
     * @return \App\Shopify\Model\Product\Inventory\ProductVariantInventoryPolicyEnum
     */
    public function getInventoryPolicy(): ProductVariantInventoryPolicyEnum
    {
        return $this->inventoryPolicy;
    }

    /**
     * @param \App\Shopify\Model\Product\Inventory\ProductVariantInventoryPolicyEnum $inventoryPolicy
     *
     * @return void
     */
    public function setInventoryPolicy(ProductVariantInventoryPolicyEnum $inventoryPolicy): void
    {
        $this->inventoryPolicy = $inventoryPolicy;
    }

    /**
     * @return \App\Shopify\Model\Product\Inventory\InventoryLevelInput|null
     */
    public function getInventoryQuantities(): ?InventoryLevelInput
    {
        return $this->inventoryQuantities;
    }

    /**
     * @param \App\Shopify\Model\Product\Inventory\InventoryLevelInput|null $inventoryQuantities
     *
     * @return void
     */
    public function setInventoryQuantities(?InventoryLevelInput $inventoryQuantities): void
    {
        $this->inventoryQuantities = $inventoryQuantities;
    }

    /**
     * @return string|null
     */
    public function getMediaId(): ?string
    {
        return $this->mediaId;
    }

    /**
     * @param string|null $mediaId
     *
     * @return void
     */
    public function setMediaId(?string $mediaId): void
    {
        $this->mediaId = $mediaId;
    }

    /**
     * @return array|null
     */
    public function getMediaSrc(): ?array
    {
        return $this->mediaSrc;
    }

    /**
     * @param array|null $mediaSrc
     *
     * @return void
     */
    public function setMediaSrc(?array $mediaSrc): void
    {
        $this->mediaSrc = $mediaSrc;
    }

    /**
     * @return \App\Shopify\Model\Metafields\MetafieldInputs|null
     */
    public function getMetafields(): ?MetafieldInputs
    {
        return $this->metafields;
    }

    /**
     * @param \App\Shopify\Model\Metafields\MetafieldInputs|null $metafields
     *
     * @return void
     */
    public function setMetafields(?MetafieldInputs $metafields): void
    {
        $this->metafields = $metafields;
    }

    /**
     * @return array|null
     */
    public function getOptionValues(): ?array
    {
        return $this->optionValues;
    }

    /**
     * @param array|null $optionValues
     *
     * @return void
     */
    public function setOptionValues(?array $optionValues): void
    {
        $this->optionValues = $optionValues;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     *
     * @return void
     */
    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return bool
     */
    public function isRequiresComponents(): bool
    {
        return $this->requiresComponents;
    }

    /**
     * @param bool $requiresComponents
     *
     * @return void
     */
    public function setRequiresComponents(bool $requiresComponents): void
    {
        $this->requiresComponents = $requiresComponents;
    }

    /**
     * @return bool|null
     */
    public function getTaxable(): ?bool
    {
        return $this->taxable;
    }

    /**
     * @param bool|null $taxable
     *
     * @return void
     */
    public function setTaxable(?bool $taxable): void
    {
        $this->taxable = $taxable;
    }

    /**
     * @return string|null
     */
    public function getTaxCode(): ?string
    {
        return $this->taxCode;
    }

    /**
     * @param string|null $taxCode
     *
     * @return void
     */
    public function setTaxCode(?string $taxCode): void
    {
        $this->taxCode = $taxCode;
    }
}
