<?php

namespace App\Shopify\Graphql\Mutation\Product\Variant;

use App\Shopify\Graphql\GraphqlClient;
use App\Shopify\Graphql\Mutation\BaseMutation;
use App\Shopify\Model\Product\Variant\ProductVariantsBulkInput;
use App\Shopify\Model\Product\Variant\ProductVariantsBulkInputs;
use App\Shopify\Service\Product\Variant\ShopifyVariantMapper;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\Notification\Service\NotificationService;
use Pimcore\Security\User\TokenStorageUserResolver;
use Psr\Log\LoggerInterface;

class ProductVariantsBulkCreateMutation extends BaseMutation
{
    /**
     * @param \App\Shopify\Service\Product\Variant\ShopifyVariantMapper $shopifyVariantMapper
     * @param \App\Shopify\Graphql\GraphqlClient $client
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Pimcore\Model\Notification\Service\NotificationService $notificationService
     * @param \Pimcore\Security\User\TokenStorageUserResolver $tokenStorageUserResolver
     */
    public function __construct(
        private readonly ShopifyVariantMapper $shopifyVariantMapper,
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
            mutation ProductVariantsCreate($productId: ID!, $variants: [ProductVariantsBulkInput!]!) {
              productVariantsBulkCreate(productId: $productId, variants: $variants) {
                productVariants {
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
     * @param \Pimcore\Model\DataObject\Product|\Pimcore\Model\DataObject\AbstractObject|array $object
     *
     * @throws \Exception
     * @return array
     */
    public function getVariables(Product|AbstractObject|array $object): array
    {
        $productVariantsBulkInputs = new ProductVariantsBulkInputs();
        if ($object->getType() === AbstractObject::OBJECT_TYPE_VARIANT) {
            $productId = $object->getParent()->getApiId();

            $productVariantsBulkInput = $this->shopifyVariantMapper->getMappedObject(new ProductVariantsBulkInput(), $object);
            $productVariantsBulkInputs->addProductVariantsBulkInput($productVariantsBulkInput);
        } else {
            $productId = $object->getApiId();

            foreach ($object->getChildren([AbstractObject::OBJECT_TYPE_VARIANT]) as $variant) {
                $productVariantsBulkInput = $this->shopifyVariantMapper->getMappedObject(new ProductVariantsBulkInput(), $variant);
                $productVariantsBulkInputs->addProductVariantsBulkInput($productVariantsBulkInput);
            }
        }

        return [
            'productId' => $productId,
            'variants' => $productVariantsBulkInputs->getAsArray(),
        ];
    }
}
