<?php

namespace PimcoreVendureBridgeBundle\Serialization\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\JsonSerializationVisitor;
use Pimcore\Model\DataObject;
use Pimcore\Model\DataObject\Concrete;

class PimcoreObjectHandler
{
    /**
     * @param \JMS\Serializer\JsonSerializationVisitor $visitor
     * @param $relation
     * @param array $type
     * @param \JMS\Serializer\Context $context
     *
     * @return int|null
     */
    public function serializeRelation(JsonSerializationVisitor $visitor, $relation, array $type, Context $context): ?int
    {
        if ($relation instanceof Concrete) {
            return $relation->getId();
        }

        return null;
    }

    /**
     * @param \JMS\Serializer\JsonSerializationVisitor $visitor
     * @param $relation
     * @param array $type
     * @param \JMS\Serializer\Context $context
     *
     * @return array
     */
    public function serializeRelationReturnArray(JsonSerializationVisitor $visitor, $relation, array $type, Context $context): array
    {
        if ($relation instanceof Concrete) {
            return [$relation->getId()];
        }

        return [];
    }

    /**
     * @param \JMS\Serializer\JsonDeserializationVisitor $visitor
     * @param $relation
     * @param array $type
     * @param \JMS\Serializer\Context $context
     *
     * @return \Pimcore\Model\DataObject|array|\Pimcore\Model\DataObject\AbstractObject|\Pimcore\Model\DataObject\Concrete|null
     */
    public function deserializeRelation(JsonDeserializationVisitor $visitor, $relation, array $type, Context $context): DataObject|array|DataObject\AbstractObject|Concrete|null
    {
        $className = $type['params'][0]['name'] ?? null;

        if (is_array($relation)) {
            $result = [];

            foreach ($relation as $rel) {
                $obj = DataObject::getById($rel);

                if ($obj instanceof $className) {
                    $result[] = $obj;
                }
            }

            return $result;
        }

        $obj = DataObject::getById($relation);

        return $obj instanceof $className ? $obj : null;
    }
}
