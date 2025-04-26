<?php

namespace App\Shopify\Graphql\Mutation\Metafield;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\Metafields\MetafieldDefinitionInput;
use App\Shopify\Service\Metafields\ShopifyShopifyMetafieldDefinitionMapper;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\ShopifyMetafieldDefinition;

class MetafieldDefinitionUpdateMutation extends BaseMutation
{
    /**
     * @param \App\Shopify\Graphql\GraphqlClient $client
     * @param \App\Shopify\Service\Metafields\ShopifyShopifyMetafieldDefinitionMapper $metafieldDefinitionMapper
     */
    public function __construct(
        GraphQLClient                                            $client,
        private readonly ShopifyShopifyMetafieldDefinitionMapper $metafieldDefinitionMapper,
    ) {
        parent::__construct($client);
    }

    /**
     * @return string
     */
    public function getMutation(): string
    {
        return <<<'GRAPHQL'
            mutation metafieldDefinitionUpdate($definition: MetafieldDefinitionUpdateInput!) {
                metafieldDefinitionUpdate(definition: $definition) {
                    updatedDefinition {
                        id
                        name
                    }
                    userErrors {
                      field
                      message
                      code
                    }
                }
            }
        GRAPHQL;
    }

    /**
     * @param \Pimcore\Model\DataObject\ShopifyMetafieldDefinition|\Pimcore\Model\DataObject\AbstractObject $object
     *
     * @return array
     */
    public function getVariables(ShopifyMetafieldDefinition|AbstractObject $object): array
    {
        return [
            'definition' => $this->metafieldDefinitionMapper->getMappedObject(new MetafieldDefinitionInput(), $object)->getAsArray(true),
        ];
    }
}
