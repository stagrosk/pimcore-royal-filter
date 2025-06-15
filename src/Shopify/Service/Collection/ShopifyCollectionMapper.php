<?php

namespace App\Shopify\Service\Collection;

use App\Shopify\Model\Collection\CollectionInput;
use App\Shopify\Model\Media\CreateMediaInput;
use App\Shopify\Model\Metafields\MetafieldInputs;
use App\Shopify\Service\Metafields\ShopifyMetafieldsMapper;
use Pimcore\Model\Asset;
use Pimcore\Model\DataObject\AbstractObject;

class ShopifyCollectionMapper implements IShopifyCollectionMapper
{
    /**
     * @param \App\Shopify\Service\Metafields\ShopifyMetafieldsMapper $metafieldsMapper
     */
    public function __construct(
        private readonly ShopifyMetafieldsMapper $metafieldsMapper
    ) {
    }

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

    /**
     * @param \App\Shopify\Model\Collection\CollectionInput $input
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     *
     * @throws \Exception
     * @return \App\Shopify\Model\Collection\CollectionInput
     */
    public function getMappedObject(CollectionInput $input, AbstractObject $object): CollectionInput
    {
        /** @var \Pimcore\Model\DataObject\Collection $object */
        $input->setId($object->getApiId());
        $input->setTitle($object->getTitle());
        $input->setDescriptionHtml($object->getDescription());
        $input->setHandle($object->getHandle());

        // TODO: finish
        //$shopifyCollectionModel->setSeo();
        //$shopifyCollectionModel->setRules();

        // metafields
        $metafieldInput = $this->metafieldsMapper->getMappedObject(new MetafieldInputs(), $object);
        $input->setMetafields($metafieldInput->getAsArray());

        // image
        $image = $object->getImage();
        if ($image instanceof Asset) {
            $shopifyMedia = new CreateMediaInput($image->getFrontendPath(), $object->getTitle());
            $input->setImage($shopifyMedia);
        }

        // products TODO: load all dependencies as products and add here?
//        $productIds = [];
//        foreach ($object->getProducts() as $product) {
//            $productIds[] = $product->getApiId();
//        }
//        $shopifyCollectionModel->setProducts($productIds);

        return $input;
    }
}
