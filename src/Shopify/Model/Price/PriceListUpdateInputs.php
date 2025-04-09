<?php

namespace App\Shopify\Model\Price;

use App\Shopify\Model\IShopifyModel;

class PriceListUpdateInputs implements IShopifyModel
{
    /**
     * @param PriceListPriceInput[] $pricesListUpdateInputs
     */
    public function __construct(
        private array $pricesListUpdateInputs = [],
    ) {
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        $data = [];
        foreach ($this->getPricesListUpdateInputs() as $priceListUpdateInput) {
            $data[] = $priceListUpdateInput->getAsArray();
        }

        return $data;
    }

    /**
     * @param \App\Shopify\Model\Price\PriceListUpdateInput $priceListUpdateInput
     *
     * @return void
     */
    public function addPricesListUpdateInput(PriceListUpdateInput $priceListUpdateInput): void
    {
        $this->pricesListUpdateInputs[] = $priceListUpdateInput;
    }

    /**
     * @return \App\Shopify\Model\Price\PriceListPriceInput[]
     */
    public function getPricesListUpdateInputs(): array
    {
        return $this->pricesListUpdateInputs;
    }

    /**
     * @param array $pricesListUpdateInputs
     *
     * @return void
     */
    public function setPricesListUpdateInputs(array $pricesListUpdateInputs): void
    {
        $this->pricesListUpdateInputs = $pricesListUpdateInputs;
    }
}
