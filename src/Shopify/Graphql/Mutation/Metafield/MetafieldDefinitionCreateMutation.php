<?php

namespace App\Shopify\Graphql\Mutation\Metafield;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\Metafields\MetafieldDefinitionInput;
use App\Shopify\Service\Metafields\ShopifyShopifyMetafieldDefinitionMapper;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\ShopifyMetafieldDefinition;
use Psr\Log\LoggerInterface;

class MetafieldDefinitionCreateMutation extends BaseMutation
{
    /**
     * @param \App\Shopify\Graphql\GraphqlClient $client
     * @param \App\Shopify\Service\Metafields\ShopifyShopifyMetafieldDefinitionMapper $metafieldDefinitionMapper
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        GraphQLClient                                            $client,
        private readonly ShopifyShopifyMetafieldDefinitionMapper $metafieldDefinitionMapper,
        LoggerInterface                                          $logger
    ) {
        parent::__construct($client, $logger);
    }

    /**
     * @return string
     */
    public function getMutation(): string
    {
        return <<<'GRAPHQL'
            mutation metafieldDefinitionCreate($definition: MetafieldDefinitionInput!) {
                metafieldDefinitionCreate(definition: $definition) {
                    createdDefinition {
                        id
                        name
                        namespace
                        key
                        description
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
     * @param \Pimcore\Model\DataObject\ShopifyMetafieldDefinition|\Pimcore\Model\DataObject\AbstractObject|array $object
     *
     * @return array
     */
    public function getVariables(ShopifyMetafieldDefinition|AbstractObject|array $object): array
    {
        return [
            'definition' => $this->metafieldDefinitionMapper->getMappedObject(new MetafieldDefinitionInput(), $object)->getAsArray(),
        ];
    }
}
