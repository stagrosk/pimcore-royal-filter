<?php

namespace App\Shopify\Graphql\Mutation\PriceList;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\PriceList\PriceListCreateInput;
use App\Shopify\Service\PriceList\ShopifyPriceListMapper;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\PriceList;
use Psr\Log\LoggerInterface;

class PriceListCreateMutation extends BaseMutation
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
            mutation PriceListCreate($input: PriceListCreateInput!) {
              priceListCreate(input: $input) {
                userErrors {
                  field
                  message
                }
                priceList {
                  id
                  name
                  currency
                  parent {
                    adjustment {
                      type
                      value
                    }
                  }
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
            'input' => $shopifyPriceListInput->getAsArray(),
        ];
    }
}
