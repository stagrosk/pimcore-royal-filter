<?php

namespace App\Shopify\Model\Product\Inventory;

use App\Shopify\Model\IShopifyModel;

class InventoryItemMeasurementInput implements IShopifyModel
{
    /**
     * @param \App\Shopify\Model\Product\Inventory\WeightInput|null $weight
     */
    public function __construct(
        private ?WeightInput $weight = null
    ) {
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        return [
            'weight' => $this->getWeight()->getAsArray(),
        ];
    }

    /**
     * @return \App\Shopify\Model\Product\Inventory\WeightInput|null
     */
    public function getWeight(): ?WeightInput
    {
        return $this->weight;
    }

    /**
     * @param \App\Shopify\Model\Product\Inventory\WeightInput|null $weight
     *
     * @return void
     */
    public function setWeight(?WeightInput $weight): void
    {
        $this->weight = $weight;
    }
}
