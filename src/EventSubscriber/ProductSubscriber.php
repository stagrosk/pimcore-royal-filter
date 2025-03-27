<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Pimcore\Event\DataObjectEvents;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\Category;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class ProductSubscriber implements EventSubscriberInterface
{
    /**
     * @param \App\EventSubscriber\ProductCreateMutation $productCreateMutation
     * @param \App\EventSubscriber\ProductUpdateMutation $productUpdateMutation
     */
    public function __construct(
        private ProductCreateMutation $productCreateMutation,
        private ProductUpdateMutation $productUpdateMutation,
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
            $response = $this->productUpdateMutation->callAction($object);
            $data = $response['data']['productUpdate'];

            if (!empty($data['userErrors'])) {
                throw new \Exception($response['userErrors']);
            }
        } else {
            $response = $this->productCreateMutation->callAction($object);
            $data = $response['data']['productCreate'];

            if (!empty($data['userErrors'])) {
                throw new \Exception($response['userErrors']);
            } else {
                $object->setApiId($data['product']['id']);
                $object->setUrlHandle($data['product']['handle']);
            }
        }
    }
}
