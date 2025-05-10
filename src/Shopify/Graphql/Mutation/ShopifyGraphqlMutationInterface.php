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
     * @param \Pimcore\Model\DataObject\AbstractObject|array $object
     *
     * @return array
     */
    public function getVariables(AbstractObject|array $object): array;

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject|array $object
     *
     * @return array
     */
    public function callAction(AbstractObject|array $object): array;
}
