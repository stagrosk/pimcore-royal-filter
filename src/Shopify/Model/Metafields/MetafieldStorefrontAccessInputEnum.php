<?php

namespace App\Shopify\Model\Metafields;

enum MetafieldStorefrontAccessInputEnum: string
{
    case NONE = 'NONE';
    case PUBLIC_READ = 'PUBLIC_READ';
}
