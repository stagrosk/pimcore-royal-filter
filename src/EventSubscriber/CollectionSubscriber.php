<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use OpenDxp\Event\Model\DataObjectEvent;
use OpenDxp\Model\DataObject\AbstractObject;
use OpenDxp\Model\DataObject\Collection;
class CollectionSubscriber extends AbstractWebhookSubscriber
{
    protected function getObjectClass(): string
    {
        return Collection::class;
    }

    protected function getLogPrefix(): string
    {
        return 'CollectionSubscriber';
    }

    public function onPostUpdate(DataObjectEvent $event): void
    {
        if ($this->isSkipPushToQueue()) {
            return;
        }

        $object = $event->getObject();

        if (!$object instanceof Collection) {
            return;
        }

        $this->processObject($object);

        foreach ($object->getChildren([AbstractObject::OBJECT_TYPE_OBJECT]) as $child) {
            if ($child instanceof Collection) {
                $this->processObject($child);
            }
        }

        if ($object->getParent() instanceof Collection) {
            $this->processObject($object->getParent());
        }
    }

}
