<?php

namespace App\Shopify\Service\Metafields;

use App\Shopify\Model\Metafields\MetafieldInputs;
use Pimcore\Model\DataObject\AbstractObject;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: self::MAPPER_TAG)]
interface IShopifyMetafieldsMapper
{
    const MAPPER_TAG = 'shopify_metafields_mapper';

    public function getMapperServiceKey(): string;

    public function getObjectClassId(): string;

    public function getShopifyChannelKey(): string;

    public function getMappedObject(MetafieldInputs $metafieldInputs, AbstractObject $object): MetafieldInputs;
}
