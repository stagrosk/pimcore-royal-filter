<?php

namespace App\Shopify\Service\Price;

use App\Shopify\Model\Price\MoneyInput;
use App\Shopify\Model\Price\PriceListPriceInput;
use App\Shopify\Model\Price\PriceListUpdateInput;
use App\Shopify\Model\Price\PriceListUpdateInputs;
use App\Shopify\Model\Price\VariantPriceInput;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\PriceList;

class ShopifyPriceMapper implements IShopifyPriceMapper
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
    ): PriceListUpdateInputs|VariantPriceInput {
        if ($input instanceof PriceListUpdateInputs) {
            return $this->processPriceListUpdateInputs($input, $object);
        } elseif ($input instanceof VariantPriceInput && $priceList instanceof PriceList) {
            return $this->processVariantPriceInput($input, $object, $priceList);
        } else {
            throw new \Exception('Incorrect input type or missing priceList');
        }
    }

    /**
     * @param \App\Shopify\Model\Price\VariantPriceInput $input
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     * @param \Pimcore\Model\DataObject\PriceList $priceList
     *
     * @return \App\Shopify\Model\Price\VariantPriceInput
     */
    private function processVariantPriceInput(VariantPriceInput $input, AbstractObject $object, PriceList $priceList): VariantPriceInput
    {
        /** @var \Pimcore\Model\DataObject\Fieldcollection\Data\Price $price */
        if (!empty($object->getPrices())) {
            foreach ($object->getPrices() as $price) {
                // skip if priceList is not published
                if (!$price->getPriceList()->isPublished() || $price->getPriceList() !== $priceList) {
                    continue;
                }

                // currency
                $currency = $priceList->getCurrency();

                // price
                $input->setPrice(new MoneyInput($price->getPrice(), $currency));

                // compare at price
                if (!empty($price->getCompareAtPrice())) {
                    $input->setCompareAtPrice(new MoneyInput($price->getCompareAtPrice(), $currency));
                }

                // we have what we need
                break;
            }
        }

        return $input;
    }

    /**
     * @param \App\Shopify\Model\Price\PriceListUpdateInputs $input
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     *
     * @return \App\Shopify\Model\Price\PriceListUpdateInputs
     */
    private function processPriceListUpdateInputs(PriceListUpdateInputs $input, AbstractObject $object): PriceListUpdateInputs
    {
        /** @var \Pimcore\Model\DataObject\Fieldcollection\Data\Price $price */
        if (!empty($object->getPrices())) {
            foreach ($object->getPrices() as $price) {
                $priceList = $price->getPriceList();
                $currency = $priceList->getCurrency();

                // skip if priceList is not published
                if (!$priceList->isPublished()) {
                    continue;
                }

                // set priceList Id
                $priceListUpdateInput = new PriceListUpdateInput($priceList->getApiId());

                $pricesToAdd = new PriceListPriceInput(
                    $object->getApiId(),
                    new MoneyInput($price->getPrice(), $currency),
                );

                if (!empty($price->getCompareAtPrice())) {
                    $pricesToAdd->setCompareAtPrice(new MoneyInput($price->getCompareAtPrice(), $currency));
                }

                $priceListUpdateInput->addPriceToAdd($pricesToAdd);
                $input->addPricesListUpdateInput($priceListUpdateInput);
            }
        }

        return $input;
    }
}
