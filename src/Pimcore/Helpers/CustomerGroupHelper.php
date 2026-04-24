<?php

declare(strict_types=1);

namespace App\Pimcore\Helpers;

use OpenDxp\Model\DataObject\CustomerGroup;

class CustomerGroupHelper
{
    /**
     * @param CustomerGroup[] $customerGroups
     * @return int[]
     */
    public static function getPublishedIds(iterable $customerGroups): array
    {
        $ids = [];
        foreach ($customerGroups as $group) {
            if ($group instanceof CustomerGroup && $group->isPublished()) {
                $ids[] = $group->getId();
            }
        }

        return $ids;
    }
}
