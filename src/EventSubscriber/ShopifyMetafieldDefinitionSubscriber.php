<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Pimcore\Helpers\VersionHelper;
use App\Shopify\Graphql\Mutation\Metafield\MetafieldDefinitionCreateMutation;
use App\Shopify\Graphql\Mutation\Metafield\MetafieldDefinitionDeleteMutation;
use App\Shopify\Graphql\Mutation\Metafield\MetafieldDefinitionUpdateMutation;
use Exception;
use Pimcore\Event\DataObjectEvents;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\ShopifyMetafieldDefinition;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class ShopifyMetafieldDefinitionSubscriber implements EventSubscriberInterface
{
    /**
     * @param \App\Shopify\Graphql\Mutation\Metafield\MetafieldDefinitionCreateMutation $metafieldDefinitionCreateMutation
     * @param \App\Shopify\Graphql\Mutation\Metafield\MetafieldDefinitionUpdateMutation $metafieldDefinitionUpdateMutation
     * @param \App\Shopify\Graphql\Mutation\Metafield\MetafieldDefinitionDeleteMutation $metafieldDefinitionDeleteMutation
     */
    public function __construct(
        private MetafieldDefinitionCreateMutation $metafieldDefinitionCreateMutation,
        private MetafieldDefinitionUpdateMutation $metafieldDefinitionUpdateMutation,
        private MetafieldDefinitionDeleteMutation $metafieldDefinitionDeleteMutation,
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
        /** @var \Pimcore\Model\DataObject\ShopifyMetafieldDefinition $object */
        $object = $event->getObject();
        if (!$object instanceof ShopifyMetafieldDefinition || !$object->isPublished()) {
            return;
        }

        $this->callCreateMutation($object);

        // save product to persist data
        VersionHelper::useVersioning(function () use ($object) {
            $object->save();
        }, false);
    }

    /**
     * @param \Pimcore\Event\Model\DataObjectEvent $event
     *
     * @throws \Exception
     * @return void
     */
    public function onPreUpdate(DataObjectEvent $event): void
    {
        /** @var \Pimcore\Model\DataObject\ShopifyMetafieldDefinition $object */
        $object = $event->getObject();
        if (!$object instanceof ShopifyMetafieldDefinition || !$object->isPublished()) {
            return;
        }

        if (!$object->getApiId()) {
            $this->callCreateMutation($object);
        } else {
            $response = $this->metafieldDefinitionUpdateMutation->callAction($object);
            $data = $response['data']['metafieldDefinitionUpdate'];

            if (!empty($data['userErrors'])) {
                throw new Exception($data['userErrors'][0]['message']);
            }
        }
    }

    /**
     * @param \Pimcore\Event\Model\DataObjectEvent $event
     *
     * @throws \Exception
     * @return void
     */
    public function onPreDelete(DataObjectEvent $event): void
    {
        /** @var \Pimcore\Model\DataObject\ShopifyMetafieldDefinition $object */
        $object = $event->getObject();
        if (!$object instanceof ShopifyMetafieldDefinition || empty($object->getApiId())) {
            return;
        }

        $data = $this->metafieldDefinitionDeleteMutation->callAction($object);
        if (!empty($data['userErrors'])) {
            throw new Exception($data['userErrors'][0]['message']);
        }
    }

    /**
     * @param \Pimcore\Model\DataObject\ShopifyMetafieldDefinition $object
     *
     * @throws \PHPShopify\Exception\ApiException
     * @throws \PHPShopify\Exception\CurlException
     * @throws \Exception
     * @return void
     */
    private function callCreateMutation(ShopifyMetafieldDefinition $object): void
    {
        $response = $this->metafieldDefinitionCreateMutation->callAction($object);
        $data = $response['data']['metafieldDefinitionCreate'];

        if (!empty($data['userErrors'])) {
            throw new Exception($data['userErrors'][0]['message']);
        } else {
            $object->setApiId($data['createdDefinition']['id']);
        }
    }
}
