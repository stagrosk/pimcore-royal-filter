<?php

namespace App\Shopify\Model\Price;

use App\Shopify\Model\IShopifyModel;

class VariantPriceInput implements IShopifyModel
{
    /**
     * @param \App\Shopify\Model\Price\MoneyInput|null $price
     * @param \App\Shopify\Model\Price\MoneyInput|null $compareAtPrice
     */
    public function __construct(
        private ?MoneyInput $price = null,
        private ?MoneyInput $compareAtPrice = null
    ) {
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        return [
            'price' => $this->getPrice()->getAmount(),
            'compareAtPrice' => $this->getCompareAtPrice()?->getAmount(),
        ];
    }

    /**
     * @return \App\Shopify\Model\Price\MoneyInput|null
     */
    public function getPrice(): ?MoneyInput
    {
        return $this->price;
    }

    /**
     * @param \App\Shopify\Model\Price\MoneyInput|null $price
     *
     * @return void
     */
    public function setPrice(?MoneyInput $price): void
    {
        $this->price = $price;
    }

    /**
     * @return \App\Shopify\Model\Price\MoneyInput|null
     */
    public function getCompareAtPrice(): ?MoneyInput
    {
        return $this->compareAtPrice;
    }

    /**
     * @param \App\Shopify\Model\Price\MoneyInput|null $compareAtPrice
     *
     * @return void
     */
    public function setCompareAtPrice(?MoneyInput $compareAtPrice): void
    {
        $this->compareAtPrice = $compareAtPrice;
    }
}
