<?php

namespace PimcoreVendureBridgeBundle\Serialization\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\JsonSerializationVisitor;
use OpenDxp\Model\Document;

class PimcoreDocumentHandler
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
        if ($relation instanceof Document) {
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
        if ($relation instanceof Document) {
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
     * @return array|\Pimcore\Model\Document|null
     */
    public function deserializeRelation(JsonDeserializationVisitor $visitor, $relation, array $type, Context $context): array|Document|null
    {
        $className = $type['params'][0]['name'] ?? null;

        if (is_array($relation)) {
            $result = [];

            foreach ($relation as $rel) {
                $obj = Document::getById($rel);

                if ($obj instanceof $className) {
                    $result[] = $obj;
                }
            }

            return $result;
        }

        $obj = Document::getById($relation);

        return $obj instanceof $className ? $obj : null;
    }
}
