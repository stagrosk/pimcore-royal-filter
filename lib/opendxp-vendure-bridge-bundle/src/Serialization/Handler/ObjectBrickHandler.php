<?php

namespace OpendxpVendureBridgeBundle\Serialization\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\JsonSerializationVisitor;

class ObjectBrickHandler
{
    private const SKIP_ATTRIBUTES = [
        'type',
        'fieldname',
        'doDelete',
        'object',
        'objectId',
        'dao',
        'loadedLazyKeys',
        '_fulldump',
        'o_dirtyFields',
    ];

    public function serializeObjectBrickToArray(JsonSerializationVisitor $visitor, $relation, array $type, Context $context): array
    {
        $class = new \ReflectionClass($relation);
        $properties = $class->getProperties();

        $data = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            if (!in_array($propertyName, self::SKIP_ATTRIBUTES)) {
                $property->setAccessible(true);
                $propertyValue = $property->getValue($relation);
                $data[] = [
                    'type' => $propertyName,
                    'value' => $propertyValue,
                ];
            }
        }

        return $data;
    }
}
