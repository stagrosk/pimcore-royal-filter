<?php

namespace App\Shopify\Model\Price;

use App\Shopify\Model\IShopifyModel;

class PriceListPriceInput implements IShopifyModel
{
    /**
     * @param string $variantId
     * @param \App\Shopify\Model\Price\MoneyInput $price
     * @param \App\Shopify\Model\Price\MoneyInput|null $compareAtPrice
     */
    public function __construct(
        private string      $variantId,
        private MoneyInput  $price,
        private ?MoneyInput $compareAtPrice = null
    ) {
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        return [
            'variantId' => $this->getVariantId(),
            'price' => $this->getPrice()->getAsArray(),
            'compareAtPrice' => $this->getCompareAtPrice()?->getAsArray(),
        ];
    }

    /**
     * @return string
     */
    public function getVariantId(): string
    {
        return $this->variantId;
    }

    /**
     * @param string $variantId
     *
     * @return void
     */
    public function setVariantId(string $variantId): void
    {
        $this->variantId = $variantId;
    }

    /**
     * @return \App\Shopify\Model\Price\MoneyInput
     */
    public function getPrice(): MoneyInput
    {
        return $this->price;
    }

    /**
     * @param \App\Shopify\Model\Price\MoneyInput $price
     *
     * @return void
     */
    public function setPrice(MoneyInput $price): void
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
