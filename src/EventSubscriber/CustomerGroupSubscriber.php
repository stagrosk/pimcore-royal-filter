<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Pimcore\Model\DataObject\CustomerGroup;

class CustomerGroupSubscriber extends AbstractWebhookSubscriber
{
    protected function getObjectClass(): string
    {
        return CustomerGroup::class;
    }

    protected function getLogPrefix(): string
    {
        return 'CustomerGroupSubscriber';
    }
}
