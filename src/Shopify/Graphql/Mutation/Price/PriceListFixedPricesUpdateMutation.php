<?php

namespace App\Shopify\Graphql\Mutation\Price;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\Price\PriceListUpdateInputs;
use App\Shopify\Service\Price\ShopifyPriceMapper;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Product;
use Psr\Log\LoggerInterface;

class PriceListFixedPricesUpdateMutation extends BaseMutation
{
    /**
     * @param \App\Shopify\Graphql\GraphqlClient $client
     * @param \App\Shopify\Service\Price\ShopifyPriceMapper $priceMapper
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        GraphQLClient                       $client,
        private readonly ShopifyPriceMapper $priceMapper,
        LoggerInterface                     $logger
    ) {
        parent::__construct($client, $logger);
    }

    /**
     * @return string
     */
    public function getMutation(): string
    {
        return <<<'GRAPHQL'
            mutation priceListFixedPricesUpdate($priceListId: ID!, $pricesToAdd: [PriceListPriceInput!]!, $variantIdsToDelete: [ID!]!) {
                priceListFixedPricesUpdate(priceListId: $priceListId, pricesToAdd: $pricesToAdd, variantIdsToDelete: $variantIdsToDelete) {
                    userErrors {
                        field
                        message
                    }
                }
            }
        GRAPHQL;
    }

    /**
     * @param \Pimcore\Model\DataObject\Product|\Pimcore\Model\DataObject\AbstractObject|array $object
     *
     * @throws \Exception
     * @return array
     */
    public function getVariables(Product|AbstractObject|array $object): array
    {
        return [
            'multiCall' => true,
            'inputs' => $this->priceMapper->getMappedObject(new PriceListUpdateInputs(), $object)->getAsArray(),
        ];
    }
}
