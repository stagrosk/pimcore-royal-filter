<?php

namespace App\Shopify\Graphql\Query\Collection;

use App\Shopify\Graphql\Query\BaseQuery;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Product;

class CollectionMediaQuery extends BaseQuery
{
    /**
     * @return string
     */
    public function getQuery(): string {
        return <<<'GRAPHQL'
            query product($id: ID!) {
                product(id: $id) {
                    media(first: 10) {
                        edges {
                            node {
                                alt
                                mediaContentType
                                status
                                __typename
                                ... on MediaImage {
                                    id
                                    preview {
                                        image {
                                            originalSrc
                                        }
                                    }
                                    __typename
                                }
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
