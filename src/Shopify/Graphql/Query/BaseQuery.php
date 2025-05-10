<?php

namespace App\Shopify\Graphql\Query;

use App\Shopify\Graphql\GraphqlClient;
use PHPShopify\Exception\ApiException;
use PHPShopify\Exception\CurlException;
use Pimcore\Model\DataObject\AbstractObject;
use Psr\Log\LoggerInterface;

abstract class BaseQuery implements ShopifyGraphqlQueryInterface
{
    /**
     * @param \App\Shopify\Graphql\GraphqlClient $client
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        private readonly GraphqlClient $client,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     *
     * @return array
     */
    public function callAction(AbstractObject $object): array
    {
        $result = [];

        try {
            $client = $this->client->getClient();
            $result = $client->GraphQL()->post($this->getQuery(), null, null, $this->getVariables($object));
        } catch (ApiException|CurlException $e) {
            $this->logger->error('[Query] Error: ' . $e->getMessage());
        }

        return $result;
    }
}
