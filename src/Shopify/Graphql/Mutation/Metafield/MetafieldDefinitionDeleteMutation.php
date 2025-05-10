<?php

namespace App\Shopify\Graphql\Mutation\Metafield;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\ShopifyMetafieldDefinition;
use Psr\Log\LoggerInterface;

class MetafieldDefinitionDeleteMutation extends BaseMutation
{
    /**
     * @return string
     */
    public function getMutation(): string
    {
        return <<<'GRAPHQL'
            mutation DeleteMetafieldDefinition($id: ID!, $deleteAllAssociatedMetafields: Boolean!) {
              metafieldDefinitionDelete(id: $id, deleteAllAssociatedMetafields: $deleteAllAssociatedMetafields) {
                deletedDefinitionId
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
            'id' => $object->getApiId(),
            'deleteAllAssociatedMetafields' => true,
        ];
    }
}
