<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Service\Generator\BaseProductGenerator;
use App\Service\Generator\FilterToProductGenerator;
use Pimcore\Event\DataObjectEvents;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\RoyalFilter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class RoyalFilterSubscriber implements EventSubscriberInterface
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
            DataObjectEvents::PRE_UPDATE => ['onPreUpdate'],
        ];
    }

    /**
     * @param \Pimcore\Event\Model\DataObjectEvent $event
     *
     * @throws \Exception
     * @return void
     */
    public function onPreUpdate(DataObjectEvent $event): void
    {
        /** @var \Pimcore\Model\DataObject\RoyalFilter $object */
        $object = $event->getObject();

        // check object type
        if (!$object instanceof RoyalFilter) {
            return;
        }

        if ($object->isPublished() && $object->getGenerateAsProduct() === true) {
            $this->generator->generateProductForObject($object);
        }
    }
}
