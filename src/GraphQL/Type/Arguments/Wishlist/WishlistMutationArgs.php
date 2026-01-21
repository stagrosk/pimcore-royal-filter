<?php

namespace App\GraphQL\Type\Arguments\Wishlist;

use GraphQL\Type\Definition\Type;

class WishlistMutationArgs
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
                'type' => Type::nonNull(Type::string()),
                'description' => 'Product API ID',
            ],
        ];
    }
}