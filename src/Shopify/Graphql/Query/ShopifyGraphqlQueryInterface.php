<?php

namespace App\Shopify\Graphql\Query;

use Pimcore\Model\DataObject\AbstractObject;

interface ShopifyGraphqlQueryInterface
{
    /**
     * @return string
     */
    public function getQuery(): string;

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     *
     * @return array
     */
    public function getVariables(AbstractObject $object): array;

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     *
     * @return array
     */
    public function callAction(AbstractObject $object): array;
}
