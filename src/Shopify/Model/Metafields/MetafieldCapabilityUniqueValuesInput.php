<?php

namespace App\Shopify\Model\Metafields;

use App\Shopify\Model\IShopifyModel;

class MetafieldCapabilityUniqueValuesInput implements IShopifyModel
{
    /**
     * @param bool $enabled
     */
    public function __construct(
        public bool $enabled = false,
    ) {
    }

    /**
     * @return bool[]
     */
    public function getAsArray(): array
    {
        return [
            'enabled' => $this->enabled,
        ];
    }
}
