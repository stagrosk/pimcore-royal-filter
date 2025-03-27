<?php

namespace App\Shopify\Graphql\Query;

interface ShopifyGraphqlQueryInterface
{
    /**
     * @return string
     */
    public function getQuery(): string;

    /**
     * @return array
     */
    public function getVariables(): array;
}
