<?php

namespace App\Shopify\Graphql\Mutation\Collection;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\Collection\CollectionPublicationInput;
use App\Shopify\Model\Collection\CollectionPublishInput;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Collection;
use Psr\Log\LoggerInterface;

class CollectionPublishMutation extends BaseMutation
{
    /**
     * @return string
     */
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
     * @param \Pimcore\Model\DataObject\Collection|\Pimcore\Model\DataObject\AbstractObject|array $object
     *
     * @return array
     */
    public function getVariables(Collection|AbstractObject|array $object): array
    {
        $collectionPublicationInput = new CollectionPublicationInput(self::PUBLICATIONS['store']['id']);
        $collectionPublishInput = new CollectionPublishInput($object->getApiId(), $collectionPublicationInput);

        return [
            'input' => $collectionPublishInput->getAsArray(),
        ];
    }
}
