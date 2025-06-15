<?php

namespace App\Shopify\Graphql\Mutation\PriceList;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\PriceList;
use Pimcore\Model\Notification\Service\NotificationService;
use Pimcore\Security\User\TokenStorageUserResolver;
use Psr\Log\LoggerInterface;

class PriceListDeleteMutation extends BaseMutation
{
    /**
     * @param \App\Shopify\Graphql\GraphqlClient $client
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Pimcore\Model\Notification\Service\NotificationService $notificationService
     * @param \Pimcore\Security\User\TokenStorageUserResolver $tokenStorageUserResolver
     */
    public function __construct(
        GraphQLClient            $client,
        LoggerInterface          $logger,
        NotificationService      $notificationService,
        TokenStorageUserResolver $tokenStorageUserResolver
    ) {
        parent::__construct($client, $logger, $notificationService, $tokenStorageUserResolver);
    }

    public function getMutation(): string
    {
        return <<<'GRAPHQL'
            mutation priceListDelete($id: ID!) {
              priceListDelete(id: $id) {
                deletedId
                userErrors {
                  field
                  code
                  message
                }
              }
            }
        GRAPHQL;
    }

    /**
     * @param \Pimcore\Model\DataObject\PriceList|\Pimcore\Model\DataObject\AbstractObject|array $object
     *
     * @return array
     */
    public function getVariables(PriceList|AbstractObject|array $object): array
    {
        return [
            'id' => $object->getApiId(),
        ];
    }
}
