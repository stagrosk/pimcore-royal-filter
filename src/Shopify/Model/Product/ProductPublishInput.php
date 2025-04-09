<?php

namespace App\Shopify\Model\Product;

use App\Shopify\Model\IShopifyModel;

class ProductPublishInput implements IShopifyModel
{
    /**
     * @param string $id The product to create or update publications for.
     * @param \App\Shopify\Model\Product\ProductPublicationInput $publicationInput
     */
    public function __construct(
        private string                  $id,
        private ProductPublicationInput $publicationInput,
    ) {
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return void
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return \App\Shopify\Model\Product\ProductPublicationInput
     */
    public function getPublicationInput(): ProductPublicationInput
    {
        return $this->publicationInput;
    }

    /**
     * @param \App\Shopify\Model\Product\ProductPublicationInput $publicationInput
     *
     * @return void
     */
    public function setPublicationInput(ProductPublicationInput $publicationInput): void
    {
        $this->publicationInput = $publicationInput;
    }

    /**
     * @return string[]
     */
    public function getAsArray(): array
    {
        return [
            'id' => $this->id,
            'productPublications' => $this->publicationInput->getAsArray(),
        ];
    }
}
