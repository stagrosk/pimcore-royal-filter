<?php

namespace App\Shopify\Graphql\Query;

use App\Shopify\Graphql\GraphqlClient;
use Pimcore\Model\DataObject\AbstractObject;

abstract class BaseQuery implements ShopifyGraphqlQueryInterface
{
    /**
     * @param \App\Shopify\Graphql\GraphqlClient $client
     */
    public function __construct(
        private readonly GraphqlClient $client
    ) {
    }

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     *
     * @throws \PHPShopify\Exception\ApiException
     * @throws \PHPShopify\Exception\CurlException
     * @return array
     */
    public function callAction(AbstractObject $object): array
    {
        $client = $this->client->getClient();

        return $client->GraphQL()->post($this->getQuery(), null, null, $this->getVariables($object));
    }
}
