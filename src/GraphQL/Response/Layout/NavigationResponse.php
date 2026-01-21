<?php

namespace App\GraphQL\Response\Layout;

use App\GraphQL\Response\AbstractResponse;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class NavigationResponse extends AbstractResponse
{
    protected static int $level = 0;

    private static ?ObjectType $canonicalType = null;

    public function __construct()
    {
        parent::__construct([
            'name' => 'NavigationResponse',
            'fields' => [
                'identifier' => [
                    'type' => Type::nonNull(Type::string()),
                ],
                'isPartner' => [
                    'type' => Type::nonNull(Type::boolean()),
                    'description' => 'Indicates if this navigation is for partner',
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
            'apiId' => [
                'type' => Type::string(),
                'description' => 'API ID for the related object (e.g. collection ID)',
            ],
            'canonicals' => [
                'type' => Type::listOf(self::getCanonicalType()),
                'description' => 'Language handles for all available languages',
            ],
            'isPartner' => [
                'type' => Type::nonNull(Type::boolean()),
                'description' => 'Indicates if this link item is for partners',
            ],
            'imageTile' => [
                'type' => Type::string(),
                'description' => 'Tile image URL 240x240 (1:1)',
            ],
            'imagePreview' => [
                'type' => Type::string(),
                'description' => 'Preview image URL 800x500 (16:10)',
            ],
            'description' => [
                'type' => Type::string(),
                'description' => 'Description of the category',
            ],
        ];
    }

    /**
     * Get canonical link type definition (singleton)
     *
     * @return \GraphQL\Type\Definition\ObjectType
     */
    private static function getCanonicalType(): ObjectType
    {
        if (self::$canonicalType === null) {
            self::$canonicalType = new ObjectType([
                'name' => 'NavigationCanonicalLink',
                'fields' => [
                    'language' => [
                        'type' => Type::nonNull(Type::string()),
                        'description' => 'Language code (e.g. en, de, sk, cs)',
                    ],
                    'handle' => [
                        'type' => Type::string(),
                        'description' => 'Handle for this language',
                    ],
                    'slug' => [
                        'type' => Type::string(),
                        'description' => 'Slug for this language',
                    ],
                ],
            ]);
        }

        return self::$canonicalType;
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
