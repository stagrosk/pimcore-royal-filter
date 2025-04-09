<?php

namespace App\Shopify\Service\Product\Variant;

use App\Shopify\Model\Product\Variant\ProductVariantsBulkInput;
use Pimcore\Model\DataObject\AbstractObject;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: self::MAPPER_TAG)]
interface IShopifyVariantMapper
{
    const MAPPER_TAG = 'shopify_product_mapper';

    public function getMapperServiceKey(): string;

    public function getProductClassId(): string;

    public function getShopifyChannelKey(): string;

    public function getMappedObject(ProductVariantsBulkInput $input, AbstractObject $object): ProductVariantsBulkInput;
}
