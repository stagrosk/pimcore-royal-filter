<?php

namespace App\Shopify\Graphql\Mutation\Collection;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\Collection\CollectionPublicationInput;
use App\Shopify\Model\Collection\CollectionPublishInput;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Category;

class CollectionPublishMutation extends BaseMutation
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
            mutation collectionPublish($input: CollectionPublishInput!) {
              collectionPublish(input: $input) {
                collection {
                  id
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
     * @param \Pimcore\Model\DataObject\Category|\Pimcore\Model\DataObject\AbstractObject $object
     *
     * @return array
     */
    public function getVariables(Category|AbstractObject $object): array
    {
        $collectionPublicationInput = new CollectionPublicationInput(self::PUBLICATIONS['store']['id']);
        $collectionPublishInput = new CollectionPublishInput($object->getApiId(), $collectionPublicationInput);

        return [
            'input' => $collectionPublishInput->getAsArray()
        ];
    }
}
