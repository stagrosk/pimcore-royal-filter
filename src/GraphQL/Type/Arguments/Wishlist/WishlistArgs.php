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
                'description' => 'Customer API ID',
            ],
            'productApiId' => [
                'type' => Type::string(),
                'description' => 'Optional: check if specific product is in wishlist',
            ],
        ];
    }
}