<?php

namespace App\Shopify\Service\PriceList;

use App\Shopify\Model\PriceList\PriceListAdjustmentInput;
use App\Shopify\Model\PriceList\PriceListAdjustmentSettingsInput;
use App\Shopify\Model\PriceList\PriceListAdjustmentTypeEnum;
use App\Shopify\Model\PriceList\PriceListCompareAtModeEnum;
use App\Shopify\Model\PriceList\PriceListCreateInput;
use App\Shopify\Model\PriceList\PriceListParentCreateInput;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\PriceList;

class ShopifyPriceListMapper implements IShopifyPriceListMapper
{
    const CLASS_ID = 'PriceList';
    const SHOPIFY_CHANNEL_KEY = 'shopify_1';

    public function getMapperServiceKey(): string
    {
        return IShopifyPriceListMapper::MAPPER_TAG;
    }

    public function getObjectClassId(): string
    {
        return self::CLASS_ID;
    }

    public function getShopifyChannelKey(): string
    {
        return self::SHOPIFY_CHANNEL_KEY;
    }

    /**
     * @param \App\Shopify\Model\PriceList\PriceListCreateInput $priceListCreateInput
     * @param \Pimcore\Model\DataObject\AbstractObject|\Pimcore\Model\DataObject\PriceList $object
     *
     * @return \App\Shopify\Model\PriceList\PriceListCreateInput
     */
    public function getMappedObject(PriceListCreateInput $priceListCreateInput, AbstractObject|PriceList $object): PriceListCreateInput
    {
//        $priceListCreateInput->setCatalogId($object->getCatalogId());
        $priceListCreateInput->setName($object->getName());
        $priceListCreateInput->setCurrency($object->getCurrency());

        $priceListAdjustmentInput = new PriceListAdjustmentInput(
            PriceListAdjustmentTypeEnum::from($object->getAdjustmentType()),
            $object->getPercentage(),
        );

        $priceListAdjustmentSettingsInput = new PriceListAdjustmentSettingsInput(PriceListCompareAtModeEnum::from($object->getCompareAtMode()));

        $parent = new PriceListParentCreateInput($priceListAdjustmentInput, $priceListAdjustmentSettingsInput);
        $priceListCreateInput->setParent($parent);

        return $priceListCreateInput;
    }
}
