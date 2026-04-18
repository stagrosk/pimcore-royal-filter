<?php

namespace App\GraphQL\Type\Arguments\Layout;

use App\GraphQL\Type\Arguments\ArgumentsInterface;
use GraphQL\Type\Definition\Type;
use JetBrains\PhpStorm\ArrayShape;

class NavigationByIdentifierArgs implements ArgumentsInterface
{
    #[ArrayShape(['identifier' => 'array', 'language' => 'array'])]
    /**
     * @return array
     */
    public static function args(): array
    {
        return [
            'identifier' => [
                'type' => Type::nonNull(Type::string()),
            ],
            'language' => [
                'type' => Type::nonNull(Type::string()),
            ],
        ];
    }
}
