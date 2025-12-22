<?php

namespace App\GraphQL\Response\ProductReview;

use App\GraphQL\Response\AbstractResponse;
use GraphQL\Type\Definition\Type;

class ProductReviewMutationResponse extends AbstractResponse
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'ProductReviewMutationResponse',
            'fields' => [
                'success' => [
                    'type' => Type::nonNull(Type::boolean()),
                    'description' => 'Whether the operation was successful',
                ],
                'message' => [
                    'type' => Type::string(),
                    'description' => 'Response message',
                ],
                'reviewId' => [
                    'type' => Type::int(),
                    'description' => 'The review ID',
                ],
                'productApiId' => [
                    'type' => Type::string(),
                    'description' => 'The product API ID',
                ],
                'customerApiId' => [
                    'type' => Type::string(),
                    'description' => 'The customer API ID',
                ],
            ],
        ]);
    }
}