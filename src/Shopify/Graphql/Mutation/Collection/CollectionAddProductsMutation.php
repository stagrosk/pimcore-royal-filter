<?php

namespace App\Shopify\Graphql\Mutation\Collection;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\Collection\ShopifyCollectionInput;
use App\Shopify\Service\Collection\ShopifyCollectionMapper;
use Pimcore\Model\DataObject\Category;
use Pimcore\Model\DataObject\Concrete;

class CollectionAddProductsMutation extends BaseMutation
{
    /**
     * @param \App\Shopify\Graphql\GraphqlClient $client
     * @param \App\Shopify\Service\Collection\ShopifyCollectionMapper $collectionMapper
     */
    public function __construct(
        GraphqlClient $client,
        private readonly ShopifyCollectionMapper $collectionMapper,
    ) {
        parent::__construct($client);
    }

    public function getMutation(): string
    {
        return <<<'GRAPHQL'
            mutation collectionAddProducts($id: ID!, $productIds: [ID!]!) {
              collectionAddProducts(id: $id, productIds: $productIds) {
                collection {
                  id
                  title
                  products(first: 10) {
                    nodes {
                      id
                      title
                    }
                  }
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
        $shopifyCollectionModel = $this->collectionMapper->getMappedCollection(new ShopifyCollectionInput(), $object);

        return [
            'input' => $shopifyCollectionModel->getAsArray()
        ];
    }
}
