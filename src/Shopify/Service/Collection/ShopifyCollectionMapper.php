<?php

namespace App\Shopify\Service\Collection;

use App\Shopify\Model\Base\ShopifyImageInput;
use App\Shopify\Model\Collection\ShopifyCollectionInput;
use Pimcore\Model\Asset;
use Pimcore\Model\DataObject\Concrete;

class ShopifyCollectionMapper implements IShopifyCollectionMapper
{
    const DEFAULT_MAPPER_SERVICE_KEY = 'default_shopify_collection';
    const COLLECTION_CLASS_ID = 'DEFAULT_PROD';
    const SHOPIFY_CHANNEL_KEY = 'shopify_1';

    public function getMapperServiceKey(): string
    {
        return self::DEFAULT_MAPPER_SERVICE_KEY;
    }

    public function getCollectionClassId(): string
    {
        return self::COLLECTION_CLASS_ID;
    }

    public function getShopifyChannelKey(): string
    {
        return self::SHOPIFY_CHANNEL_KEY;
    }

    public function getMappedCollection(ShopifyCollectionInput $shopifyCollectionModel, Concrete $category): ShopifyCollectionInput
    {
        /** @var \Pimcore\Model\DataObject\Category $category */
        $shopifyCollectionModel->setId($category->getApiId());
        $shopifyCollectionModel->setTitle($category->getTitle());
        $shopifyCollectionModel->setDescriptionHtml($category->getDescription());
        $shopifyCollectionModel->setHandle($category->getUrlHandle());

        // TODO: finish
        //$shopifyCollectionModel->setMetafields();
        //$shopifyCollectionModel->setSeo();
        //$shopifyCollectionModel->setRules();

        // image
        $image = $category->getImage();
        if ($image instanceof Asset) {
            $shopifyMedia = new ShopifyImageInput($image->getFrontendPath(), $category->getTitle());
            $shopifyCollectionModel->setImage($shopifyMedia);
        }

        // products
        $productIds = [];
        foreach ($category->getProducts() as $product) {
            $productIds[] = $product->getApiId();
        }
        $shopifyCollectionModel->setProducts($productIds);

        return $shopifyCollectionModel;
    }
}
