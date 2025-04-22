<?php

namespace App\Shopify\Model\Metafields;

use App\Shopify\Model\IShopifyModel;

class MetafieldCapabilitySmartCollectionConditionInput implements IShopifyModel
{
    /**
     * @param bool $enabled
     */
    public function __construct(
        public bool $enabled = true,
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
