<?php

namespace App\Shopify\Graphql\Mutation\Product;

use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\Product\ProductPublicationInput;
use App\Shopify\Model\Product\ProductPublishInput;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Collection;

class ProductPublishMutation extends BaseMutation
{
    /**
     * @return string
     */
    public function getMutation(): string
    {
        return <<<'GRAPHQL'
            mutation productPublish($input: ProductPublishInput!) {
              productPublish(input: $input) {
                product {
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
        $shopifyProductPublicationInput = new ProductPublicationInput(self::PUBLICATIONS['store']['id']);
        $shopifyProductPublishInput = new ProductPublishInput($object->getApiId(), $shopifyProductPublicationInput);

        return [
            'input' => $shopifyProductPublishInput->getAsArray(),
        ];
    }
}
