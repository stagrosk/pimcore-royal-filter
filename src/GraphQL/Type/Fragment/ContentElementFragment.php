<?php

namespace App\GraphQL\Type\Fragment;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ContentElementFragment
{
    private static ?ObjectType $elementType = null;
    private static ?ObjectType $headlineType = null;
    private static ?ObjectType $textType = null;
    private static ?ObjectType $buttonComponentType = null;
    private static ?ObjectType $imageComponentType = null;
    private static ?ObjectType $textWithImageType = null;
    private static ?ObjectType $imageContentType = null;
    private static ?ObjectType $scriptType = null;
    private static ?ObjectType $widgetType = null;
    private static ?ObjectType $heroSwiperType = null;
    private static ?ObjectType $paralaxContentType = null;
    private static ?ObjectType $productGridType = null;
    private static ?ObjectType $categoryGridType = null;

    // Shared sub-types
    private static ?ObjectType $buttonType = null;
    private static ?ObjectType $buttonRelationType = null;
    private static ?ObjectType $linkType = null;
    private static ?ObjectType $videoType = null;
    private static ?ObjectType $galleryImageType = null;
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
                        'description' => 'Headline, Button, Text, Image, TextWithImage, ImageContent, Script, Widget, HeroSwiper, ParalaxContent, ProductGrid, CategoryGrid',
                    ],
                    'headline' => ['type' => self::getHeadlineType()],
                    'text' => ['type' => self::getTextType()],
                    'button' => ['type' => self::getButtonComponentType()],
                    'image' => ['type' => self::getImageComponentType()],
                    'textWithImage' => ['type' => self::getTextWithImageType()],
                    'imageContent' => ['type' => self::getImageContentType()],
                    'script' => ['type' => self::getScriptType()],
                    'widget' => ['type' => self::getWidgetType()],
                    'heroSwiper' => ['type' => self::getHeroSwiperType()],
                    'paralaxContent' => ['type' => self::getParalaxContentType()],
                    'productGrid' => ['type' => self::getProductGridType()],
                    'categoryGrid' => ['type' => self::getCategoryGridType()],
                ],
            ]);
        }

        return self::$elementType;
    }

    private static function getHeadlineType(): ObjectType
    {
        if (self::$headlineType === null) {
            self::$headlineType = new ObjectType([
                'name' => 'ContentHeadline',
                'fields' => [
                    'headline' => ['type' => Type::string()],
                    'headlineType' => ['type' => Type::string(), 'description' => 'h1, h2, h3'],
                    'textBoxed' => ['type' => Type::boolean()],
                ],
            ]);
        }

        return self::$headlineType;
    }

    private static function getTextType(): ObjectType
    {
        if (self::$textType === null) {
            self::$textType = new ObjectType([
                'name' => 'ContentText',
                'fields' => [
                    'text' => ['type' => Type::string(), 'description' => 'HTML content'],
                    'textBoxed' => ['type' => Type::boolean()],
                ],
            ]);
        }

        return self::$textType;
    }

    private static function getButtonComponentType(): ObjectType
    {
        if (self::$buttonComponentType === null) {
            self::$buttonComponentType = new ObjectType([
                'name' => 'ContentButtonComponent',
                'fields' => [
                    'link' => ['type' => self::getLinkType()],
                    'color' => ['type' => Type::string(), 'description' => 'RGBA hex string'],
                    'isExternal' => ['type' => Type::boolean()],
                    'position' => ['type' => Type::string(), 'description' => 'left, center, right'],
                    'fullWidth' => ['type' => Type::boolean()],
                ],
            ]);
        }

        return self::$buttonComponentType;
    }

    private static function getImageComponentType(): ObjectType
    {
        if (self::$imageComponentType === null) {
            self::$imageComponentType = new ObjectType([
                'name' => 'ContentImageComponent',
                'fields' => [
                    'image' => ['type' => Type::string(), 'description' => 'Image URL'],
                    'imageThumbnail' => ['type' => Type::string()],
                ],
            ]);
        }

        return self::$imageComponentType;
    }

    private static function getTextWithImageType(): ObjectType
    {
        if (self::$textWithImageType === null) {
            self::$textWithImageType = new ObjectType([
                'name' => 'ContentTextWithImage',
                'fields' => [
                    'text' => ['type' => Type::string(), 'description' => 'HTML content'],
                    'image' => ['type' => Type::string(), 'description' => 'Image URL'],
                    'imageThumbnail' => ['type' => Type::string()],
                    'imagePosition' => ['type' => Type::string(), 'description' => 'left, right'],
                ],
            ]);
        }

        return self::$textWithImageType;
    }

    private static function getImageContentType(): ObjectType
    {
        if (self::$imageContentType === null) {
            self::$imageContentType = new ObjectType([
                'name' => 'ContentImageContent',
                'fields' => [
                    'images' => ['type' => Type::listOf(self::getGalleryImageType())],
                ],
            ]);
        }

        return self::$imageContentType;
    }

    private static function getScriptType(): ObjectType
    {
        if (self::$scriptType === null) {
            self::$scriptType = new ObjectType([
                'name' => 'ContentScript',
                'fields' => [
                    'scriptSrc' => ['type' => Type::string()],
                    'bodyContent' => ['type' => Type::string()],
                ],
            ]);
        }

        return self::$scriptType;
    }

    private static function getWidgetType(): ObjectType
    {
        if (self::$widgetType === null) {
            self::$widgetType = new ObjectType([
                'name' => 'ContentWidget',
                'fields' => [
                    'ident' => ['type' => Type::string(), 'description' => 'shopReviews, search, categories, newestProducts, blog, processedWhirlpools'],
                ],
            ]);
        }

        return self::$widgetType;
    }

    private static function getHeroSwiperType(): ObjectType
    {
        if (self::$heroSwiperType === null) {
            self::$heroSwiperType = new ObjectType([
                'name' => 'ContentHeroSwiper',
                'fields' => [
                    'slides' => ['type' => Type::listOf(self::getHeroSlideType())],
                ],
            ]);
        }

        return self::$heroSwiperType;
    }

    private static function getParalaxContentType(): ObjectType
    {
        if (self::$paralaxContentType === null) {
            self::$paralaxContentType = new ObjectType([
                'name' => 'ContentParalaxContent',
                'fields' => [
                    'title' => ['type' => Type::string()],
                    'text' => ['type' => Type::string(), 'description' => 'HTML content'],
                    'button' => ['type' => self::getButtonActionType()],
                    'image' => ['type' => Type::string(), 'description' => 'Image URL'],
                    'imageThumbnail' => ['type' => Type::string()],
                    'video' => ['type' => self::getVideoType()],
                    'overlay' => ['type' => Type::string(), 'description' => 'none, blur, lightDark, dark'],
                ],
            ]);
        }

        return self::$paralaxContentType;
    }

    private static function getProductGridType(): ObjectType
    {
        if (self::$productGridType === null) {
            self::$productGridType = new ObjectType([
                'name' => 'ContentProductGrid',
                'fields' => [
                    'tabTitle' => ['type' => Type::string(), 'description' => 'Tab/section title (localized)'],
                    'products' => ['type' => Type::listOf(ProductFragment::getType()), 'description' => 'Resolved products'],
                ],
            ]);
        }

        return self::$productGridType;
    }

    private static function getCategoryGridType(): ObjectType
    {
        if (self::$categoryGridType === null) {
            self::$categoryGridType = new ObjectType([
                'name' => 'ContentCategoryGrid',
                'fields' => [
                    'categoryIds' => ['type' => Type::listOf(Type::int()), 'description' => 'Pimcore IDs of Collection objects'],
                ],
            ]);
        }

        return self::$categoryGridType;
    }

    // --- Shared sub-types ---

    public static function getButtonActionType(): ObjectType
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

    private static function getGalleryImageType(): ObjectType
    {
        if (self::$galleryImageType === null) {
            self::$galleryImageType = new ObjectType([
                'name' => 'ContentGalleryImage',
                'fields' => [
                    'url' => ['type' => Type::nonNull(Type::string())],
                    'thumbnailUrl' => ['type' => Type::string()],
                ],
            ]);
        }

        return self::$galleryImageType;
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
                    'primaryButton' => ['type' => self::getButtonActionType()],
                    'secondaryButton' => ['type' => self::getButtonActionType()],
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
