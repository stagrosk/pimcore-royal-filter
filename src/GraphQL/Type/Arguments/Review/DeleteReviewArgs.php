<?php

namespace App\GraphQL\Type\Arguments\Review;

use GraphQL\Type\Definition\Type;

class DeleteReviewArgs
{
    /**
     * Get arguments for deleting a review
     *
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
                'description' => 'Customer API ID (for authorization)',
            ],
        ];
    }
}