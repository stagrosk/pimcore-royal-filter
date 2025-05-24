<?php

namespace App\Shopify\Model\PriceList;

use App\Shopify\Model\IShopifyModel;

final class PriceListAdjustmentInput implements IShopifyModel
{
    private PriceListAdjustmentTypeEnum $type;
    private float $value;

    /**
     * @param \App\Shopify\Model\PriceList\PriceListAdjustmentTypeEnum $type
     * @param float $value
     */
    public function __construct(
        PriceListAdjustmentTypeEnum $type = PriceListAdjustmentTypeEnum::PERCENTAGE_INCREASE,
        float $value = 0.0,
    ) {
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        return [
            'type' => $this->getType()->value,
            'value' => $this->getValue(),
        ];
    }

    public function getType(): PriceListAdjustmentTypeEnum
    {
        return $this->type;
    }

    public function getValue(): float
    {
        return $this->value;
    }
}
