<?php

namespace App\Shopify\Graphql\Query\Product;

use App\Shopify\Graphql\Query\BaseQuery;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Product;

class ProductMetafieldsQuery extends BaseQuery
{
    /**
     * @return string
     */
    public function getQuery(): string {
        return <<<'GRAPHQL'
            query product($id: ID!) {
                product(id: $id) {
                    title
                    metafields(first: 100) {
                        edges {
                            node {
                                id
                                key
                                definition {
                                    id
                                    key
                                    name
                                }
                                value
                            }
                        }
                    }
                }
            }
        GRAPHQL;
    }

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject|\Pimcore\Model\DataObject\Product $object
     *
     * @return array
     */
    public function getVariables(AbstractObject|Product $object): array
    {
        return [
            'id' => $object->getApiId(),
        ];
    }
}
