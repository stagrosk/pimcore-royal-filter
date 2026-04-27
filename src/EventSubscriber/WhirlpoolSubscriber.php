<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Service\Generator\BaseProductGenerator;
use App\Service\Generator\WhirlpoolToProductGenerator;
use OpenDxp\Event\DataObjectEvents;
use OpenDxp\Event\Model\DataObjectEvent;
use OpenDxp\Model\DataObject\Product;
use OpenDxp\Model\DataObject\Whirlpool;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

readonly class WhirlpoolSubscriber implements EventSubscriberInterface
{
    /**
     * @param \App\Service\Generator\WhirlpoolToProductGenerator $generator
     */
    public function __construct(
        private WhirlpoolToProductGenerator $generator
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
        /** @var \OpenDxp\Model\DataObject\Whirlpool $object */
        $object = $event->getObject();

        // check object type
        if (!$object instanceof Whirlpool) {
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
