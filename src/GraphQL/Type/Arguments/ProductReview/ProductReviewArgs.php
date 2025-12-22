<?php

namespace App\GraphQL\Type\Arguments\ProductReview;

use GraphQL\Type\Definition\Type;

class ProductReviewArgs
{
    /**
     * @return array
     */
    public static function args(): array
    {
        return [
            'productApiId' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Shopify product API ID (gid://shopify/Product/...)',
            ],
        ];
    }
}