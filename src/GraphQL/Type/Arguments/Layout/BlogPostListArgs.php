<?php

namespace App\GraphQL\Type\Arguments\Layout;

use App\GraphQL\Type\Arguments\ArgumentsInterface;
use GraphQL\Type\Definition\Type;

class BlogPostListArgs implements ArgumentsInterface
{
    public static function args(): array
    {
        return [
            'language' => [
                'type' => Type::string(),
            ],
            'categorySlug' => [
                'type' => Type::string(),
            ],
            'limit' => [
                'type' => Type::int(),
            ],
            'offset' => [
                'type' => Type::int(),
            ],
        ];
    }
}
