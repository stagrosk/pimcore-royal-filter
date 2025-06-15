<?php

namespace App\Shopify\Graphql\Mutation\Translation;

use App\Pimcore\Model\DataObject\Collection;
use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\Translation\TranslationInputs;
use App\Shopify\Service\Translation\ShopifyTranslationMapper;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\Notification\Service\NotificationService;
use Pimcore\Security\User\TokenStorageUserResolver;
use Psr\Log\LoggerInterface;

class TranslationsRegisterMutation extends BaseMutation
{
    /**
     * @param \App\Shopify\Service\Translation\ShopifyTranslationMapper $productMapper
     * @param \App\Shopify\Graphql\GraphqlClient $client
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Pimcore\Model\Notification\Service\NotificationService $notificationService
     * @param \Pimcore\Security\User\TokenStorageUserResolver $tokenStorageUserResolver
     */
    public function __construct(
        private readonly ShopifyTranslationMapper $productMapper,
        GraphqlClient                             $client,
        LoggerInterface                           $logger,
        NotificationService                       $notificationService,
        TokenStorageUserResolver                  $tokenStorageUserResolver
    ) {
        parent::__construct($client, $logger, $notificationService, $tokenStorageUserResolver);
    }

    /**
     * @return string
     */
    public function getMutation(): string
    {
        return <<<'GRAPHQL'
            mutation translationsRegister($resourceId: ID!, $translations: [TranslationInput!]!) {
              translationsRegister(resourceId: $resourceId, translations: $translations) {
                userErrors {
                  message
                  field
                }
              }
            }
        GRAPHQL;
    }

    /**
     * @param \App\Pimcore\Model\DataObject\Collection|\Pimcore\Model\DataObject\Product|\Pimcore\Model\DataObject\AbstractObject|array $object
     *
     * @throws \Exception
     * @return array
     */
    public function getVariables(Collection|Product|AbstractObject|array $object): array
    {
        $translationInputs = $this->productMapper->getMappedObject(new TranslationInputs(), $object);

        return [
            'resourceId' => $object->getApiId(),
            'translations' => $translationInputs->getAsArray(),
        ];
    }
}
