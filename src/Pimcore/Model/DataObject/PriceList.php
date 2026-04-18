<?php

declare(strict_types=1);

namespace App\Pimcore\Model\DataObject;

use Pimcore\Model\DataObject\AbstractObject;

class PriceList extends \Pimcore\Model\DataObject\PriceList
{
    /**
     * Get parent PriceList ID (only if parent is PriceList)
     */
    public function getParentPriceListId(): ?int
    {
        $parent = $this->getParent();

        if ($parent instanceof self) {
            return $parent->getId();
        }

        return null;
    }

    /**
     * Get children PriceList IDs
     */
    public function getChildrenIds(): array
    {
        $childrenIds = [];
        $children = $this->getChildren([AbstractObject::OBJECT_TYPE_OBJECT]);

        foreach ($children as $child) {
            if ($child instanceof self && $child->isPublished()) {
                $childrenIds[] = $child->getId();
            }
        }

        return $childrenIds;
    }
}