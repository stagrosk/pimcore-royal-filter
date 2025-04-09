<?php

namespace App\Shopify\Graphql\Mutation;

use Pimcore\Model\DataObject\AbstractObject;

interface ShopifyGraphqlMutationInterface
{
    /**
     * @return string
     */
    public function getMutation(): string;

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     *
     * @return array
     */
    public function getVariables(AbstractObject $object): array;
}
