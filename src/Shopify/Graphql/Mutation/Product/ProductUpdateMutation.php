<?php

namespace App\Shopify\Graphql\Mutation\Product;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\Media\CreateMediaInputs;
use App\Shopify\Model\Product\ProductUpdateInput;
use App\Shopify\Service\Media\ShopifyMediaMapper;
use App\Shopify\Service\Media\ShopifyMediaService;
use App\Shopify\Service\Product\ShopifyProductMapper;
use Pimcore\Model\DataObject\AbstractObject;
use Psr\Log\LoggerInterface;

class ProductUpdateMutation extends BaseMutation
{
    /**
     * @param \App\Shopify\Graphql\GraphqlClient $client
     * @param \Psr\Log\LoggerInterface $logger
     * @param \App\Shopify\Service\Product\ShopifyProductMapper $productMapper
     * @param \App\Shopify\Service\Media\ShopifyMediaMapper $mediaMapper
     * @param \App\Shopify\Service\Media\ShopifyMediaService $shopifyMediaService
     */
    public function __construct(
        GraphQLClient                         $client,
        LoggerInterface                       $logger,
        private readonly ShopifyProductMapper $productMapper,
        private readonly ShopifyMediaMapper   $mediaMapper,
        private readonly ShopifyMediaService  $shopifyMediaService,
    ) {
        parent::__construct($client, $logger);
    }

    /**
     * @return string
     */
    public function getMutation(): string
    {
        return <<<'GRAPHQL'
            mutation UpdateProduct($product: ProductUpdateInput!, $media: [CreateMediaInput!]) {
              productUpdate(product: $product, media: $media) {
                product {
                  id
                  handle
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
     * @param \Pimcore\Model\DataObject\AbstractObject|array $object
     *
     * @throws \Exception
     * @return array
     */
    public function getVariables(AbstractObject|array $object): array
    {
        $productUpdateInput = $this->productMapper->getMappedObject(new ProductUpdateInput(), $object);

        // this is just an array of new added media files
        $newImages = $this->shopifyMediaService->getUnprocessedImages($object);
        $createMediaInput = $this->mediaMapper->getMappedImages(new CreateMediaInputs(), $newImages);

        return [
            'product' => $productUpdateInput->getAsArray(),
            'media' => $createMediaInput->getAsArray(),
        ];
    }
}
