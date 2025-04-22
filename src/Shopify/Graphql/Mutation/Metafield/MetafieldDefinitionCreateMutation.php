<?php

namespace App\Shopify\Graphql\Mutation\Metafield;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\Price\PriceListUpdateInputs;
use App\Shopify\Service\Price\ShopifyPriceMapper;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Product;

class MetafieldDefinitionCreateMutation extends BaseMutation
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
//        mutation metafieldDefinitionCreate($definition: MetafieldDefinitionInput!) {
//            metafieldDefinitionCreate(definition: $definition) {
//            createdDefinition {
//                name
//                namespace
//                key
//                description
//                pinnedPosition
//                type {
//                    name
//                    category
//                    supportedValidations {
//                        name
//                        type
//                    }
//                    supportsDefinitionMigrations
//                }
//                access {
//                    admin
//                    customerAccount
//                    storefront
//                }
//            }
//        }

        return <<<'GRAPHQL'
            mutation metafieldDefinitionCreate($definition: MetafieldDefinitionInput!) {
                metafieldDefinitionCreate(definition: $definition) {
                    createdDefinition {
                        name
                        namespace
                        key
                        description
                        pinnedPosition
                    }
                }
            }
        GRAPHQL;
    }

    /**
     * @param \Pimcore\Model\DataObject\Product|\Pimcore\Model\DataObject\AbstractObject $object
     *
     * @throws \Exception
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
