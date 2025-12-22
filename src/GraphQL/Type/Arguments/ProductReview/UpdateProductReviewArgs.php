<?php

namespace App\GraphQL\Type\Arguments\ProductReview;

use GraphQL\Type\Definition\Type;

class UpdateProductReviewArgs
{
    /**
     * @return array
     */
    public static function args(): array
    {
        return [
            'reviewId' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Review ID to update',
            ],
            'customerApiId' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Shopify customer API ID (for authorization)',
            ],
            'rating' => [
                'type' => Type::float(),
                'description' => 'New rating (1-5)',
            ],
            'content' => [
                'type' => Type::string(),
                'description' => 'New review text content',
            ],
        ];
    }
}