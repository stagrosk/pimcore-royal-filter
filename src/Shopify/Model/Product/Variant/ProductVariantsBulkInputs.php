<?php

namespace App\Shopify\Model\Product\Variant;

use App\Shopify\Model\IShopifyModel;

class ProductVariantsBulkInputs implements IShopifyModel
{
    /**
     * @param ProductVariantsBulkInput[] $productVariantsBulkInput
     */
    public function __construct(
        private array $productVariantsBulkInput = [],
    ) {
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        $data = [];
        foreach ($this->getProductVariantsBulkInput() as $priceListUpdateInput) {
            $data[] = $priceListUpdateInput->getAsArray();
        }

        return $data;
    }

    /**
     * @param \App\Shopify\Model\Product\Variant\ProductVariantsBulkInput $productVariantsBulkInput
     *
     * @return void
     */
    public function addProductVariantsBulkInput(ProductVariantsBulkInput $productVariantsBulkInput): void
    {
        $this->productVariantsBulkInput[] = $productVariantsBulkInput;
    }

    /**
     * @return \App\Shopify\Model\Product\Variant\ProductVariantsBulkInput[]
     */
    public function getProductVariantsBulkInput(): array
    {
        return $this->productVariantsBulkInput;
    }

    /**
     * @param array $productVariantsBulkInput
     *
     * @return void
     */
    public function setProductVariantsBulkInput(array $productVariantsBulkInput): void
    {
        $this->productVariantsBulkInput = $productVariantsBulkInput;
    }
}
