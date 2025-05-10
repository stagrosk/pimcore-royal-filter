<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Pimcore\Helpers\VersionHelper;
use App\Shopify\Graphql\Mutation\Metafield\DeleteMetafieldsMutation;
use App\Shopify\Graphql\Mutation\Product\ProductCreateMutation;
use App\Shopify\Graphql\Mutation\Product\ProductDeleteMutation;
use App\Shopify\Graphql\Mutation\Product\ProductPublishMutation;
use App\Shopify\Graphql\Mutation\Product\ProductUpdateMutation;
use App\Shopify\Graphql\Mutation\Product\Variant\ProductVariantsBulkCreateMutation;
use App\Shopify\Graphql\Mutation\Product\Variant\ProductVariantsBulkUpdateMutation;
use App\Shopify\Service\Media\ShopifyMediaService;
use Exception;
use Pimcore\Event\DataObjectEvents;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\Service;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class ProductSubscriber implements EventSubscriberInterface
{
    /**
     * @param \App\Shopify\Graphql\Mutation\Product\ProductCreateMutation $productCreateMutation
     * @param \App\Shopify\Graphql\Mutation\Product\ProductUpdateMutation $productUpdateMutation
     * @param \App\Shopify\Graphql\Mutation\Product\ProductDeleteMutation $productDeleteMutation
     * @param \App\Shopify\Graphql\Mutation\Product\ProductPublishMutation $productPublishMutation
     * @param \App\Shopify\Graphql\Mutation\Product\Variant\ProductVariantsBulkCreateMutation $productVariantsBulkCreateMutation
     * @param \App\Shopify\Graphql\Mutation\Product\Variant\ProductVariantsBulkUpdateMutation $productVariantsBulkUpdateMutation
     * @param \App\Shopify\Graphql\Mutation\Metafield\DeleteMetafieldsMutation $deleteMetafieldsMutation
     */
    public function __construct(
        private ProductCreateMutation             $productCreateMutation,
        private ProductUpdateMutation             $productUpdateMutation,
        private ProductDeleteMutation             $productDeleteMutation,
        private ProductPublishMutation            $productPublishMutation,
        private ProductVariantsBulkCreateMutation $productVariantsBulkCreateMutation,
        private ProductVariantsBulkUpdateMutation $productVariantsBulkUpdateMutation,
        private DeleteMetafieldsMutation          $deleteMetafieldsMutation,
        private ShopifyMediaService               $shopifyMediaService
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            DataObjectEvents::POST_ADD => ['onPostAdd'],
            DataObjectEvents::PRE_UPDATE => ['onPreUpdate'],
            DataObjectEvents::PRE_DELETE => ['onPreDelete'],
        ];
    }

    /**
     * @param \Pimcore\Event\Model\DataObjectEvent $event
     *
     * @throws \PHPShopify\Exception\ApiException
     * @throws \PHPShopify\Exception\CurlException
     * @throws \Exception
     * @return void
     */
    public function onPostAdd(DataObjectEvent $event): void
    {
        /** @var \Pimcore\Model\DataObject\Product $object */
        $object = $event->getObject();

        // check an object type and is not variant
        if (!$object instanceof Product) {
            return;
        }

        // call product update with variant configuration
        if ($object->getType() === AbstractObject::OBJECT_TYPE_VARIANT) {
            // call resave to trigger update of variant
            VersionHelper::useVersioning(function () use ($object) {
                $object->save();
            }, false);
        } else {
            // handle product
            $response = $this->productCreateMutation->callAction($object);
            $data = $response['data']['productCreate'];
            if (!empty($data['userErrors'])) {
                throw new Exception($data['userErrors'][0]['message']);
            } else {
                $object->setApiId($data['product']['id']);
                $object->setHandle($data['product']['handle']);
            }

            // save product to persist data
            VersionHelper::useVersioning(function () use ($object) {
                $object->save();
            }, false);

            // publish
            $this->productPublishMutation->callAction($object);

            // process shopify media
            $this->shopifyMediaService->processMedia($object);

            // automatic creation of variant for product with first ID generated form shopify
            $variant = new Product();
            $variant->setApiId($data['product']['variants']['edges'][0]['node']['id']);
            $variant->setParent($object);
            $variant->setKey(Service::getValidKey(sprintf('V-%s', uniqid()), 'object'));
            $variant->setType(AbstractObject::OBJECT_TYPE_VARIANT);
            $variant->setPrices($object->getPrices());
            $variant->setPublished(true);

            VersionHelper::useVersioning(function () use ($variant) {
                $variant->save();
            }, false);
        }
    }

    /**
     * @param \Pimcore\Event\Model\DataObjectEvent $event
     *
     * @throws \PHPShopify\Exception\ApiException
     * @throws \PHPShopify\Exception\CurlException
     * @throws \Exception
     * @return void
     */
    public function onPreUpdate(DataObjectEvent $event): void
    {
        /** @var \Pimcore\Model\DataObject\Product $object */
        $object = $event->getObject();

        // check an object type
        if (!$object instanceof Product) {
            return;
        }

        $errors = [];

        // pimcore type is variant OR shopify type is variant
        if ($object->getType() === AbstractObject::OBJECT_TYPE_VARIANT || str_contains($object->getApiId(), 'ProductVariant')) {
            // update variant
            if (empty($object->getApiId())) {
                $response = $this->productVariantsBulkCreateMutation->callAction($object);  // send only variant
                $data = $response['data']['productVariantsBulkCreate'];
            } else {
                $response = $this->productVariantsBulkUpdateMutation->callAction($object);  // send only variant
                $data = $response['data']['productVariantsBulkUpdate'];
            }

            if (!empty($data['userErrors'])) {
                $errors[] = $data['userErrors'][0]['message'];
            } else {
                // set api id on new added product variant
                $object->setApiId($data['productVariants'][0]['id']);
            }
        } else {
            // product update
            $response = $this->productUpdateMutation->callAction($object);
            if (!empty($response['data']['productUpdate']['userErrors'])) {
                $errors[] = $response['data']['productUpdate']['userErrors'][0]['message'];
            }

            // check and delete metadata
            $response = $this->deleteMetafieldsMutation->callAction($object);
            if (!empty($response['data']['metafieldsDelete']['userErrors'])) {
                $errors[] = $response['data']['metafieldsDelete']['userErrors'][0]['message'];
            }

            // process shopify media
            $this->shopifyMediaService->processMedia($object);
        }

        if (!empty($errors)) {
            throw new Exception(implode("\n", $errors));
        }
    }

    /**
     * @param \Pimcore\Event\Model\DataObjectEvent $event
     *
     * @throws \PHPShopify\Exception\ApiException
     * @throws \PHPShopify\Exception\CurlException
     * @throws \Exception
     * @return void
     */
    public function onPreDelete(DataObjectEvent $event): void
    {
        /** @var \Pimcore\Model\DataObject\Product $object */
        $object = $event->getObject();

        // check an object type
        if (!$object instanceof Product
            || empty($object->getApiId())
            || $object->getType() === AbstractObject::OBJECT_TYPE_VARIANT   // pimcore type is variant
            || str_contains($object->getApiId(), 'ProductVariant')          // shopify type is variant
        ) {
            return;
        }

        $data = $this->productDeleteMutation->callAction($object);
        if (!empty($data['userErrors'])) {
            throw new Exception($data['userErrors'][0]['message']);
        }
    }
}
