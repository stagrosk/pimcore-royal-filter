<?php

namespace App\Shopify\Model\Product\Inventory;

use App\Shopify\Model\IShopifyModel;

class WeightInput implements IShopifyModel
{
    /**
     * @param float $value
     * @param \App\Shopify\Model\Product\Inventory\WeightUnitEnum $unit
     */
    public function __construct(
        private float $value,
        private WeightUnitEnum $unit
    ) {
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        return [
            'value' => $this->getValue(),
            'unit' => $this->getUnit()->value
        ];
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param float $value
     *
     * @return void
     */
    public function setValue(float $value): void
    {
        $this->value = $value;
    }

    /**
     * @return \App\Shopify\Model\Product\Inventory\WeightUnitEnum
     */
    public function getUnit(): WeightUnitEnum
    {
        return $this->unit;
    }

    /**
     * @param \App\Shopify\Model\Product\Inventory\WeightUnitEnum $unit
     *
     * @return void
     */
    public function setUnit(WeightUnitEnum $unit): void
    {
        $this->unit = $unit;
    }
}
