<?php

namespace App\Shopify\Model\Metafields;

use App\Shopify\Model\IShopifyModel;

class MetafieldCapabilityCreateInput implements IShopifyModel
{
    /**
     * @param \App\Shopify\Model\Metafields\MetafieldCapabilityAdminFilterableInput|null $adminFilterable
     * @param \App\Shopify\Model\Metafields\MetafieldCapabilitySmartCollectionConditionInput|null $smartCollectionCondition
     * @param \App\Shopify\Model\Metafields\MetafieldCapabilityUniqueValuesInput|null $uniqueValues
     */
    public function __construct(
        public ?MetafieldCapabilityAdminFilterableInput $adminFilterable = null,
        public ?MetafieldCapabilitySmartCollectionConditionInput $smartCollectionCondition = null,
        public ?MetafieldCapabilityUniqueValuesInput $uniqueValues = null,
    ) {
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        return [
            'admin_filterable' => $this->getAdminFilterable()->getAsArray(),
            'smart_collection_condition' => $this->getSmartCollectionCondition()->getAsArray(),
            'unique_values' => $this->getUniqueValues()->getAsArray(),
        ];
    }

    public function getAdminFilterable(): ?MetafieldCapabilityAdminFilterableInput
    {
        return $this->adminFilterable;
    }

    public function setAdminFilterable(?MetafieldCapabilityAdminFilterableInput $adminFilterable): void
    {
        $this->adminFilterable = $adminFilterable;
    }

    public function getSmartCollectionCondition(): ?MetafieldCapabilitySmartCollectionConditionInput
    {
        return $this->smartCollectionCondition;
    }

    public function setSmartCollectionCondition(?MetafieldCapabilitySmartCollectionConditionInput $smartCollectionCondition): void
    {
        $this->smartCollectionCondition = $smartCollectionCondition;
    }

    public function getUniqueValues(): ?MetafieldCapabilityUniqueValuesInput
    {
        return $this->uniqueValues;
    }

    public function setUniqueValues(?MetafieldCapabilityUniqueValuesInput $uniqueValues): void
    {
        $this->uniqueValues = $uniqueValues;
    }
}
