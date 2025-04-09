<?php

namespace App\Shopify\Model\Product\Inventory;

use App\Shopify\Model\IShopifyModel;

class InventoryItemInput implements IShopifyModel
{
    /**
     * @param float|null $cost
     * @param string|null $countryCodeOfOrigin
     * @param \App\Shopify\Model\Product\Inventory\InventoryItemMeasurementInput|null $measurement
     * @param bool|null $requiresShipping
     * @param string|null $sku
     * @param bool|null $tracked
     */
    public function __construct(
        private ?float $cost = null,
        private ?string $countryCodeOfOrigin = null,
        private ?InventoryItemMeasurementInput $measurement = null,
        private ?bool $requiresShipping = null,
        private ?string $sku = null,
        private ?bool $tracked = null
    ) {
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        $data = [];

        if ($this->getCost() !== null) {
            $data['cost'] = $this->getCost();
        }

        if ($this->getCountryCodeOfOrigin() !== null) {
            $data['countryCodeOfOrigin'] = $this->getCountryCodeOfOrigin();
        }

        if ($this->getMeasurement() !== null) {
            $data['measurement'] = $this->getMeasurement()->getAsArray();
        }

        if ($this->getRequiresShipping() !== null) {
            $data['requiresShipping'] = $this->getRequiresShipping();
        }

        if ($this->getSku() !== null) {
            $data['sku'] = $this->getSku();
        }

        if ($this->getTracked() !== null) {
            $data['tracked'] = $this->getTracked();
        }

        return $data;
    }

    /**
     * @return float|null
     */
    public function getCost(): ?float
    {
        return $this->cost;
    }

    /**
     * @param float|null $cost
     *
     * @return void
     */
    public function setCost(?float $cost): void
    {
        $this->cost = $cost;
    }

    /**
     * @return string|null
     */
    public function getCountryCodeOfOrigin(): ?string
    {
        return $this->countryCodeOfOrigin;
    }

    /**
     * @param string|null $countryCodeOfOrigin
     *
     * @return void
     */
    public function setCountryCodeOfOrigin(?string $countryCodeOfOrigin): void
    {
        $this->countryCodeOfOrigin = $countryCodeOfOrigin;
    }

    /**
     * @return \App\Shopify\Model\Product\Inventory\InventoryItemMeasurementInput|null
     */
    public function getMeasurement(): ?InventoryItemMeasurementInput
    {
        return $this->measurement;
    }

    /**
     * @param \App\Shopify\Model\Product\Inventory\InventoryItemMeasurementInput|null $measurement
     *
     * @return void
     */
    public function setMeasurement(?InventoryItemMeasurementInput $measurement): void
    {
        $this->measurement = $measurement;
    }

    /**
     * @return bool|null
     */
    public function getRequiresShipping(): ?bool
    {
        return $this->requiresShipping;
    }

    /**
     * @param bool|null $requiresShipping
     *
     * @return void
     */
    public function setRequiresShipping(?bool $requiresShipping): void
    {
        $this->requiresShipping = $requiresShipping;
    }

    /**
     * @return string|null
     */
    public function getSku(): ?string
    {
        return $this->sku;
    }

    /**
     * @param string|null $sku
     *
     * @return void
     */
    public function setSku(?string $sku): void
    {
        $this->sku = $sku;
    }

    /**
     * @return bool|null
     */
    public function getTracked(): ?bool
    {
        return $this->tracked;
    }

    /**
     * @param bool|null $tracked
     *
     * @return void
     */
    public function setTracked(?bool $tracked): void
    {
        $this->tracked = $tracked;
    }
}
