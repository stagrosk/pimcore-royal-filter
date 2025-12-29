<?php

namespace App\GraphQL\Response\Wishlist;

use App\GraphQL\Response\AbstractResponse;
use GraphQL\Type\Definition\Type;

class WishlistResponse extends AbstractResponse
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'WishlistResponse',
            'fields' => [
                'customerApiId' => [
                    'type' => Type::nonNull(Type::string()),
                    'description' => 'Shopify customer API ID',
                ],
                'productApiIds' => [
                    'type' => Type::nonNull(Type::listOf(Type::nonNull(Type::string()))),
                    'description' => 'List of Shopify product API IDs in wishlist',
                ],
                'count' => [
                    'type' => Type::nonNull(Type::int()),
                    'description' => 'Number of products in wishlist',
                ],
                'hasProduct' => [
                    'type' => Type::nonNull(Type::boolean()),
                    'description' => 'Whether specific product is in wishlist (when productApiId is provided)',
                ],
            ],
        ]);
    }
}