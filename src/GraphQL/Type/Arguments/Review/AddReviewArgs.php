<?php

namespace App\GraphQL\Type\Arguments\Review;

use GraphQL\Type\Definition\Type;

class AddReviewArgs
{
    /**
     * Get arguments for adding a review
     *
     * @return array
     */
    public static function args(): array
    {
        return [
            'productApiId' => [
                'type' => Type::string(),
                'description' => 'Product API ID (null for shop review)',
            ],
            'customerApiId' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Customer API ID',
            ],
            'firstName' => [
                'type' => Type::string(),
                'description' => 'Customer first name',
            ],
            'lastName' => [
                'type' => Type::string(),
                'description' => 'Customer last name',
            ],
            'rating' => [
                'type' => Type::nonNull(Type::float()),
                'description' => 'Rating (1-5)',
            ],
            'content' => [
                'type' => Type::string(),
                'description' => 'Review content',
            ],
        ];
    }
}