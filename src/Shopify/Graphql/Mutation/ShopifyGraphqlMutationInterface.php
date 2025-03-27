<?php

namespace App\Shopify\Graphql\Mutation;

use Pimcore\Model\DataObject\Concrete;

interface ShopifyGraphqlMutationInterface
{
    /**
     * @return string
     */
    public function getMutation(): string;

    /**
     * @param \Pimcore\Model\DataObject\Concrete $object
     *
     * @return array
     */
    public function getVariables(Concrete $object): array;
}
