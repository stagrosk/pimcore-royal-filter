<?php

namespace App\GraphQL\Type\Arguments\Review;

use GraphQL\Type\Definition\Type;

class UpdateReviewArgs
{
    /**
     * Get arguments for updating a review
     *
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
                'description' => 'Customer API ID (for authorization)',
            ],
            'rating' => [
                'type' => Type::float(),
                'description' => 'New rating (1-5)',
            ],
            'content' => [
                'type' => Type::string(),
                'description' => 'New review content',
            ],
        ];
    }
}