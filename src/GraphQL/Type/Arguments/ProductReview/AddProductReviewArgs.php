<?php

namespace App\GraphQL\Type\Arguments\ProductReview;

use GraphQL\Type\Definition\Type;

class AddProductReviewArgs
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
            'customerApiId' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Shopify customer API ID (gid://shopify/Customer/...)',
            ],
            'rating' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Rating (1-5)',
            ],
            'content' => [
                'type' => Type::string(),
                'description' => 'Review text content',
            ],
        ];
    }
}