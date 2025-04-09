<?php

namespace App\Shopify\Model\Price;

use App\Shopify\Model\IShopifyModel;

class PriceListUpdateInput implements IShopifyModel
{
    public string $priceListId;

    /** @var array<PriceListPriceInput> */
    public array $pricesToAdd;

    /** @var array<string> */
    public array $variantIdsToDelete;

    /**
     * @param string $priceListId
     * @param array $pricesToAdd
     * @param array $variantIdsToDelete
     */
    public function __construct(
        string $priceListId,
        array $pricesToAdd = [],
        array $variantIdsToDelete = []
    ) {
        $this->priceListId = $priceListId;
        $this->pricesToAdd = $pricesToAdd;
        $this->variantIdsToDelete = $variantIdsToDelete;
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        return [
            'priceListId' => $this->getPriceListId(),
            'pricesToAdd' => $this->getPricesToAdd(),
            'variantIdsToDelete' => $this->getVariantIdsToDelete(),
        ];
    }

    /**
     * @return string
     */
    public function getPriceListId(): string
    {
        return $this->priceListId;
    }

    /**
     * @param string $priceListId
     *
     * @return void
     */
    public function setPriceListId(string $priceListId): void
    {
        $this->priceListId = $priceListId;
    }

    /**
     * @return array
     */
    public function getPricesToAdd(): array
    {
        $pricesToAdd = [];
        foreach ($this->pricesToAdd as $price) {
            $pricesToAdd[] = $price->getAsArray();
        }

        return $pricesToAdd;
    }

    /**
     * @param array $pricesToAdd
     *
     * @return void
     */
    public function setPricesToAdd(array $pricesToAdd): void
    {
        $this->pricesToAdd = $pricesToAdd;
    }

    /**
     * @param \App\Shopify\Model\Price\PriceListPriceInput $priceListPriceInput
     *
     * @return void
     */
    public function addPriceToAdd(PriceListPriceInput $priceListPriceInput): void
    {
        $this->pricesToAdd[] = $priceListPriceInput;
    }

    /**
     * @return array|string[]
     */
    public function getVariantIdsToDelete(): array
    {
        return $this->variantIdsToDelete;
    }

    /**
     * @param array $variantIdsToDelete
     *
     * @return void
     */
    public function setVariantIdsToDelete(array $variantIdsToDelete): void
    {
        $this->variantIdsToDelete = $variantIdsToDelete;
    }
}
