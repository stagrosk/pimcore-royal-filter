<?php

namespace App\Shopify\Model\Product;

enum ProductStatusEnum: string
{
    case ACTIVE = 'ACTIVE';
    case ARCHIVED = 'ARCHIVED';
    case DRAFT = 'DRAFT';
}
