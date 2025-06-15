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

class CollectionCreateMutation extends BaseMutation
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
        GraphQLClient                            $client,
        LoggerInterface                          $logger,
        NotificationService                      $notificationService,
        TokenStorageUserResolver                 $tokenStorageUserResolver
    ) {
        parent::__construct($client, $logger, $notificationService, $tokenStorageUserResolver);
    }

    public function getMutation(): string
    {
        return <<<'GRAPHQL'
            mutation CollectionCreate($input: CollectionInput!) {
              collectionCreate(input: $input) {
                collection {
                  id
                  title
                  descriptionHtml
                  updatedAt
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
     * @param \Pimcore\Model\DataObject\Collection|\Pimcore\Model\DataObject\AbstractObject|array $object
     *
     * @throws \Exception
     * @return array
     */
    public function getVariables(Collection|AbstractObject|array $object): array
    {
        $shopifyCollectionInput = $this->collectionMapper->getMappedObject(new CollectionInput(), $object);

        return [
            'input' => $shopifyCollectionInput->getAsArray(),
        ];
    }
}

