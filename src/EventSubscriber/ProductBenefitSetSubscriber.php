<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Pimcore\Model\DataObject\ProductBenefitSet;

class ProductBenefitSetSubscriber extends AbstractWebhookSubscriber
{
    protected function getObjectClass(): string
    {
        return ProductBenefitSet::class;
    }

    protected function getLogPrefix(): string
    {
        return 'ProductBenefitSetSubscriber';
    }
}
