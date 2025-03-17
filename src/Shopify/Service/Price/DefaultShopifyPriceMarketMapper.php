<?php

namespace App\Shopify\Service\Price;

use App\Shopify\Model\Price\ShopifyPrice;
use Pimcore\Model\DataObject\Concrete;
use Pimcore\Model\DataObject\Product;

class DefaultShopifyPriceMarketMapper implements IShopifyPriceMapper
{
    const DEFAULT_MAPPER_SERVICE_KEY = 'default_shopify_price_market';
    const PRODUCT_CLASS_ID = 'DEFAULT_PROD';

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
        $shopifyPriceModel->setPriceListId("xxxxxxxxx");
        $shopifyPriceModel->setMarketId("xxxxxxxxx");

        return $shopifyPriceModel;
    }
}
