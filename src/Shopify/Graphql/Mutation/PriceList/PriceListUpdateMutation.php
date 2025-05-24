<?php

namespace App\Shopify\Graphql\Mutation\PriceList;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\PriceList\PriceListCreateInput;
use App\Shopify\Service\PriceList\ShopifyPriceListMapper;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\PriceList;
use Psr\Log\LoggerInterface;

class PriceListUpdateMutation extends BaseMutation
{
    /**
     * @param \App\Shopify\Graphql\GraphqlClient $client
     * @param \App\Shopify\Service\PriceList\ShopifyPriceListMapper $shopifyPriceListMapper
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        GraphQLClient                           $client,
        private readonly ShopifyPriceListMapper $shopifyPriceListMapper,
        LoggerInterface                         $logger
    ) {
        parent::__construct($client, $logger);
    }

    public function getMutation(): string
    {
        return <<<'GRAPHQL'
            mutation priceListUpdate($id: ID!, $input: PriceListUpdateInput!) {
              priceListUpdate(id: $id, input: $input) {
                userErrors {
                  message
                  field
                  code
                }
              }
            }
        GRAPHQL;
    }

    /**
     * @param \Pimcore\Model\DataObject\PriceList|\Pimcore\Model\DataObject\AbstractObject|array $object
     *
     * @return array
     */
    public function getVariables(PriceList|AbstractObject|array $object): array
    {
        $shopifyPriceListInput = $this->shopifyPriceListMapper->getMappedObject(new PriceListCreateInput(), $object);

        return [
            'id' => $object->getApiId(),
            'input' => $shopifyPriceListInput->getAsArray(),
        ];
    }
}
