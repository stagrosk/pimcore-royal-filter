<?php

namespace App\GraphQL\Type\Arguments\Review;

use GraphQL\Type\Definition\Type;

class ReviewArgs
{
    /**
     * Get arguments for review query
     *
     * @return array
     */
    public static function args(): array
    {
        return [
            'productApiId' => [
                'type' => Type::string(),
                'description' => 'Product API ID (null to get shop reviews)',
            ],
        ];
    }
}