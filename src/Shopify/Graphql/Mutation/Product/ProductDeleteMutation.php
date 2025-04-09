<?php

namespace App\Shopify\Graphql\Mutation\Product;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\Product\ProductDeleteInput;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Product;

class ProductDeleteMutation extends BaseMutation
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
            mutation productDelete($input: ProductDeleteInput!) {
                productDelete(input: $input) {
                    deletedProductId
                    userErrors {
                        field
                        message
                    }
                }
            }
        GRAPHQL;
    }

    /**
     * @param \Pimcore\Model\DataObject\Product|\Pimcore\Model\DataObject\AbstractObject $object
     *
     * @return array
     */
    public function getVariables(Product|AbstractObject $object): array
    {
        $shopifyProductDeleteInput = new ProductDeleteInput($object->getApiId());

        return [
            'input' => $shopifyProductDeleteInput->getAsArray()
        ];
    }
}
