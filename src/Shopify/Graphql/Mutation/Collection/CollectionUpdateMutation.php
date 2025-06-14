<?php

namespace App\Shopify\Graphql\Mutation\Collection;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\Collection\CollectionInput;
use App\Shopify\Service\Collection\ShopifyCollectionMapper;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Collection;
use Psr\Log\LoggerInterface;

class CollectionUpdateMutation extends BaseMutation
{
    /**
     * @param \App\Shopify\Graphql\GraphqlClient $client
     * @param \App\Shopify\Service\Collection\ShopifyCollectionMapper $collectionMapper
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        GraphQLClient                            $client,
        private readonly ShopifyCollectionMapper $collectionMapper,
        LoggerInterface                          $logger
    ) {
        parent::__construct($client, $logger);
    }

    /**
     * @return string
     */
    public function getMutation(): string
    {
        return <<<'GRAPHQL'
            mutation CollectionUpdate($input: CollectionInput!) {
                collectionUpdate(input: $input) {
                    collection {
                        id
                        title
                        description
                        handle
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
     * @param \Pimcore\Model\DataObject\Collection|\Pimcore\Model\DataObject\AbstractObject|array $object
     *
     * @return array
     */
    public function getVariables(Collection|AbstractObject|array $object): array
    {
        $shopifyCollectionModel = $this->collectionMapper->getMappedObject(new CollectionInput(), $object);

        return [
            'input' => $shopifyCollectionModel->getAsArray(),
        ];
    }
}
