<?php

namespace App\Shopify\Model\Product\Inventory;

enum ProductVariantInventoryPolicyEnum: string
{
    case DENY = 'DENY';
    case CONTINUE = 'CONTINUE';
}
