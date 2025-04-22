<?php

namespace App\Shopify\Model\Metafields;

enum MetafieldAdminAccessInputEnum: string
{
    case MERCHANT_READ = "MERCHANT_READ";

    case MERCHANT_READ_WRITE = "MERCHANT_READ_WRITE";
}
