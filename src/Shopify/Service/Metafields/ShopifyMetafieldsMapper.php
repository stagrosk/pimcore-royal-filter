<?php

namespace App\Shopify\Service\Metafields;

use App\Shopify\Model\Metafields\MetafieldInput;
use App\Shopify\Model\Metafields\MetafieldInputs;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\ShopifyMetafieldDefinition;

class ShopifyMetafieldsMapper implements ShopifyMetafieldsMapperInterface
{
    const CLASS_ID = 'ClassificationStore';
    const SHOPIFY_CHANNEL_KEY = 'shopify_1';

    /**
     * @param \App\Shopify\Service\Metafields\ShopifyMetafieldService $shopifyMetafieldService
     */
    public function __construct(
        private readonly ShopifyMetafieldService $shopifyMetafieldService,
    ) {
    }

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
     * Map metafields from dataObject on object based on classification store values on object
     *
     * @param \App\Shopify\Model\Metafields\MetafieldInputs $inputs
     * @param \Pimcore\Model\DataObject\AbstractObject|\Pimcore\Model\DataObject\Product $object
     *
     * @throws \Exception
     * @return \App\Shopify\Model\Metafields\MetafieldInputs
     */
    public function getMappedObject(MetafieldInputs $inputs, Product|AbstractObject $object): MetafieldInputs
    {
        $metafieldDefinitions = $this->shopifyMetafieldService->getObjectMetafieldDefinitions($object);
        if (!empty($metafieldDefinitions)) {
            // loop all definitions and add values to them to be sent on product
            foreach ($metafieldDefinitions['addMetafields'] as $item) {
                /** @var \App\Model\ClassificationStoreMappingItem $classificationStoreMappingItem */
                $classificationStoreMappingItem = $item['classificationStoreMappingItem'];
                /** @var \Pimcore\Model\DataObject\ShopifyMetafieldDefinition $metafieldDefinition */
                $metafieldDefinition = $item['metafieldDefinition'];

                $metafieldInput = new MetafieldInput();
                $metafieldInput->setNamespace($metafieldDefinition->getNamespace());
                $metafieldInput->setKey($metafieldDefinition->getMetaKey());
                $metafieldInput->setType($metafieldDefinition->getMetaType());
                $metafieldInput->setValue($this->shopifyMetafieldService->prepareValue($metafieldDefinition, $classificationStoreMappingItem));

                $inputs->addMetaFieldInput($metafieldInput);
            }
        }

        return $inputs;
    }
}
