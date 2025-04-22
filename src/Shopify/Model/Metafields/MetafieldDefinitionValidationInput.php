<?php

namespace App\Shopify\Model\Metafields;

use App\Shopify\Model\IShopifyModel;

class MetafieldDefinitionValidationInput implements IShopifyModel
{
    /**
     * @param string $name
     * @param string $value
     */
    public function __construct(
        public string $name,
        public string $value,
    ) {
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        return [
            'name' => $this->getName(),
            'value' => $this->getValue(),
        ];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
