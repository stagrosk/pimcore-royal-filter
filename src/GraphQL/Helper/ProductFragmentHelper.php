<?php

namespace App\GraphQL\Helper;

use Pimcore\Model\DataObject\Data\RgbaColor;
use Pimcore\Model\DataObject\Fieldcollection\Data\Price;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\CustomerRole;
use Pimcore\Model\DataObject\ProductFlag;
use Pimcore\Tool;

class ProductFragmentHelper
{
    private const THUMBNAIL_TILE = 'product-tile';
    private const THUMBNAIL_PREVIEW = 'product-preview';

    /**
     * Transform Product object to ProductFragment array
     *
     * @param \Pimcore\Model\DataObject\Product $product
     * @param string|null $language
     *
     * @return array
     */
    public static function transform(Product $product, ?string $language = null): array
    {
        $language = $language ?? Tool::getDefaultLanguage();

        return [
            // Basic fields
            'apiId' => $product->getApiId(),
            'title' => $product->getTitle($language),
            'shortDescription' => $product->getShortDescription($language),
            'description' => $product->getDescription($language),
            'sku' => $product->getSku(),
            'handle' => $product->getHandle($language),
            'slug' => $product->getSlug($language),
            'status' => $product->getStatus(),
            'productType' => $product->getProductType(),
            'ean' => $product->getEan(),
            'madeIn' => $product->getMadeIn(),
            'isFreeGift' => $product->getIsFreeGift() ?? false,
            'isGiftCard' => $product->getIsGiftCard() ?? false,
            'isVirtualProduct' => $product->getIsVirtualProduct() ?? false,

            // Images
            'imageTile' => self::getFirstImageUrl($product, self::THUMBNAIL_TILE),
            'imagePreview' => self::getFirstImageUrl($product, self::THUMBNAIL_PREVIEW),
            'images' => self::getImages($product),

            // Prices
            'prices' => self::getPrices($product),

            // Flags
            'flags' => self::getFlags($product, $language),

            // Customer Roles
            'customerRoles' => self::getCustomerRoles($product, $language),

            // Collections
            'collections' => self::getCollections($product, $language),

            // Manufacturer
            'manufacturer' => self::getManufacturer($product),

            // Canonicals
            'canonicals' => self::getCanonicals($product),
        ];
    }

    /**
     * Get first image URL from gallery
     *
     * @param \Pimcore\Model\DataObject\Product $product
     * @param string $thumbnailName
     *
     * @return string|null
     */
    private static function getFirstImageUrl(Product $product, string $thumbnailName): ?string
    {
        $gallery = $product->getImageGallery();

        if ($gallery === null) {
            return null;
        }

        $items = $gallery->getItems();
        if (empty($items)) {
            return null;
        }

        $firstItem = $items[0];
        $image = $firstItem->getImage();

        if ($image === null) {
            return null;
        }

        return $image->getThumbnail($thumbnailName)?->getPath();
    }

    /**
     * Get all images from gallery
     *
     * @param \Pimcore\Model\DataObject\Product $product
     *
     * @return array
     */
    private static function getImages(Product $product): array
    {
        $gallery = $product->getImageGallery();

        if ($gallery === null) {
            return [];
        }

        $images = [];
        foreach ($gallery->getItems() as $item) {
            $image = $item->getImage();
            if ($image === null) {
                continue;
            }

            $images[] = [
                'tile' => $image->getThumbnail(self::THUMBNAIL_TILE)?->getPath(),
                'preview' => $image->getThumbnail(self::THUMBNAIL_PREVIEW)?->getPath(),
                'original' => $image->getFullPath(),
            ];
        }

        return $images;
    }

    /**
     * Get prices from product
     *
     * @param \Pimcore\Model\DataObject\Product $product
     *
     * @return array
     */
    private static function getPrices(Product $product): array
    {
        $pricesCollection = $product->getPrices();

        if ($pricesCollection === null) {
            return [];
        }

        $prices = [];
        foreach ($pricesCollection->getItems() as $priceItem) {
            if (!$priceItem instanceof Price) {
                continue;
            }

            $priceList = $priceItem->getPriceList();

            $prices[] = [
                'priceListId' => $priceList?->getKey(),
                'priceListName' => $priceList?->getName(),
                'price' => $priceItem->getPrice(),
                'compareAtPrice' => $priceItem->getCompareAtPrice(),
                'wholesalePrice' => $priceItem->getWholesalePrice(),
                'unitPrice' => $priceItem->getUnitPrice(),
            ];
        }

        return $prices;
    }

    /**
     * Get collections from product
     *
     * @param \Pimcore\Model\DataObject\Product $product
     * @param string $language
     *
     * @return array
     */
    private static function getCollections(Product $product, string $language): array
    {
        $collections = $product->getCollections();

        if (empty($collections)) {
            return [];
        }

        $result = [];
        foreach ($collections as $collection) {
            $result[] = [
                'apiId' => $collection->getKey(),
                'title' => $collection->getTitle($language),
                'handle' => $collection->getHandle($language),
                'slug' => $collection->getSlug($language),
            ];
        }

        return $result;
    }

    /**
     * Get manufacturer from product
     *
     * @param \Pimcore\Model\DataObject\Product $product
     *
     * @return array|null
     */
    private static function getManufacturer(Product $product): ?array
    {
        $manufacturer = $product->getManufacturer();

        if ($manufacturer === null) {
            return null;
        }

        return [
            'id' => $manufacturer->getId(),
            'name' => $manufacturer->getTitle(),
        ];
    }

    /**
     * Get canonicals with handles for all language mutations
     *
     * @param \Pimcore\Model\DataObject\Product $product
     *
     * @return array
     */
    private static function getFlags(Product $product, string $language): array
    {
        $flags = $product->getFlags();

        if (empty($flags)) {
            return [];
        }

        $result = [];
        foreach ($flags as $flag) {
            if (!$flag instanceof ProductFlag) {
                continue;
            }

            $color = $flag->getColor();

            $result[] = [
                'code' => $flag->getCode(),
                'title' => $flag->getTitle($language),
                'color' => $color instanceof RgbaColor ? self::rgbaToHex($color) : null,
            ];
        }

        return $result;
    }

    private static function getCustomerRoles(Product $product, string $language): array
    {
        $roles = $product->getCustomerRoles();

        if (empty($roles)) {
            return [];
        }

        $result = [];
        foreach ($roles as $role) {
            if (!$role instanceof CustomerRole) {
                continue;
            }

            $result[] = [
                'code' => $role->getCode(),
                'title' => $role->getTitle($language),
            ];
        }

        return $result;
    }

    private static function rgbaToHex(RgbaColor $color): string
    {
        return sprintf('#%02x%02x%02x', $color->getR(), $color->getG(), $color->getB());
    }

    private static function getCanonicals(Product $product): array
    {
        $canonicals = [];
        $validLanguages = Tool::getValidLanguages();

        foreach ($validLanguages as $lang) {
            $handle = $product->getHandle($lang);
            $slug = $product->getSlug($lang);

            if (!empty($handle) || !empty($slug)) {
                $canonicals[] = [
                    'language' => $lang,
                    'handle' => $handle,
                    'slug' => $slug,
                ];
            }
        }

        return $canonicals;
    }

    /**
     * Transform multiple products
     *
     * @param array $products
     * @param string|null $language
     *
     * @return array
     */
    public static function transformList(array $products, ?string $language = null): array
    {
        return array_map(fn($product) => self::transform($product, $language), $products);
    }
}
