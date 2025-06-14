<?php

namespace App\Shopify\Graphql\Mutation\Collection;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\Collection\CollectionDeleteInput;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Collection;
use Psr\Log\LoggerInterface;

class CollectionDeleteMutation extends BaseMutation
{
    /**
     * @return string
     */
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
     * @param \Pimcore\Model\DataObject\Collection|\Pimcore\Model\DataObject\AbstractObject|array $object
     *
     * @return array
     */
    public function getVariables(Collection|AbstractObject|array $object): array
    {
        $shopifyCollectionDeleteInput = new CollectionDeleteInput($object->getApiId());

        return [
            'input' => $shopifyCollectionDeleteInput->getAsArray(),
        ];
    }
}
