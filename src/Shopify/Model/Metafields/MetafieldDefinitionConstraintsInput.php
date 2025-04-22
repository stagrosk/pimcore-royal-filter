<?php

namespace App\Shopify\Model\Metafields;

use App\Shopify\Model\IShopifyModel;

class MetafieldDefinitionConstraintsInput implements IShopifyModel
{
    /**
     * @param string $key The category of resource subtypes that the definition applies to.
     * @param array<string> $values The specific constraint subtype values that the definition applies to.
     */
    public function __construct(
        public string $key,
        public array $values,
    ) {
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        return [
            'key' => $this->key,
            'values' => $this->values,
        ];
    }
}
