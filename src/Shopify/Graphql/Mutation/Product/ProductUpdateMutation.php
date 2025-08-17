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
use Pimcore\Model\Notification\Service\NotificationService;
use Pimcore\Security\User\TokenStorageUserResolver;
use Psr\Log\LoggerInterface;

class ProductUpdateMutation extends BaseMutation
{
    /**
     * @param \App\Shopify\Service\Product\ShopifyProductMapper $productMapper
     * @param \App\Shopify\Service\Media\ShopifyMediaMapper $mediaMapper
     * @param \App\Shopify\Service\Media\ShopifyMediaService $shopifyMediaService
     * @param \App\Shopify\Graphql\GraphqlClient $client
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Pimcore\Model\Notification\Service\NotificationService $notificationService
     * @param \Pimcore\Security\User\TokenStorageUserResolver $tokenStorageUserResolver
     */
    public function __construct(
        private readonly ShopifyProductMapper $productMapper,
        private readonly ShopifyMediaMapper   $mediaMapper,
        private readonly ShopifyMediaService  $shopifyMediaService,
        GraphQLClient                         $client,
        LoggerInterface                       $logger,
        NotificationService                   $notificationService,
        TokenStorageUserResolver              $tokenStorageUserResolver
    ) {
        parent::__construct($client, $logger, $notificationService, $tokenStorageUserResolver);
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
                  variants(first: 10) {
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
