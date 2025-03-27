<?php

namespace App\Shopify\Model\Collection;

class ShopifyCollectionSortOrder
{
    public const SORTING_ALPHA_ASC    = 'ALPHA_ASC';       // Alphabetically, in ascending order (A - Z).
    public const SORTING_ALPHA_DESC   = 'ALPHA_DESC';     // Alphabetically, in descending order (Z - A).
    public const SORTING_BEST_SELLING = 'BEST_SELLING';    // By best-selling products.
    public const SORTING_CREATED      = 'CREATED';         // By date created, in ascending order (oldest - newest).
    public const SORTING_CREATED_DESC = 'CREATED_DESC';    // By date created, in descending order (newest - oldest).
    public const SORTING_MANUAL       = 'MANUAL';          // In the order set manually by the merchant.
    public const SORTING_PRICE_ASC    = 'PRICE_ASC';       // By price, in ascending order (lowest - highest).
    public const SORTING_PRICE_DESC   = 'PRICE_DESC';      // By price, in descending order (highest - lowest).

    private string $sorting = self::SORTING_CREATED;

    /**
     * @return string
     */
    public function getSorting(): string
    {
        return $this->sorting;
    }

    public function setSorting(string $sorting): void
    {
        $this->sorting = $sorting;
    }
}
