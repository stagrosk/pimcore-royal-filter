<?php

namespace App\Shopify\Service\Product;

use App\Shopify\Model\Product\ProductCreateInput;
use App\Shopify\Model\Product\ProductUpdateInput;
use Pimcore\Model\DataObject\AbstractObject;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: self::MAPPER_TAG)]
interface IShopifyProductMapper
{
    const MAPPER_TAG = 'shopify_product_mapper';

    public function getMapperServiceKey(): string;

    public function getProductClassId(): string;

    public function getShopifyChannelKey(): string;

    public function getMappedObject(ProductCreateInput|ProductUpdateInput $input, AbstractObject $object): ProductCreateInput|ProductUpdateInput;
}
