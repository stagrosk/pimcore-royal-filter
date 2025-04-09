<?php

namespace App\Shopify\Model\Product;

use App\Shopify\Model\IShopifyModel;

class ProductDeleteInput implements IShopifyModel
{
    /**
     * @param string $id
     */
    public function __construct(
        private string $id
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
     * @return string[]
     */
    public function getAsArray(): array
    {
        return [
            'id' => $this->id,
        ];
    }
}
