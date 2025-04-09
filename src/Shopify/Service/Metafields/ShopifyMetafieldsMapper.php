<?php

namespace App\Shopify\Service\Metafields;

use App\Shopify\Model\Metafields\MetafieldInputs;
use Pimcore\Model\DataObject\AbstractObject;

class ShopifyMetafieldsMapper implements IShopifyMetafieldsMapper
{
    const CLASS_ID = 'ClassificationStore';
    const SHOPIFY_CHANNEL_KEY = 'shopify_1';

    public function getMapperServiceKey(): string
    {
        return IShopifyMetafieldsMapper::MAPPER_TAG;
    }

    public function getObjectClassId(): string
    {
        return self::CLASS_ID;
    }

    public function getShopifyChannelKey(): string
    {
        return self::SHOPIFY_CHANNEL_KEY;
    }

    /**
     * @param \App\Shopify\Model\Metafields\MetafieldInputs $metafieldInputs
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     *
     * @return MetafieldInputs
     */
    public function getMappedObject(MetafieldInputs $metafieldInputs, AbstractObject $object): MetafieldInputs
    {
        return $metafieldInputs;
    }
}
