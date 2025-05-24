<?php

namespace App\Shopify\Graphql\Mutation\PriceList;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\PriceList;
use Psr\Log\LoggerInterface;

class PriceListDeleteMutation extends BaseMutation
{
    /**
     * @param \App\Shopify\Graphql\GraphqlClient $client
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        GraphQLClient   $client,
        LoggerInterface $logger
    ) {
        parent::__construct($client, $logger);
    }

    public function getMutation(): string
    {
        return <<<'GRAPHQL'
            mutation priceListDelete($id: ID!) {
              priceListDelete(id: $id) {
                deletedId
                userErrors {
                  field
                  code
                  message
                }
              }
            }
        GRAPHQL;
    }

    /**
     * @param \Pimcore\Model\DataObject\PriceList|\Pimcore\Model\DataObject\AbstractObject|array $object
     *
     * @return array
     */
    public function getVariables(PriceList|AbstractObject|array $object): array
    {
        return [
            'id' => $object->getApiId(),
        ];
    }
}
