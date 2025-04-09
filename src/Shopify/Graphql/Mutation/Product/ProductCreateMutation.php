<?php

namespace App\Shopify\Graphql\Mutation\Product;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\Media\CreateMediaInputs;
use App\Shopify\Model\Product\ProductCreateInput;
use App\Shopify\Service\Media\ShopifyMediaMapper;
use App\Shopify\Service\Product\ShopifyProductMapper;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Category;

class ProductCreateMutation extends BaseMutation
{
    /**
     * @param \App\Shopify\Graphql\GraphqlClient $client
     * @param \App\Shopify\Service\Product\ShopifyProductMapper $productMapper
     * @param \App\Shopify\Service\Media\ShopifyMediaMapper $mediaMapper
     */
    public function __construct(
        GraphQLClient                         $client,
        private readonly ShopifyProductMapper $productMapper,
        private readonly ShopifyMediaMapper   $mediaMapper
    ) {
        parent::__construct($client);
    }

    public function getMutation(): string
    {
        return <<<'GRAPHQL'
            mutation productCreate($product: ProductCreateInput!, $media: [CreateMediaInput!]) {
                productCreate(product: $product, media: $media) {
                    product {
                        id
                        title
                        handle
                        metafields(first: 10) {
                            nodes {
                                id
                                namespace
                                key
                                value
                            }
                        }
                        media(first: 10) {
                            nodes {
                                alt
                                mediaContentType
                                preview {
                                    status
                                }
                            }
                        }
                        variants(first: 1) {
                            edges {
                                node {
                                    id
                                }
                            }
                        }
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
        $productCreateInput = $this->productMapper->getMappedObject(new ProductCreateInput(), $object);
        $createMediaInputs = $this->mediaMapper->getMappedObject(new CreateMediaInputs(), $object);

        return [
            'product' => $productCreateInput->getAsArray(),
            'media' => $createMediaInputs->getAsArray(),
        ];
    }
}

