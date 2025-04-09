<?php

namespace App\Shopify\Model\Product\Variant;

use App\Shopify\Model\IShopifyModel;

class VariantOptionValueInput implements IShopifyModel
{
    public ?string $name = null;
    public ?string $value = null;
    public ?string $optionId = null;

    /**
     * @param string|null $name
     * @param string|null $value
     * @param string|null $optionId
     */
    public function __construct(?string $name = null, ?string $value = null, ?string $optionId = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->optionId = $optionId;
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
            'optionId' => $this->optionId,
        ];
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    public function getOptionId(): ?string
    {
        return $this->optionId;
    }

    public function setOptionId(?string $optionId): void
    {
        $this->optionId = $optionId;
    }
}
