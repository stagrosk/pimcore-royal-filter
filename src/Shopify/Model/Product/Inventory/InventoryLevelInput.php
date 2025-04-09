<?php

namespace App\Shopify\Model\Product\Inventory;

use App\Shopify\Model\IShopifyModel;

class InventoryLevelInput implements IShopifyModel
{
    /**
     * @param string $locationId
     * @param int $availableQuantity
     */
    public function __construct(
        private string $locationId,
        private int $availableQuantity
    ) {
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        return [
            'locationId' => $this->getLocationId(),
            'availableQuantity' => $this->getAvailableQuantity(),
        ];
    }

    /**
     * @return string
     */
    public function getLocationId(): string
    {
        return $this->locationId;
    }

    /**
     * @param string $locationId
     *
     * @return void
     */
    public function setLocationId(string $locationId): void
    {
        $this->locationId = $locationId;
    }

    /**
     * @return int
     */
    public function getAvailableQuantity(): int
    {
        return $this->availableQuantity;
    }

    /**
     * @param int $availableQuantity
     *
     * @return void
     */
    public function setAvailableQuantity(int $availableQuantity): void
    {
        $this->availableQuantity = $availableQuantity;
    }
}
