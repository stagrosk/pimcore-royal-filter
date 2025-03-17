<?php

namespace App\Shopify\Model\Base;

use App\Shopify\Model\IShopifyModel;

class ShopifyImageInput implements IShopifyModel
{
    private string $src;
    private string $altText;

    public function __construct(string $src, string $altText)
    {
        $this->src = $src;
        $this->altText = $altText;
    }

    public function getAsArray(): array
    {
        return [
            'src' => $this->getSrc(),
            'altText' => $this->getAltText(),
        ];
    }

    public function getSrc(): string
    {
        return $this->src;
    }

    public function setSrc(string $src): void
    {
        $this->src = $src;
    }

    public function getAltText(): string
    {
        return $this->altText;
    }

    public function setAltText(string $altText): void
    {
        $this->altText = $altText;
    }
}
