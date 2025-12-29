<?php

namespace App\GraphQL\Response\Review;

use App\GraphQL\Response\AbstractResponse;
use GraphQL\Type\Definition\Type;

class ReviewMutationResponse extends AbstractResponse
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'ReviewMutationResponse',
            'fields' => [
                'success' => [
                    'type' => Type::nonNull(Type::boolean()),
                    'description' => 'Whether the operation was successful',
                ],
                'message' => [
                    'type' => Type::nonNull(Type::string()),
                    'description' => 'Result message',
                ],
                'reviewId' => [
                    'type' => Type::int(),
                    'description' => 'The review ID',
                ],
                'productApiId' => [
                    'type' => Type::string(),
                    'description' => 'Product API ID',
                ],
                'customerApiId' => [
                    'type' => Type::string(),
                    'description' => 'Customer API ID',
                ],
            ],
        ]);
    }
}