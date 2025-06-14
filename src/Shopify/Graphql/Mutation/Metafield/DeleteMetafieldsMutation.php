<?php

namespace App\Shopify\Graphql\Mutation\Metafield;

use App\Pimcore\Model\DataObject\Collection;
use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\Metafields\MetafieldIdentifierInput;
use App\Shopify\Model\Metafields\MetafieldIdentifierInputs;
use App\Shopify\Service\Metafields\ShopifyMetafieldService;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Product;
use Psr\Log\LoggerInterface;

class DeleteMetafieldsMutation extends BaseMutation
{
    /**
     * @param \App\Shopify\Service\Metafields\ShopifyMetafieldService $shopifyMetafieldService
     * @param \App\Shopify\Graphql\GraphqlClient $client
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        private readonly ShopifyMetafieldService $shopifyMetafieldService,
        GraphqlClient                            $client,
        LoggerInterface                          $logger
    ) {
        parent::__construct($client, $logger);
    }

    /**
     * @return string
     */
    public function getMutation(): string
    {
        return <<<'GRAPHQL'
            mutation MetafieldsDelete($metafields: [MetafieldIdentifierInput!]!) {
              metafieldsDelete(metafields: $metafields) {
                deletedMetafields {
                  key
                  namespace
                  ownerId
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
     * @param \Pimcore\Model\DataObject\AbstractObject|\Pimcore\Model\DataObject\Product|\App\Pimcore\Model\DataObject\Collection|array $object
     *
     * @throws \PHPShopify\Exception\ApiException
     * @throws \PHPShopify\Exception\CurlException
     * @return array
     */
    public function getVariables(AbstractObject|Product|Collection|array $object): array
    {
        $metafieldIdentifierInputs = new MetafieldIdentifierInputs();

        // get metafields to be deleted
        $metafieldsToBeDeleted = $this->shopifyMetafieldService->getMetafieldsToBeDeleted($object);
        foreach ($metafieldsToBeDeleted as $metafield) {
            $metafieldIdentifierInput = new MetafieldIdentifierInput(
                $metafield['key'],
                $metafield['namespace'],
                $object->getApiId()
            );

            $metafieldIdentifierInputs->addMetafieldIdentifierInput($metafieldIdentifierInput);
        }

        return [
            'metafields' => $metafieldIdentifierInputs->getAsArray(),
        ];
    }
}
