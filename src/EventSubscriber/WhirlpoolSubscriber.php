<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Service\Generator\BaseProductGenerator;
use App\Service\Generator\WhirlpoolToProductGenerator;
use Pimcore\Event\DataObjectEvents;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\Whirlpool;
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
        /** @var \Pimcore\Model\DataObject\Whirlpool $object */
        $object = $event->getObject();

        // check object type
        if (!$object instanceof Whirlpool) {
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
