<?php

namespace App\Shopify\Graphql\Query\Translation;

use App\Shopify\Graphql\Query\BaseQuery;
use Pimcore\Model\DataObject\AbstractObject;

class TranslatableResourceQuery extends BaseQuery
{
    /**
     * @return string
     */
    public function getQuery(): string {
        return <<<'GRAPHQL'
            query translatableResourcesByIds($resourceId: ID!) {
              translatableResourcesByIds(first: 1, resourceIds: [$resourceId]) {
                edges {
                  node {
                    resourceId
                    translatableContent {
                      key
                      value
                      digest
                      locale
                      type
                    }
                  }
                }
              }
            }
        GRAPHQL;
    }

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     *
     * @return array
     */
    public function getVariables(AbstractObject $object): array
    {
        return [
            'resourceId' => $object->getApiId(),
        ];
    }
}
