<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Logger;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\PriceList;

class PriceListSubscriber extends AbstractWebhookSubscriber
{
    protected function getObjectClass(): string
    {
        return PriceList::class;
    }

    protected function getLogPrefix(): string
    {
        return 'PriceListSubscriber';
    }

    public function onPostUpdate(DataObjectEvent $event): void
    {
        if ($this->isSkipPushToQueue()) {
            return;
        }

        $object = $event->getObject();

        if (!$object instanceof PriceList) {
            return;
        }

        Logger::info(sprintf(
            '[PriceListSubscriber] Processing PriceList: %d - %s',
            $object->getId(),
            $object->getFullPath()
        ));

        $this->processObject($object);
        $this->processAllDescendants($object);

        if ($object->getParent() instanceof PriceList) {
            $this->processObject($object->getParent());
        }
    }

    private function processAllDescendants(PriceList $object): void
    {
        foreach ($object->getChildren([AbstractObject::OBJECT_TYPE_OBJECT]) as $child) {
            if (!$child instanceof PriceList) {
                continue;
            }

            $this->processObject($child);
            $this->processAllDescendants($child);
        }
    }
}
