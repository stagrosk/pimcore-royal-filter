<?php

namespace PimcoreVendureBridgeBundle\Serialization\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\JsonSerializationVisitor;
use OpenDxp\Model\Asset;

class PimcoreAssetHandler
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
        if ($relation instanceof Asset) {
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
        if ($relation instanceof Asset) {
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
     * @return \OpenDxp\Model\Asset|array|null
     */
    public function deserializeRelation(JsonDeserializationVisitor $visitor, $relation, array $type, Context $context): Asset|array|null
    {
        $className = $type['params'][0]['name'] ?? null;

        if (is_array($relation)) {
            $result = [];

            foreach ($relation as $rel) {
                $obj = Asset::getById($rel);

                if ($obj instanceof $className) {
                    $result[] = $obj;
                }
            }

            return $result;
        }

        $obj = Asset::getById($relation);

        return $obj instanceof $className ? $obj : null;
    }
}
