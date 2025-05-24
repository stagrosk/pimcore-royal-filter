<?php

namespace App\Shopify\Model\PriceList;

enum PriceListAdjustmentTypeEnum: string
{
    case PERCENTAGE_DECREASE = 'PERCENTAGE_DECREASE';
    case PERCENTAGE_INCREASE = 'PERCENTAGE_INCREASE';
}
