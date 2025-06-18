<?php

namespace App\GraphQL\Response;

use GraphQL\Type\Definition\Type;

class SuccessResponse extends AbstractResponse
{
    public function __construct()
    {
        parent::__construct([
            'fields' => [
                'success' => [
                    'type' => Type::nonNull(Type::boolean()),
                ],
                'exception' => [
                    'type' => Type::listOf(Type::string()),
                ],
            ],
        ]);
    }
}
