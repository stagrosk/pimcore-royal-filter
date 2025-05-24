<?php

namespace App\Shopify\Service\PriceList;

use App\Shopify\Model\PriceList\PriceListCreateInput;
use Pimcore\Model\DataObject\AbstractObject;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: self::MAPPER_TAG)]
interface IShopifyPriceListMapper
{
    const MAPPER_TAG = 'shopify_price_list_mapper';

    public function getMapperServiceKey(): string;

    public function getObjectClassId(): string;

    public function getShopifyChannelKey(): string;

    public function getMappedObject(PriceListCreateInput $priceListCreateInput, AbstractObject $object): PriceListCreateInput;
}
