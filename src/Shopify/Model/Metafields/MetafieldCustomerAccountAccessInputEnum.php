<?php

namespace App\Shopify\Model\Metafields;

enum MetafieldCustomerAccountAccessInputEnum: string
{
    case NONE = 'NONE';
    case READ = 'READ';
    case READ_WRITE = 'READ_WRITE';
}
