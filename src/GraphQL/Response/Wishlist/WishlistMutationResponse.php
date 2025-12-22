<?php

namespace App\GraphQL\Response\Wishlist;

use App\GraphQL\Response\AbstractResponse;
use GraphQL\Type\Definition\Type;

class WishlistMutationResponse extends AbstractResponse
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'WishlistMutationResponse',
            'fields' => [
                'success' => [
                    'type' => Type::nonNull(Type::boolean()),
                    'description' => 'Whether the operation was successful',
                ],
                'message' => [
                    'type' => Type::string(),
                    'description' => 'Response message',
                ],
                'productApiId' => [
                    'type' => Type::string(),
                    'description' => 'The product API ID that was added/removed',
                ],
                'customerApiId' => [
                    'type' => Type::string(),
                    'description' => 'The customer API ID',
                ],
            ],
        ]);
    }
}