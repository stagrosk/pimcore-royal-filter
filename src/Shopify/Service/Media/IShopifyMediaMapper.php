<?php

namespace App\Shopify\Service\Media;

use App\Shopify\Model\Media\CreateMediaInputs;
use Pimcore\Model\DataObject\AbstractObject;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: self::MAPPER_TAG)]
interface IShopifyMediaMapper
{
    const MAPPER_TAG = 'shopify_media_mapper';

    public function getMapperServiceKey(): string;

    public function getObjectClassId(): string;

    public function getShopifyChannelKey(): string;

    public function getMappedObject(CreateMediaInputs $createMediaInputs, AbstractObject $object, string $property = 'imageGallery'): CreateMediaInputs;
}
