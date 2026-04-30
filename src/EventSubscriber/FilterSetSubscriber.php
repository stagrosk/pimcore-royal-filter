<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Service\Generator\FilterToProductGenerator;
use OpenDxp\Event\DataObjectEvents;
use OpenDxp\Event\Model\DataObjectEvent;
use OpenDxp\Model\DataObject\FilterSet;
use OpenDxp\Model\DataObject\Product;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class FilterSetSubscriber implements EventSubscriberInterface
{
    /**
     * @param \App\Service\Generator\FilterToProductGenerator $generator
     */
    public function __construct(
        private FilterToProductGenerator $generator
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            DataObjectEvents::POST_UPDATE => ['onPostUpdate'],
        ];
    }

    /**
     * @param \OpenDxp\Event\Model\DataObjectEvent $event
     *
     * @throws \Exception
     * @return void
     */
    public function onPostUpdate(DataObjectEvent $event): void
    {
        /** @var \OpenDxp\Model\DataObject\FilterSet $object */
        $object = $event->getObject();

        // check object type
        if (!$object instanceof FilterSet) {
            return;
        }

        // skip when the save was triggered by ProductSubscriber to disable auto-regeneration after manual delete
        if (isset(\App\EventSubscriber\ProductSubscriber::$skipSourceUpdateForObjectIds[$object->getId()])) {
            return;
        }

        if ($object->isPublished() && $object->getGenerateAsProduct() === true) {
            $this->generator->generateProductForObject($object);
        }

        // if is not generated as product and product exists -> remove product
        if (!$object->getGenerateAsProduct() && $object->getProduct() instanceof Product) {
            $object->getProduct()->delete();
        }
    }
}
