<?php

namespace App\Shopify\Service\Price;

use App\Shopify\Model\Price\ShopifyPrice;
use Pimcore\Model\DataObject\Concrete;
use Pimcore\Model\DataObject\Product;

class DefaultShopifyPriceMapper implements IShopifyPriceMapper
{
    const DEFAULT_MAPPER_SERVICE_KEY = 'default_shopify_price';
    const PRODUCT_CLASS_ID = 'product';
    const SHOPIFY_CHANNEL_KEY = 'shopify_1';

    public function getMapperServiceKey(): string
    {
        return self::DEFAULT_MAPPER_SERVICE_KEY;
    }

    public function getProductClassId(): string
    {
        return self::PRODUCT_CLASS_ID;
    }

    public function getShopifyChannelKey(): string
    {
        return self::SHOPIFY_CHANNEL_KEY;
    }

    public function getMappedPrice(ShopifyPrice $shopifyPriceModel, Concrete $product): ShopifyPrice
    {
        /** @var Product $product */
        $shopifyPriceModel->setSku($product->getSku());
        $shopifyPriceModel->setPrice($product->getPrice_EUR());
        $shopifyPriceModel->setCompareAtPrice($product->getCompareAtPrice_EUR());

        return $shopifyPriceModel;
    }
}
