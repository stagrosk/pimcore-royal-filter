<?php

namespace App\GraphQL\Type\Arguments\Wishlist;

use GraphQL\Type\Definition\Type;

class WishlistArgs
{
    /**
     * @return array
     */
    public static function args(): array
    {
        return [
            'customerApiId' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Shopify customer API ID (gid://shopify/Customer/...)',
            ],
        ];
    }
}