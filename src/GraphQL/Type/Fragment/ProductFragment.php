<?php

namespace App\GraphQL\Type\Fragment;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ProductFragment
{
    private static ?ObjectType $type = null;
    private static ?ObjectType $imageType = null;
    private static ?ObjectType $priceType = null;
    private static ?ObjectType $collectionType = null;
    private static ?ObjectType $manufacturerType = null;
    private static ?ObjectType $canonicalType = null;
    private static ?ObjectType $flagType = null;
    private static ?ObjectType $customerRoleType = null;

    /**
     * Get the singleton ProductFragment type
     *
     * @return \GraphQL\Type\Definition\ObjectType
     */
    public static function getType(): ObjectType
    {
        if (self::$type === null) {
            self::$type = new ObjectType([
                'name' => 'ProductFragment',
                'description' => 'Reusable product type for wishlist, cart, and other product lists',
                'fields' => [
                    // Basic fields
                    'apiId' => [
                        'type' => Type::string(),
                        'description' => 'Product API ID',
                    ],
                    'title' => [
                        'type' => Type::string(),
                        'description' => 'Product title (localized)',
                    ],
                    'shortDescription' => [
                        'type' => Type::string(),
                        'description' => 'Short description (localized)',
                    ],
                    'description' => [
                        'type' => Type::string(),
                        'description' => 'Full description (localized)',
                    ],
                    'sku' => [
                        'type' => Type::string(),
                        'description' => 'Product SKU',
                    ],
                    'handle' => [
                        'type' => Type::string(),
                        'description' => 'Product handle/URL slug (localized)',
                    ],
                    'slug' => [
                        'type' => Type::string(),
                        'description' => 'Product slug (localized)',
                    ],
                    'status' => [
                        'type' => Type::string(),
                        'description' => 'Product status (active, draft, archived)',
                    ],
                    'productType' => [
                        'type' => Type::string(),
                        'description' => 'Product type/category',
                    ],
                    'ean' => [
                        'type' => Type::string(),
                        'description' => 'EAN code',
                    ],
                    'madeIn' => [
                        'type' => Type::string(),
                        'description' => 'Country of origin (ISO code)',
                    ],
                    'isFreeGift' => [
                        'type' => Type::boolean(),
                        'description' => 'Whether product is a free gift',
                    ],
                    'isGiftCard' => [
                        'type' => Type::boolean(),
                        'description' => 'Whether product is a gift card',
                    ],
                    'isVirtualProduct' => [
                        'type' => Type::boolean(),
                        'description' => 'Whether product is virtual (no shipping)',
                    ],

                    // Images
                    'imageTile' => [
                        'type' => Type::string(),
                        'description' => 'Tile image URL 240x240 (1:1)',
                    ],
                    'imagePreview' => [
                        'type' => Type::string(),
                        'description' => 'Preview image URL 800x800 (1:1)',
                    ],
                    'images' => [
                        'type' => Type::listOf(self::getImageType()),
                        'description' => 'All product images from gallery',
                    ],

                    // Prices
                    'prices' => [
                        'type' => Type::listOf(self::getPriceType()),
                        'description' => 'Product prices for different price lists',
                    ],

                    // Collections
                    'collections' => [
                        'type' => Type::listOf(self::getCollectionType()),
                        'description' => 'Collections this product belongs to',
                    ],

                    // Manufacturer
                    'manufacturer' => [
                        'type' => self::getManufacturerType(),
                        'description' => 'Product manufacturer',
                    ],

                    // Flags
                    'flags' => [
                        'type' => Type::listOf(self::getFlagType()),
                        'description' => 'Product flags (e.g. novinka, akcia)',
                    ],

                    // Customer Roles
                    'customerRoles' => [
                        'type' => Type::listOf(self::getCustomerRoleType()),
                        'description' => 'Customer roles assigned to this product',
                    ],

                    // Canonicals for language mutations
                    'canonicals' => [
                        'type' => Type::listOf(self::getCanonicalType()),
                        'description' => 'Language handles/slugs for all available languages',
                    ],
                ],
            ]);
        }

        return self::$type;
    }

