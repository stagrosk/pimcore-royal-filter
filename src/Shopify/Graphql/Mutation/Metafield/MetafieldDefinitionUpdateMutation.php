<?php

namespace App\Shopify\Graphql\Mutation\Metafield;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\Metafields\MetafieldDefinitionInput;
use App\Shopify\Service\Metafields\ShopifyShopifyMetafieldDefinitionMapper;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\ShopifyMetafieldDefinition;
use Pimcore\Model\Notification\Service\NotificationService;
use Pimcore\Security\User\TokenStorageUserResolver;
use Psr\Log\LoggerInterface;

class MetafieldDefinitionUpdateMutation extends BaseMutation
{
    /**
     * @param \App\Shopify\Service\Metafields\ShopifyShopifyMetafieldDefinitionMapper $metafieldDefinitionMapper
     * @param \App\Shopify\Graphql\GraphqlClient $client
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Pimcore\Model\Notification\Service\NotificationService $notificationService
     * @param \Pimcore\Security\User\TokenStorageUserResolver $tokenStorageUserResolver
     */
    public function __construct(
        private readonly ShopifyShopifyMetafieldDefinitionMapper $metafieldDefinitionMapper,
        GraphQLClient                                            $client,
        LoggerInterface                                          $logger,
        NotificationService                                      $notificationService,
        TokenStorageUserResolver                                 $tokenStorageUserResolver
    ) {
        parent::__construct($client, $logger, $notificationService, $tokenStorageUserResolver);
    }

    /**
     * @return string
     */
    public function getMutation(): string
    {
        return <<<'GRAPHQL'
            mutation metafieldDefinitionUpdate($definition: MetafieldDefinitionUpdateInput!) {
                metafieldDefinitionUpdate(definition: $definition) {
                    updatedDefinition {
                        id
                        name
                    }
                    userErrors {
                      field
                      message
                      code
                    }
                }
            }
        GRAPHQL;
    }

    /**
     * @param \Pimcore\Model\DataObject\ShopifyMetafieldDefinition|\Pimcore\Model\DataObject\AbstractObject|array $object
     *
     * @return array
     */
    public function getVariables(ShopifyMetafieldDefinition|AbstractObject|array $object): array
    {
        return [
            'definition' => $this->metafieldDefinitionMapper->getMappedObject(new MetafieldDefinitionInput(), $object)->getAsArray(true),
        ];
    }
}
