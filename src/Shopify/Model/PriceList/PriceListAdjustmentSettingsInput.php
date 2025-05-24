<?php

namespace App\Shopify\Model\PriceList;

use App\Shopify\Model\IShopifyModel;

final class PriceListAdjustmentSettingsInput implements IShopifyModel
{
    private PriceListCompareAtModeEnum $compareAtMode;

    /**
     * @param \App\Shopify\Model\PriceList\PriceListCompareAtModeEnum $compareAtMode
     */
    public function __construct(
        PriceListCompareAtModeEnum $compareAtMode = PriceListCompareAtModeEnum::NULLIFY
    ) {
        $this->compareAtMode = $compareAtMode;
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        return [
            'compareAtMode' => $this->getCompareAtMode()->value
        ];
    }

    public function getCompareAtMode(): PriceListCompareAtModeEnum
    {
        return $this->compareAtMode;
    }
}
