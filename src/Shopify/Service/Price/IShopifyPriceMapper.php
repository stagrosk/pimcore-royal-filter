<?php

namespace App\Shopify\Service\Price;

use App\Shopify\Model\Price\PriceListUpdateInputs;
use App\Shopify\Model\Price\VariantPriceInput;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\PriceList;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: self::MAPPER_TAG)]
interface IShopifyPriceMapper
{
    const MAPPER_TAG = 'shopify_price_mapper';

    public function getMapperServiceKey(): string;

    public function getProductClassId(): string;

    public function getShopifyChannelKey(): string;

    /**
     * @param \App\Shopify\Model\Price\PriceListUpdateInputs|\App\Shopify\Model\Price\VariantPriceInput $input
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     * @param \Pimcore\Model\DataObject\PriceList|null $priceList
     *
     * @throws \Exception
     * @return \App\Shopify\Model\Price\PriceListUpdateInputs|\App\Shopify\Model\Price\VariantPriceInput
     */
    public function getMappedObject(
        PriceListUpdateInputs|VariantPriceInput $input,
        AbstractObject $object,
        ?PriceList $priceList = null
    ): PriceListUpdateInputs|VariantPriceInput;
}
