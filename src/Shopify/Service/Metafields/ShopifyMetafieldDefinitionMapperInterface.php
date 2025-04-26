<?php

namespace App\Shopify\Service\Metafields;

use App\Shopify\Model\Metafields\MetafieldDefinitionInput;
use Pimcore\Model\DataObject\AbstractObject;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: self::MAPPER_TAG)]
interface ShopifyMetafieldDefinitionMapperInterface
{
    const MAPPER_TAG = 'shopify_metafield_definition_mapper';

    public function getMapperServiceKey(): string;

    public function getObjectClassId(): string;

    public function getShopifyChannelKey(): string;

    public function getMappedObject(MetafieldDefinitionInput $input, AbstractObject $object): MetafieldDefinitionInput;
}
