<?php

namespace App\Shopify\Graphql\Mutation\Price;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\Price\PriceListUpdateInputs;
use App\Shopify\Service\Price\ShopifyPriceMapper;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\Notification\Service\NotificationService;
use Pimcore\Security\User\TokenStorageUserResolver;
use Psr\Log\LoggerInterface;

class PriceListFixedPricesUpdateMutation extends BaseMutation
{
    /**
     * @param \App\Shopify\Service\Price\ShopifyPriceMapper $priceMapper
     * @param \App\Shopify\Graphql\GraphqlClient $client
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Pimcore\Model\Notification\Service\NotificationService $notificationService
     * @param \Pimcore\Security\User\TokenStorageUserResolver $tokenStorageUserResolver
     */
    public function __construct(
        private readonly ShopifyPriceMapper $priceMapper,
        GraphQLClient                       $client,
        LoggerInterface                     $logger,
        NotificationService                 $notificationService,
        TokenStorageUserResolver            $tokenStorageUserResolver
    ) {
        parent::__construct($client, $logger, $notificationService, $tokenStorageUserResolver);
    }

    /**
     * @return string
     */
    public function getMutation(): string
    {
        return <<<'GRAPHQL'
            mutation priceListFixedPricesUpdate($priceListId: ID!, $pricesToAdd: [PriceListPriceInput!]!, $variantIdsToDelete: [ID!]!) {
                priceListFixedPricesUpdate(priceListId: $priceListId, pricesToAdd: $pricesToAdd, variantIdsToDelete: $variantIdsToDelete) {
                    userErrors {
                        field
                        message
                    }
                }
            }
        GRAPHQL;
    }

    /**
     * @param \Pimcore\Model\DataObject\Product|\Pimcore\Model\DataObject\AbstractObject|array $object
     *
     * @throws \Exception
     * @return array
     */
    public function getVariables(Product|AbstractObject|array $object): array
    {
        return [
            'multiCall' => true,
            'inputs' => $this->priceMapper->getMappedObject(new PriceListUpdateInputs(), $object)->getAsArray(),
        ];
    }
}
