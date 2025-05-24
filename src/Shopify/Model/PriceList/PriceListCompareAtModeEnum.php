<?php

namespace App\Shopify\Model\PriceList;

enum PriceListCompareAtModeEnum: string
{
    case ADJUSTED = 'ADJUSTED';
    case NULLIFY = 'NULLIFY';
}
