<?php

namespace App\GraphQL\Type\Arguments\Layout;

use App\GraphQL\Type\Arguments\ArgumentsInterface;
use GraphQL\Type\Definition\Type;
use JetBrains\PhpStorm\ArrayShape;

class ContentPageArgs implements ArgumentsInterface
{
    #[ArrayShape(['language' => 'array', 'handle' => 'array', 'slug' => 'array'])]
    /**
     * @return array
     */
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
