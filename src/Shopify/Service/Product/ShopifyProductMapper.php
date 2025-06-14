<?php

namespace App\Shopify\Service\Product;

use App\Shopify\Model\Metafields\MetafieldInputs;
use App\Shopify\Model\Product\ProductCreateInput;
use App\Shopify\Model\Product\ProductUpdateInput;
use App\Shopify\Model\Seo\SeoInput;
use App\Shopify\Service\Metafields\ShopifyMetafieldsMapper;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Collection;
use Pimcore\Model\DataObject\Product;

class ShopifyProductMapper implements IShopifyProductMapper
{
    /**
     * @param \App\Shopify\Service\Metafields\ShopifyMetafieldsMapper $metafieldsMapper
     */
    public function __construct(
        private readonly ShopifyMetafieldsMapper $metafieldsMapper
    ) {
    }

    const DEFAULT_MAPPER_SERVICE_KEY = 'shopify_product';
    const PRODUCT_CLASS_ID = 'DEFAULT_PROD';
    const SHOPIFY_CHANNEL_KEY = 'shopify_1';

    public function getMapperServiceKey(): string
    {
        return self::DEFAULT_MAPPER_SERVICE_KEY;
    }

    public function getProductClassId(): string
    {
        return self::PRODUCT_CLASS_ID;
    }

    public function getShopifyChannelKey(): string
    {
        return self::SHOPIFY_CHANNEL_KEY;
    }

    /**
     * @param \App\Shopify\Model\Product\ProductCreateInput|\App\Shopify\Model\Product\ProductUpdateInput $input
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     *
     * @throws \Exception
     * @return \App\Shopify\Model\Product\ProductCreateInput|\App\Shopify\Model\Product\ProductUpdateInput
     */
    public function getMappedObject(ProductCreateInput|ProductUpdateInput $input, AbstractObject $object): ProductCreateInput|ProductUpdateInput
    {
        /** @var Product $object */
        if (method_exists($input, 'setId')) {
            $input->setId($object->getApiId());
        }

        $input->setTitle($object->getTitle());
        $input->setDescriptionHtml($object->getDescription());
        $input->setHandle($object->getHandle());
        $input->setVendor($object->getManufacturer()?->getTitle());
        $input->setStatus($object->getStatus());
        $input->setCategory($object->getTaxonomyCategory());
        $input->setProductType($object->getProductType());

        // TODO: finish
//        $input->setTags(); // brand?
//        $input->setSku($object->getSku()); // as custom field?

        // seo
        $seo = new SeoInput($object->getSeoTitle(), $object->getSeoDescription());
        $input->setSeo($seo);

        // collections
        $collectionsToJoin = collect($object->getCollections())->map(fn(Collection $collection) => $collection->getApiId())->toArray();
        $input->setCollectionsToJoin($collectionsToJoin);

        // metafields
        $metafieldInput = $this->metafieldsMapper->getMappedObject(new MetafieldInputs(), $object);
        $input->setMetafields($metafieldInput->getAsArray());

        return $input;
    }
}
