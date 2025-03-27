<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Shopify\Graphql\Mutation\Collection\CollectionCreateMutation;
use App\Shopify\Graphql\Mutation\Collection\CollectionUpdateMutation;
use Pimcore\Event\DataObjectEvents;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\Category;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class CategorySubscriber implements EventSubscriberInterface
{
    /**
     * @param \App\Shopify\Graphql\Mutation\Collection\CollectionCreateMutation $collectionCreateMutation
     * @param \App\Shopify\Graphql\Mutation\Collection\CollectionUpdateMutation $collectionUpdateMutation
     */
    public function __construct(
        private CollectionCreateMutation $collectionCreateMutation,
        private CollectionUpdateMutation $collectionUpdateMutation,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            DataObjectEvents::PRE_UPDATE => ['onPreUpdate'],
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
        /** @var \Pimcore\Model\DataObject\Category $object */
        $object = $event->getObject();

        // check object type
        if (!$object instanceof Category) {
            return;
        }

        if ($object->getApiId()) {
            $response = $this->collectionUpdateMutation->callAction($object);
            $data = $response['data']['collectionUpdate'];

            if (!empty($data['userErrors'])) {
                throw new \Exception($response['userErrors']);
            }
        } else {
            $response = $this->collectionCreateMutation->callAction($object);
            $data = $response['data']['collectionCreate'];

            if (!empty($data['userErrors'])) {
                throw new \Exception($response['userErrors']);
            } else {
                $object->setApiId($data['collection']['id']);
                $object->setSlug($data['collection']['handle']);
            }
        }
    }
}
