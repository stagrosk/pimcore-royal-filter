<?php

namespace App\Shopify\Service\Product;

use App\Shopify\Model\Product\ShopifyProduct;
use Pimcore\Model\DataObject\Concrete;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: self::MAPPER_TAG)]
interface IShopifyProductMapper
{
    const MAPPER_TAG = 'shopify_product_mapper';

    public function getMapperServiceKey(): string;

    public function getProductClassId(): string;

    public function getShopifyChannelKey(): string;

    public function getMappedProduct(ShopifyProduct $shopifyProductModel, Concrete $product): ShopifyProduct;
}
