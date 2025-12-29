<?php

namespace App\GraphQL\Type\Arguments\Review;

use GraphQL\Type\Definition\Type;

class AddReplyArgs
{
    /**
     * Get arguments for adding a reply to a review
     *
     * @return array
     */
    public static function args(): array
    {
        return [
            'reviewId' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'Review ID to reply to',
            ],
            'customerApiId' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Customer API ID of the person replying',
            ],
            'firstName' => [
                'type' => Type::string(),
                'description' => 'First name of person replying',
            ],
            'lastName' => [
                'type' => Type::string(),
                'description' => 'Last name of person replying',
            ],
            'content' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'Reply content',
            ],
        ];
    }
}