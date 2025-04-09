<?php

namespace App\Shopify\Model\Price;

use App\Shopify\Model\IShopifyModel;

class MoneyInput implements IShopifyModel
{
    /**
     * @param float $amount
     * @param string $currencyCode
     */
    public function __construct(
        private float $amount,
        private string $currencyCode
    ) {
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        return [
            'amount' => $this->getAmount(),
            'currencyCode' => $this->getCurrencyCode(),
        ];
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode(string $currencyCode): void
    {
        $this->currencyCode = $currencyCode;
    }
}
