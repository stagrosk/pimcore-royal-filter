<?php

namespace App\Shopify\Graphql\Mutation\Product;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\Media\CreateMediaInputs;
use App\Shopify\Model\Product\ProductCreateInput;
use App\Shopify\Service\Media\ShopifyMediaMapper;
use App\Shopify\Service\Product\ShopifyProductMapper;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Collection;
use Pimcore\Model\Notification\Service\NotificationService;
use Pimcore\Security\User\TokenStorageUserResolver;
use Psr\Log\LoggerInterface;

class ProductCreateMutation extends BaseMutation
{
    /**
     * @param \App\Shopify\Service\Product\ShopifyProductMapper $productMapper
     * @param \App\Shopify\Service\Media\ShopifyMediaMapper $mediaMapper
     * @param \App\Shopify\Graphql\GraphqlClient $client
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Pimcore\Model\Notification\Service\NotificationService $notificationService
     * @param \Pimcore\Security\User\TokenStorageUserResolver $tokenStorageUserResolver
     */
    public function __construct(
        private readonly ShopifyProductMapper $productMapper,
        private readonly ShopifyMediaMapper   $mediaMapper,
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
            mutation productCreate($product: ProductCreateInput!, $media: [CreateMediaInput!]) {
                productCreate(product: $product, media: $media) {
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
     * @throws \Exception
     * @return array
     */
    public function getVariables(Collection|AbstractObject|array $object): array
    {
        $productCreateInput = $this->productMapper->getMappedObject(new ProductCreateInput(), $object);
        $createMediaInputs = $this->mediaMapper->getMappedObject(new CreateMediaInputs(), $object);

        return [
            'product' => $productCreateInput->getAsArray(),
            'media' => $createMediaInputs->getAsArray(),
        ];
    }
}

