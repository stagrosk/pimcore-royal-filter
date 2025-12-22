<?php

namespace App\GraphQL\Response\Wishlist;

use App\GraphQL\Response\AbstractResponse;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class WishlistResponse extends AbstractResponse
{
    private static ?ObjectType $productType = null;

    public function __construct()
    {
        parent::__construct([
            'name' => 'WishlistResponse',
            'fields' => [
                'customerApiId' => [
                    'type' => Type::nonNull(Type::string()),
                    'description' => 'Shopify customer API ID',
                ],
                'products' => [
                    'type' => Type::listOf(self::getProductType()),
                    'description' => 'List of products in wishlist',
                ],
                'count' => [
                    'type' => Type::nonNull(Type::int()),
                    'description' => 'Number of products in wishlist',
                ],
            ],
        ]);
    }

    /**
     * Get product type (singleton)
     *
     * @return \GraphQL\Type\Definition\ObjectType
     */
    private static function getProductType(): ObjectType
    {
        if (self::$productType === null) {
            self::$productType = new ObjectType([
                'name' => 'WishlistProduct',
                'fields' => [
                    'id' => [
                        'type' => Type::nonNull(Type::int()),
                        'description' => 'Pimcore product ID',
                    ],
                    'apiId' => [
                        'type' => Type::string(),
                        'description' => 'Shopify product API ID',
                    ],
                    'name' => [
                        'type' => Type::string(),
                        'description' => 'Product name',
                    ],
                ],
            ]);
        }

        return self::$productType;
    }
}