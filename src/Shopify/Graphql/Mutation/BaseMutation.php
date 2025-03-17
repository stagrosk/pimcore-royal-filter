<?php

namespace App\Shopify\Graphql\Mutation;

use App\Shopify\Graphql\GraphqlClient;
use Pimcore\Model\DataObject\Concrete;

abstract class BaseMutation implements ShopifyGraphqlMutationInterface
{
    /**
     * @param \App\Shopify\Graphql\GraphqlClient $client
     */
    public function __construct(
        private readonly GraphqlClient $client
    ) {
    }

    /**
     * @param \Pimcore\Model\DataObject\Concrete $object
     *
     * @throws \PHPShopify\Exception\ApiException
     * @throws \PHPShopify\Exception\CurlException
     * @return array
     */
    public function callAction(Concrete $object): array
    {
        $client = $this->client->getClient();

        return $client->GraphQL()->post($this->getMutation(), null, null, $this->getVariables($object));
    }
}
