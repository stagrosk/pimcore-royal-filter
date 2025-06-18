<?php

namespace App\GraphQL\Response\Layout;

use App\GraphQL\Response\AbstractResponse;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class NavigationResponse extends AbstractResponse
{
    protected static int $level = 0;

    public function __construct()
    {
        parent::__construct([
            'name' => 'NavigationResponse',
            'fields' => [
                'identifier' => [
                    'type' => Type::nonNull(Type::string()),
                ],
                'linkItems' => [
                    'type' => Type::listOf(self::linkItemFirstLevel()),
                ],
            ],
        ]);
    }

    /**
     * @return array[]
     */
    private static function getBasicLinkItemFields(): array
    {
        return [
            'title' => [
                'type' => Type::nonNull(Type::string()),
            ],
            'className' => [
                'type' => Type::nonNull(Type::string()),
            ],
            'handle' => [
                'type' => Type::string(),
            ],
            'slug' => [
                'type' => Type::string(),
            ],
            'additionalData' => [
                'type' => Type::string(),
            ],
            'nameInNavigation' => [
                'type' => Type::string(),
            ],
        ];
    }

    /**
     * @return \GraphQL\Type\Definition\ObjectType
     */
    public static function linkItemFirstLevel(): ObjectType
    {
        return new ObjectType([
            'name' => 'NavigationItemFirstLevelResponse',
            'fields' => array_merge(self::getBasicLinkItemFields(), [
                'linkItems' => [
                    'type' => Type::listOf(self::linkItemSecondLevel()),
                ],
            ]),
        ]);
    }

    /**
     * @return \GraphQL\Type\Definition\ObjectType
     */
    public static function linkItemSecondLevel(): ObjectType
    {
        return new ObjectType([
            'name' => 'NavigationItemSecondLevelResponse',
            'fields' => array_merge(self::getBasicLinkItemFields(), [
                'linkItems' => [
                    'type' => Type::listOf(self::linkItemThirdLevel()),
                ],
            ]),
        ]);
    }

    /**
     * @return \GraphQL\Type\Definition\ObjectType
     */
    public static function linkItemThirdLevel(): ObjectType
    {
        return new ObjectType([
            'name' => 'NavigationItemThirdLevelResponse',
            'fields' => array_merge(self::getBasicLinkItemFields(), [
                'linkItems' => [
                    'type' => Type::listOf(self::linkItemFourthLevel()),
                ],
            ]),
        ]);
    }

    /**
     * @return \GraphQL\Type\Definition\ObjectType
     */
    public static function linkItemFourthLevel(): ObjectType
    {
        return new ObjectType([
            'name' => 'NavigationItemFourthLevelResponse',
            'fields' => self::getBasicLinkItemFields(),
        ]);
    }
}
