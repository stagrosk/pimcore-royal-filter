<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Shopify\Graphql\Mutation\PriceList\PriceListCreateMutation;
use App\Shopify\Graphql\Mutation\PriceList\PriceListDeleteMutation;
use App\Shopify\Graphql\Mutation\PriceList\PriceListUpdateMutation;
use Pimcore\Event\DataObjectEvents;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\PriceList;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class PriceListSubscriber implements EventSubscriberInterface
{
    /**
     * @param \App\Shopify\Graphql\Mutation\PriceList\PriceListCreateMutation $priceListCreateMutation
     * @param \App\Shopify\Graphql\Mutation\PriceList\PriceListUpdateMutation $priceListUpdateMutation
     * @param \App\Shopify\Graphql\Mutation\PriceList\PriceListDeleteMutation $priceListDeleteMutation
     */
    public function __construct(
        private PriceListCreateMutation $priceListCreateMutation,
        private PriceListUpdateMutation $priceListUpdateMutation,
        private PriceListDeleteMutation $priceListDeleteMutation,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
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
    public function onPreUpdate(DataObjectEvent $event): void
    {
        /** @var \Pimcore\Model\DataObject\PriceList $object */
        $object = $event->getObject();

        // check an object type
        if (!$object instanceof PriceList || !$object->isPublished()) {
            return;
        }

        if ($object->getApiId()) {
            $response = $this->priceListUpdateMutation->callAction($object);
            $data = $response['data']['priceListUpdate'];
        } else {
            $response = $this->priceListCreateMutation->callAction($object);
            $data = $response['data']['priceListCreate'];
            $object->setApiId($data['priceList']['id']);
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
        /** @var \Pimcore\Model\DataObject\PriceList $object */
        $object = $event->getObject();

        // check an object type
        if (!$object instanceof PriceList) {
            return;
        }

        $this->priceListDeleteMutation->callAction($object);
    }
}
