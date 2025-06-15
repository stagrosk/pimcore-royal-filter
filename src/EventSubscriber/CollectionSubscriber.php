<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Shopify\Graphql\Mutation\Collection\CollectionCreateMutation;
use App\Shopify\Graphql\Mutation\Collection\CollectionDeleteMutation;
use App\Shopify\Graphql\Mutation\Collection\CollectionPublishMutation;
use App\Shopify\Graphql\Mutation\Collection\CollectionUpdateMutation;
use App\Shopify\Graphql\Mutation\Translation\TranslationsRegisterMutation;
use App\Shopify\Service\Media\ShopifyMediaService;
use Pimcore\Event\DataObjectEvents;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\Collection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class CollectionSubscriber implements EventSubscriberInterface
{
    /**
     * @param \App\Shopify\Graphql\Mutation\Collection\CollectionCreateMutation $collectionCreateMutation
     * @param \App\Shopify\Graphql\Mutation\Collection\CollectionUpdateMutation $collectionUpdateMutation
     * @param \App\Shopify\Graphql\Mutation\Collection\CollectionDeleteMutation $collectionDeleteMutation
     * @param \App\Shopify\Graphql\Mutation\Collection\CollectionPublishMutation $collectionPublishMutation
     * @param \App\Shopify\Graphql\Mutation\Translation\TranslationsRegisterMutation $translationsRegisterMutation
     */
    public function __construct(
        private CollectionCreateMutation     $collectionCreateMutation,
        private CollectionUpdateMutation     $collectionUpdateMutation,
        private CollectionDeleteMutation     $collectionDeleteMutation,
        private CollectionPublishMutation    $collectionPublishMutation,
        private TranslationsRegisterMutation $translationsRegisterMutation
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            DataObjectEvents::PRE_UPDATE => ['onPreUpdate'],
            DataObjectEvents::POST_UPDATE => ['onPostUpdate'],
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
    public function onPreUpdate(DataObjectEvent $event): void
    {
        /** @var \Pimcore\Model\DataObject\Collection $object */
        $object = $event->getObject();

        // check an object type
        if (!$object instanceof Collection || !$object->isPublished()) {
            return;
        }

        if (!$object->getApiId()) {
            $response = $this->collectionCreateMutation->callAction($object);
            $data = $response['data']['collectionCreate'];

            $object->setApiId($data['collection']['id']);
            $object->setHandle($data['collection']['handle']);
        }
    }

    /**
     * @param \Pimcore\Event\Model\DataObjectEvent $event
     *
     * @throws \Exception
     * @return void
     */
    public function onPostUpdate(DataObjectEvent $event): void
    {
        /** @var \Pimcore\Model\DataObject\Collection $object */
        $object = $event->getObject();

        // check an object type
        if (!$object instanceof Collection || !$object->isPublished() || !$object->getApiId()) {
            return;
        }

        // update
        $this->collectionUpdateMutation->callAction($object);

        // publish
        $this->collectionPublishMutation->callAction($object);

        // process translations
        $this->translationsRegisterMutation->callAction($object);
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
        /** @var \Pimcore\Model\DataObject\Collection $object */
        $object = $event->getObject();

        // check an object type
        if (!$object instanceof Collection) {
            return;
        }

        // check api id
        if (!$object->getApiId()) {
            return;
        }

        $response = $this->collectionDeleteMutation->callAction($object);
        $data = $response['data']['collectionDelete'];

        if (!empty($data['userErrors'])) {
            throw new \Exception($data['userErrors'][0]['message']);
        }
    }
}
