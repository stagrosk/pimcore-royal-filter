<?php

namespace App\Shopify\Service\Collection;

use App\Shopify\Model\Collection\CollectionInput;
use Pimcore\Model\DataObject\AbstractObject;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: self::MAPPER_TAG)]
interface IShopifyCollectionMapper
{
    const MAPPER_TAG = 'shopify_collection_mapper';

    public function getMapperServiceKey(): string;

    public function getCollectionClassId(): string;

    public function getShopifyChannelKey(): string;

    public function getMappedObject(CollectionInput $shopifyCollectionModel, AbstractObject $object): CollectionInput;
}