    /**
     * Get image type (singleton)
     *
     * @return \GraphQL\Type\Definition\ObjectType
     */
    private static function getImageType(): ObjectType
    {
        if (self::$imageType === null) {
            self::$imageType = new ObjectType([
                'name' => 'ProductImage',
                'fields' => [
                    'tile' => [
                        'type' => Type::string(),
                        'description' => 'Tile thumbnail URL 240x240',
                    ],
                    'preview' => [
                        'type' => Type::string(),
                        'description' => 'Preview thumbnail URL 800x800',
                    ],
                    'original' => [
                        'type' => Type::string(),
                        'description' => 'Original image URL',
                    ],
                ],
            ]);
        }

        return self::$imageType;
    }

    /**
     * Get price type (singleton)
     *
     * @return \GraphQL\Type\Definition\ObjectType
     */
    private static function getPriceType(): ObjectType
    {
        if (self::$priceType === null) {
            self::$priceType = new ObjectType([
                'name' => 'ProductPrice',
                'fields' => [
                    'priceListId' => [
                        'type' => Type::string(),
                        'description' => 'Price list identifier',
                    ],
                    'priceListName' => [
                        'type' => Type::string(),
                        'description' => 'Price list name',
                    ],
                    'price' => [
                        'type' => Type::float(),
                        'description' => 'Current price',
                    ],
                    'compareAtPrice' => [
                        'type' => Type::float(),
                        'description' => 'Compare at price (original/crossed out price)',
                    ],
                    'wholesalePrice' => [
                        'type' => Type::float(),
                        'description' => 'Wholesale price',
                    ],
                    'unitPrice' => [
                        'type' => Type::float(),
                        'description' => 'Unit price',
                    ],
                ],
            ]);
        }

        return self::$priceType;
    }

    /**
     * Get collection type (singleton)
     *
     * @return \GraphQL\Type\Definition\ObjectType
     */
    private static function getCollectionType(): ObjectType
    {
        if (self::$collectionType === null) {
            self::$collectionType = new ObjectType([
                'name' => 'ProductCollection',
                'fields' => [
                    'apiId' => [
                        'type' => Type::string(),
                        'description' => 'Collection API ID',
                    ],
                    'title' => [
                        'type' => Type::string(),
                        'description' => 'Collection title',
                    ],
                    'handle' => [
                        'type' => Type::string(),
                        'description' => 'Collection handle',
                    ],
                    'slug' => [
                        'type' => Type::string(),
                        'description' => 'Collection slug',
                    ],
                ],
            ]);
        }

        return self::$collectionType;
    }

    /**
     * Get manufacturer type (singleton)
     *
     * @return \GraphQL\Type\Definition\ObjectType
     */
    private static function getManufacturerType(): ObjectType
    {
        if (self::$manufacturerType === null) {
            self::$manufacturerType = new ObjectType([
                'name' => 'ProductManufacturer',
                'fields' => [
                    'id' => [
                        'type' => Type::int(),
                        'description' => 'Manufacturer ID',
                    ],
                    'name' => [
                        'type' => Type::string(),
                        'description' => 'Manufacturer name',
                    ],
                ],
            ]);
        }

        return self::$manufacturerType;
    }

    /**
     * Get canonical type (singleton)
     *
     * @return \GraphQL\Type\Definition\ObjectType
     */
    private static function getFlagType(): ObjectType
    {
        if (self::$flagType === null) {
            self::$flagType = new ObjectType([
                'name' => 'ProductFlag',
                'fields' => [
                    'code' => [
                        'type' => Type::string(),
                        'description' => 'Flag code identifier',
                    ],
                    'title' => [
                        'type' => Type::string(),
                        'description' => 'Flag title (localized)',
                    ],
                    'color' => [
                        'type' => Type::string(),
                        'description' => 'Flag color as hex string',
                    ],
                ],
            ]);
        }

        return self::$flagType;
    }

    private static function getCustomerRoleType(): ObjectType
    {
        if (self::$customerRoleType === null) {
            self::$customerRoleType = new ObjectType([
                'name' => 'ProductCustomerRole',
                'fields' => [
                    'code' => [
                        'type' => Type::string(),
                        'description' => 'Role code identifier',
                    ],
                    'title' => [
                        'type' => Type::string(),
                        'description' => 'Role title (localized)',
                    ],
                ],
            ]);
        }

        return self::$customerRoleType;
    }

    private static function getCanonicalType(): ObjectType
    {
        if (self::$canonicalType === null) {
            self::$canonicalType = new ObjectType([
                'name' => 'ProductCanonical',
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
}