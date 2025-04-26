<?php

namespace App\Shopify\Service\Metafields;

use App\Shopify\Model\Metafields\MetafieldAccessInput;
use App\Shopify\Model\Metafields\MetafieldAdminAccessInputEnum;
use App\Shopify\Model\Metafields\MetafieldCustomerAccountAccessInputEnum;
use App\Shopify\Model\Metafields\MetafieldDefinitionInput;
use App\Shopify\Model\Metafields\MetafieldOwnerTypeEnum;
use App\Shopify\Model\Metafields\MetafieldStorefrontAccessInputEnum;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\ShopifyMetafieldDefinition;

class ShopifyShopifyMetafieldDefinitionMapper implements ShopifyMetafieldDefinitionMapperInterface
{
    const CLASS_ID = 'ClassificationStore';
    const SHOPIFY_CHANNEL_KEY = 'shopify_1';

    public function getMapperServiceKey(): string
    {
        return ShopifyMetafieldsMapperInterface::MAPPER_TAG;
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
     * @param \App\Shopify\Model\Metafields\MetafieldDefinitionInput $input
     * @param \Pimcore\Model\DataObject\AbstractObject|\Pimcore\Model\DataObject\ShopifyMetafieldDefinition $object
     *
     * @return \App\Shopify\Model\Metafields\MetafieldDefinitionInput
     */
    public function getMappedObject(MetafieldDefinitionInput $input, ShopifyMetafieldDefinition|AbstractObject $object): MetafieldDefinitionInput
    {
        $input->setName($object->getName());
        $input->setKey($object->getMetaKey());
        $input->setType(strtolower($object->getMetaType()));
        $input->setNamespace($object->getNamespace());
        $input->setPin($object->getIsPinned());
        $input->setDescription($object->getDescription());

        $ownerTypeEnum = MetafieldOwnerTypeEnum::from($object->getOwnerType());
        $input->setOwnerType($ownerTypeEnum);

        // access
        $access = new MetafieldAccessInput();

        $adminAccess = MetafieldAdminAccessInputEnum::from($object->getAdminAccess());
        $access->setAdmin($adminAccess);

        $customerAccountAccess = MetafieldCustomerAccountAccessInputEnum::from($object->getCustomerAccountAccess());
        $access->setCustomerAccount($customerAccountAccess);

        $storefrontAccess = MetafieldStorefrontAccessInputEnum::from($object->getStorefrontAccess());
        $access->setStorefront($storefrontAccess);

        $input->setAccess($access);

        return $input;
    }
}
