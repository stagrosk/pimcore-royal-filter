<?php

namespace App\Shopify\Model\Product\Inventory;

enum WeightUnitEnum : string
{
    case GRAMS = 'GRAMS';
    case KILOGRAMS = 'KILOGRAMS';
    case OUNCES = 'OUNCES';
    case POUNDS = 'POUNDS';
}
