<?php

namespace App\Shopify\Graphql\Mutation\PriceList;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\PriceList\PriceListCreateInput;
use App\Shopify\Service\PriceList\ShopifyPriceListMapper;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\PriceList;
use Pimcore\Model\Notification\Service\NotificationService;
use Pimcore\Security\User\TokenStorageUserResolver;
use Psr\Log\LoggerInterface;

class PriceListUpdateMutation extends BaseMutation
{
    /**
     * @param \App\Shopify\Service\PriceList\ShopifyPriceListMapper $shopifyPriceListMapper
     * @param \App\Shopify\Graphql\GraphqlClient $client
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Pimcore\Model\Notification\Service\NotificationService $notificationService
     * @param \Pimcore\Security\User\TokenStorageUserResolver $tokenStorageUserResolver
     */
    public function __construct(
        private readonly ShopifyPriceListMapper $shopifyPriceListMapper,
        GraphQLClient                           $client,
        LoggerInterface                         $logger,
        NotificationService                     $notificationService,
        TokenStorageUserResolver                $tokenStorageUserResolver
    ) {
        parent::__construct($client, $logger, $notificationService, $tokenStorageUserResolver);
    }

    public function getMutation(): string
    {
        return <<<'GRAPHQL'
            mutation priceListUpdate($id: ID!, $input: PriceListUpdateInput!) {
              priceListUpdate(id: $id, input: $input) {
                userErrors {
                  message
                  field
                  code
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
        $shopifyPriceListInput = $this->shopifyPriceListMapper->getMappedObject(new PriceListCreateInput(), $object);

        return [
            'id' => $object->getApiId(),
            'input' => $shopifyPriceListInput->getAsArray(),
        ];
    }
}
