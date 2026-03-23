<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Pimcore\Event\DataObjectEvents;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Model\DataObject\CustomerRole;
use Pimcore\Model\DataObject\Product;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CustomerRoleSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            DataObjectEvents::PRE_ADD => ['onPreAdd'],
        ];
    }

    public function onPreAdd(DataObjectEvent $event): void
    {
        $object = $event->getObject();

        if (!$object instanceof Product) {
            return;
        }

        $existingRoles = $object->getCustomerRoles();
        if (!empty($existingRoles)) {
            return;
        }

        $autoRoles = $this->getAutoAssignRoles();
        if (!empty($autoRoles)) {
            $object->setCustomerRoles($autoRoles);
        }
    }

    /**
     * @return CustomerRole[]
     */
    private function getAutoAssignRoles(): array
    {
        $listing = CustomerRole::getList();
        $listing->setCondition('addAutomaticallyOnProduct = 1');

        return $listing->load();
    }
}
