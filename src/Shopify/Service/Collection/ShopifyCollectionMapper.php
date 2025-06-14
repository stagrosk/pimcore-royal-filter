<?php

namespace App\Shopify\Service\Collection;

use App\Shopify\Model\Collection\CollectionInput;
use App\Shopify\Model\Media\CreateMediaInput;
use Pimcore\Model\Asset;
use Pimcore\Model\DataObject\AbstractObject;

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

    public function getMappedObject(CollectionInput $shopifyCollectionModel, AbstractObject $object): CollectionInput
    {
        /** @var \Pimcore\Model\DataObject\Category $object */
        $shopifyCollectionModel->setId($object->getApiId());
        $shopifyCollectionModel->setTitle($object->getTitle());
        $shopifyCollectionModel->setDescriptionHtml($object->getDescription());
        $shopifyCollectionModel->setHandle($object->getSlug());   // TODO: lang? default only? do we need it in shopify?

        // TODO: finish
        //$shopifyCollectionModel->setMetafields();
        //$shopifyCollectionModel->setSeo();
        //$shopifyCollectionModel->setRules();

        // image
        $image = $object->getImage();
        if ($image instanceof Asset) {
            $shopifyMedia = new CreateMediaInput($image->getFrontendPath(), $object->getTitle());
            $shopifyCollectionModel->setImage($shopifyMedia);
        }

        // products TODO: load all dependencies as products and add here?
//        $productIds = [];
//        foreach ($object->getProducts() as $product) {
//            $productIds[] = $product->getApiId();
//        }
//        $shopifyCollectionModel->setProducts($productIds);

        return $shopifyCollectionModel;
    }
}
