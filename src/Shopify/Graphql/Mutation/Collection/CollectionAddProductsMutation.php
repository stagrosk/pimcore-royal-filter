<?php

namespace App\Shopify\Graphql\Mutation\Collection;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\Collection\CollectionInput;
use App\Shopify\Service\Collection\ShopifyCollectionMapper;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Collection;
use Pimcore\Model\Notification\Service\NotificationService;
use Pimcore\Security\User\TokenStorageUserResolver;
use Psr\Log\LoggerInterface;

class CollectionAddProductsMutation extends BaseMutation
{
    /**
     * @param \App\Shopify\Service\Collection\ShopifyCollectionMapper $collectionMapper
     * @param \App\Shopify\Graphql\GraphqlClient $client
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Pimcore\Model\Notification\Service\NotificationService $notificationService
     * @param \Pimcore\Security\User\TokenStorageUserResolver $tokenStorageUserResolver
     */
    public function __construct(
        private readonly ShopifyCollectionMapper $collectionMapper,
        GraphqlClient                            $client,
        LoggerInterface                          $logger,
        NotificationService                      $notificationService,
        TokenStorageUserResolver                 $tokenStorageUserResolver
    ) {
        parent::__construct($client, $logger, $notificationService, $tokenStorageUserResolver);
    }

    /**
     * @return string
     */
    public function getMutation(): string
    {
        return <<<'GRAPHQL'
            mutation collectionAddProducts($id: ID!, $productIds: [ID!]!) {
              collectionAddProducts(id: $id, productIds: $productIds) {
                collection {
                  id
                  title
                  products(first: 10) {
                    nodes {
                      id
                      title
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
     * @param \Pimcore\Model\DataObject\Collection|\Pimcore\Model\DataObject\AbstractObject|array $object
     *
     * @return array
     */
    public function getVariables(Collection|AbstractObject|array $object): array
    {
        $shopifyCollectionModel = $this->collectionMapper->getMappedObject(new CollectionInput(), $object);

        return [
            'input' => $shopifyCollectionModel->getAsArray(),
        ];
    }
}
