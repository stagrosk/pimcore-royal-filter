<?php

namespace App\GraphQL\Type\Fragment;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ContentElementFragment
{
    private static ?ObjectType $elementType = null;
    private static ?ObjectType $buttonType = null;
    private static ?ObjectType $buttonRelationType = null;
    private static ?ObjectType $linkType = null;
    private static ?ObjectType $videoType = null;
    private static ?ObjectType $imageType = null;
    private static ?ObjectType $heroSlideType = null;
    private static ?ObjectType $heroAssetType = null;

    public static function getType(): ObjectType
    {
        if (self::$elementType === null) {
            self::$elementType = new ObjectType([
                'name' => 'ContentElement',
                'fields' => [
                    'componentType' => [
                        'type' => Type::nonNull(Type::string()),
                        'description' => 'Headline, Button, Text, Image, TextWithImage, ImageContent, Script, Widget, HeroSwiper, ParalaxContent',
                    ],

                    // Headline
                    'headline' => ['type' => Type::string()],
                    'headlineType' => ['type' => Type::string(), 'description' => 'h1, h2, h3'],

                    // Text / Headline / TextWithImage
                    'text' => ['type' => Type::string(), 'description' => 'HTML content'],
                    'textBoxed' => ['type' => Type::boolean()],

                    // Button
                    'link' => ['type' => self::getLinkType()],
                    'color' => ['type' => Type::string(), 'description' => 'RGBA hex string'],
                    'isExternal' => ['type' => Type::boolean()],
                    'position' => ['type' => Type::string(), 'description' => 'left, center, right'],
                    'fullWidth' => ['type' => Type::boolean()],

                    // Image
                    'image' => ['type' => Type::string(), 'description' => 'Image URL'],
                    'imageThumbnail' => ['type' => Type::string()],

                    // TextWithImage
                    'imagePosition' => ['type' => Type::string(), 'description' => 'left, right'],

                    // ImageContent
                    'images' => ['type' => Type::listOf(self::getImageType())],

                    // Script
                    'scriptSrc' => ['type' => Type::string()],
                    'bodyContent' => ['type' => Type::string()],

                    // Widget
                    'ident' => ['type' => Type::string(), 'description' => 'shopReviews, search, categories, newestProducts, blog, processedWhirlpools'],

                    // HeroSwiper
                    'heroSlides' => ['type' => Type::listOf(self::getHeroSlideType())],

                    // ParalaxContent
                    'title' => ['type' => Type::string()],
                    'button' => ['type' => self::getButtonType()],
                    'video' => ['type' => self::getVideoType()],
                    'overlay' => ['type' => Type::string(), 'description' => 'none, blur, lightDark, dark'],
                ],
            ]);
        }

        return self::$elementType;
    }

    public static function getButtonType(): ObjectType
    {
        if (self::$buttonType === null) {
            self::$buttonType = new ObjectType([
                'name' => 'ContentButton',
                'fields' => [
                    'text' => ['type' => Type::string()],
                    'link' => ['type' => Type::string(), 'description' => 'Resolved URL'],
                    'target' => ['type' => Type::string()],
                    'relation' => ['type' => self::getButtonRelationType()],
                ],
            ]);
        }

        return self::$buttonType;
    }

    private static function getButtonRelationType(): ObjectType
    {
        if (self::$buttonRelationType === null) {
            self::$buttonRelationType = new ObjectType([
                'name' => 'ContentButtonRelation',
                'fields' => [
                    'type' => ['type' => Type::nonNull(Type::string()), 'description' => 'ContentPage or Collection'],
                    'id' => ['type' => Type::nonNull(Type::int())],
                    'handle' => ['type' => Type::string()],
                    'slug' => ['type' => Type::string()],
                ],
            ]);
        }

        return self::$buttonRelationType;
    }

    private static function getLinkType(): ObjectType
    {
        if (self::$linkType === null) {
            self::$linkType = new ObjectType([
                'name' => 'ContentLink',
                'fields' => [
                    'url' => ['type' => Type::string()],
                    'text' => ['type' => Type::string()],
                    'target' => ['type' => Type::string()],
                ],
            ]);
        }

        return self::$linkType;
    }

    private static function getVideoType(): ObjectType
    {
        if (self::$videoType === null) {
            self::$videoType = new ObjectType([
                'name' => 'ContentVideo',
                'fields' => [
                    'type' => ['type' => Type::nonNull(Type::string()), 'description' => 'asset or youtube'],
                    'url' => ['type' => Type::nonNull(Type::string())],
                    'poster' => ['type' => Type::string()],
                    'title' => ['type' => Type::string()],
                ],
            ]);
        }

        return self::$videoType;
    }

    private static function getImageType(): ObjectType
    {
        if (self::$imageType === null) {
            self::$imageType = new ObjectType([
                'name' => 'ContentImage',
                'fields' => [
                    'url' => ['type' => Type::nonNull(Type::string())],
                    'thumbnailUrl' => ['type' => Type::string()],
                ],
            ]);
        }

        return self::$imageType;
    }

    private static function getHeroSlideType(): ObjectType
    {
        if (self::$heroSlideType === null) {
            self::$heroSlideType = new ObjectType([
                'name' => 'ContentHeroSlide',
                'fields' => [
                    'title' => ['type' => Type::string()],
                    'subtitle' => ['type' => Type::string()],
                    'text' => ['type' => Type::string(), 'description' => 'HTML content'],
                    'asset' => ['type' => self::getHeroAssetType()],
                    'assetText' => ['type' => Type::string(), 'description' => 'HTML content'],
                    'primaryButton' => ['type' => self::getButtonType()],
                    'secondaryButton' => ['type' => self::getButtonType()],
                ],
            ]);
        }

        return self::$heroSlideType;
    }

    private static function getHeroAssetType(): ObjectType
    {
        if (self::$heroAssetType === null) {
            self::$heroAssetType = new ObjectType([
                'name' => 'ContentHeroAsset',
                'fields' => [
                    'type' => ['type' => Type::nonNull(Type::string()), 'description' => 'image or video'],
                    'url' => ['type' => Type::nonNull(Type::string())],
                    'thumbnailUrl' => ['type' => Type::string()],
                ],
            ]);
        }

        return self::$heroAssetType;
    }
}
