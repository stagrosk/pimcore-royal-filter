<?php

namespace App\Shopify\Model\Product;

use App\Shopify\Model\Base\ShopifyImageInput;

class AbstractShopifyProduct
{
    protected ?string $sku = null;
    protected ?float $price = null;

    /** @var ShopifyImageInput[] $media */
    protected array $media = [];
    protected array $metafields = [];

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }

    public function getMedia(): array
    {
        return $this->media;
    }

    public function addMedia(ShopifyImageInput $shopifyProductMedia): void
    {
        $this->media[] = $shopifyProductMedia;
    }

    /**
     * @param ShopifyImageInput[] $shopifyProductMedias
     */
    public function addMedias(array $shopifyProductMedias): void
    {
        $this->media = array_merge($this->media, $shopifyProductMedias);
    }

    public function addMetafield(string $key, ?string $value): void
    {
        $this->metafields[$key] = $value;
    }

    public function getMetafields(): array
    {
        return $this->metafields;
    }
}
