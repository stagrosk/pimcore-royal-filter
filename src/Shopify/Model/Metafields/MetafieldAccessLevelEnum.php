<?php

namespace App\Shopify\Model\Metafields;

enum MetafieldAccessLevelEnum: string
{
    case NONE = 'NONE';
    case READ = 'READ';
    case READ_WRITE = 'READ_WRITE';
    case MERCHANT_READ = 'MERCHANT_READ';
    case MERCHANT_READ_WRITE = 'MERCHANT_READ_WRITE';
    case PUBLIC_READ = 'PUBLIC_READ';
}
