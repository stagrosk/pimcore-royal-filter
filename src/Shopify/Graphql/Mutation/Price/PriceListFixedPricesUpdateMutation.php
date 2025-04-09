<?php

namespace App\Shopify\Graphql\Mutation\Price;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\Price\PriceListUpdateInputs;
use App\Shopify\Service\Price\ShopifyPriceMapper;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Product;

class PriceListFixedPricesUpdateMutation extends BaseMutation
{
    /**
     * @param \App\Shopify\Graphql\GraphqlClient $client
     * @param \App\Shopify\Service\Price\ShopifyPriceMapper $priceMapper
     */
    public function __construct(
        GraphQLClient $client,
        private readonly ShopifyPriceMapper $priceMapper,
    ) {
        parent::__construct($client);
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
     * @param \Pimcore\Model\DataObject\Product|\Pimcore\Model\DataObject\AbstractObject $object
     *
     * @return array
     */
    public function getVariables(Product|AbstractObject $object): array
    {
        return [
            'multiCall' => true,
            'inputs' => $this->priceMapper->getMappedObject(new PriceListUpdateInputs(), $object)->getAsArray(),
        ];
    }
}
