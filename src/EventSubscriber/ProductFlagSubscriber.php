<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\OpenDxp\Model\DataObject\ProductFlag;

class ProductFlagSubscriber extends AbstractWebhookSubscriber
{
    protected function getObjectClass(): string
    {
        return ProductFlag::class;
    }

    protected function getLogPrefix(): string
    {
        return 'ProductFlagSubscriber';
    }
}
