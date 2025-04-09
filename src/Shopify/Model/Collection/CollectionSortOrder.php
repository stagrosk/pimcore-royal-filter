<?php

namespace App\Shopify\Model\Collection;

use InvalidArgumentException;

class CollectionSortOrder {
    public const SORTING_ALPHA_ASC    = 'ALPHA_ASC';
    public const SORTING_ALPHA_DESC   = 'ALPHA_DESC';
    public const SORTING_BEST_SELLING = 'BEST_SELLING';
    public const SORTING_CREATED      = 'CREATED';
    public const SORTING_CREATED_DESC = 'CREATED_DESC';
    public const SORTING_MANUAL       = 'MANUAL';
    public const SORTING_PRICE_ASC    = 'PRICE_ASC';
    public const SORTING_PRICE_DESC   = 'PRICE_DESC';

    public const AVAILABLE_SORTING = [
        self::SORTING_ALPHA_ASC,
        self::SORTING_ALPHA_DESC,
        self::SORTING_BEST_SELLING,
        self::SORTING_CREATED,
        self::SORTING_CREATED_DESC,
        self::SORTING_MANUAL,
        self::SORTING_PRICE_ASC,
        self::SORTING_PRICE_DESC,
    ];

    /**
     * @var string
     */
    private string $sorting = self::SORTING_CREATED;

    /**
     * @return string
     */
    public function getSorting(): string {
        return $this->sorting;
    }

    /**
     * @param string $sorting
     *
     * @return void
     * @throws InvalidArgumentException
     */
    public function setSorting(string $sorting): void {
        if (in_array($sorting, self::AVAILABLE_SORTING, true)) {
            $this->sorting = $sorting;
        } else {
            throw new InvalidArgumentException("Invalid CollectionSortOrder: " . $sorting);
        }
    }
}
