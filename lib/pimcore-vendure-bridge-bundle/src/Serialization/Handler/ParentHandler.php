<?php

namespace PimcoreVendureBridgeBundle\Serialization\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\JsonSerializationVisitor;
use Pimcore\Bundle\EcommerceFrameworkBundle\Model\ProductInterface;
use Pimcore\Model\DataObject;
use PimcoreVendureBridgeBundle\Model\PimcoreVendureInterface;

class ParentHandler
{
    /**
     * @param \JMS\Serializer\JsonSerializationVisitor $visitor
     * @param mixed $objectId
     * @param array $type
     * @param \JMS\Serializer\Context $context
     *
     * @return int|null
     */
    public function serializeParentSameInstance(JsonSerializationVisitor $visitor, int $objectId, array $type, Context $context): ?int
    {
        $object = DataObject::getById($objectId);
        if (!$object instanceof PimcoreVendureInterface) {
            return null;
        }

        return $object->getParent() instanceof $object ? $object->getParentId() : null;
    }

    /**
     * TODO: define that object must be variant and parent product interface
     *
     * @param \JMS\Serializer\JsonSerializationVisitor $visitor
     * @param int $objectId
     * @param array $type
     * @param \JMS\Serializer\Context $context
     *
     * @return int|null
     */
    public function serializeVariantParent(JsonSerializationVisitor $visitor, int $objectId, array $type, Context $context): ?int
    {
        $object = DataObject::getById($objectId);
        if (!$object instanceof PimcoreVendureInterface && !$object instanceof ProductInterface) {
            return null;
        }

        return $object->getParent() instanceof $object ? $object->getParentId() : null;
    }

    /**
     * @param \JMS\Serializer\JsonSerializationVisitor $visitor
     * @param array $objects
     * @param array $type
     * @param \JMS\Serializer\Context $context
     *
     * @return array|null
     */
    public function serializeObjectAndParentIds(JsonSerializationVisitor $visitor, array $objects, array $type, Context $context): ?array
    {
        $categoryIds = [];
        foreach ($objects as $object) {
            $this->getParentIdsToRootId($object, $categoryIds);
        }

        return $categoryIds;
    }

    /**
     * @param \Pimcore\Model\DataObject $object
     * @param array $ids
     *
     * @return void
     */
    private function getParentIdsToRootId(DataObject $object, array &$ids = []): void
    {
        // add object to array
        $ids[] = $object->getId();

        // if object got parent so add parent to array
        $categoryParent = $object->getParent();
        if ($categoryParent instanceof $object) {
            $this->getParentIdsToRootId($categoryParent, $ids);
        }
    }
}
