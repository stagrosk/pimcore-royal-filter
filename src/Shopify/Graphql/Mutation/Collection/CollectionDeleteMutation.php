<?php

namespace App\Shopify\Graphql\Mutation\Collection;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\Collection\ShopifyCollectionDeleteInput;
use Pimcore\Model\DataObject\Category;
use Pimcore\Model\DataObject\Concrete;

class CollectionDeleteMutation extends BaseMutation
{
    /**
     * @param \App\Shopify\Graphql\GraphqlClient $client
     */
    public function __construct(
        GraphQLClient $client,
    ) {
        parent::__construct($client);
    }

    public function getMutation(): string
    {
        return <<<'GRAPHQL'
            mutation collectionDelete($input: CollectionDeleteInput!) {
                collectionDelete(input: $input) {
                    deletedCollectionId
                    shop {
                        id
                        name
                    }
                    userErrors {
                        field
                        message
                    }
                }
            }
        GRAPHQL;
    }

    /**
     * @param \Pimcore\Model\DataObject\Category|\Pimcore\Model\DataObject\Concrete $object
     *
     * @return array
     */
    public function getVariables(Category|Concrete $object): array
    {
        $shopifyCollectionDeleteInput = new ShopifyCollectionDeleteInput($object->getApiId());

        return [
            'input' => $shopifyCollectionDeleteInput->getAsArray()
        ];
    }
}
