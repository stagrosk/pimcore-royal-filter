<?php

namespace App\GraphQL\Type\Arguments\Layout;

use App\GraphQL\Type\Arguments\ArgumentsInterface;
use GraphQL\Type\Definition\Type;

class BlogPostArgs implements ArgumentsInterface
{
    public static function args(): array
    {
        return [
            'language' => [
                'type' => Type::string(),
            ],
            'handle' => [
                'type' => Type::string(),
            ],
            'slug' => [
                'type' => Type::string(),
            ],
        ];
    }
}
