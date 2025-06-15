<?php

namespace App\Shopify\Model\Media;

use App\Shopify\Model\IShopifyModel;

class ImageInput implements IShopifyModel
{
    /**
     * @param string $src
     * @param string $altText
     */
    public function __construct(
        private string $src,
        private string $altText,
    ) {
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        return [
            'src' => $this->src,
            'altText' => $this->altText,
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
