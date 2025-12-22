<?php

namespace App\GraphQL\Type\Arguments\ProductReview;

use GraphQL\Type\Definition\Type;

class DeleteProductReviewArgs
{
    /**
     * @return array
     */
    public static function args(): array
    {
        return [
            'reviewId' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Review ID to delete',
            ],
            'customerApiId' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Shopify customer API ID (for authorization)',
            ],
        ];
    }
}