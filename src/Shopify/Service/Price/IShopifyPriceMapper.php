<?php

namespace App\Shopify\Service\Price;

use App\Shopify\Model\Price\ShopifyPrice;
use Pimcore\Model\DataObject\Concrete;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: self::MAPPER_TAG)]
interface IShopifyPriceMapper
{
    const MAPPER_TAG = 'shopify_price_mapper';

    public function getMapperServiceKey(): string;

    public function getProductClassId(): string;

    public function getShopifyChannelKey(): string;

    public function getMappedPrice(ShopifyPrice $shopifyPriceModel, Concrete $product): ShopifyPrice;
}
