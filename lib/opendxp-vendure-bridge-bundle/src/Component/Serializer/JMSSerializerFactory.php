<?php

namespace OpendxpVendureBridgeBundle\Component\Serializer;

use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;

class JMSSerializerFactory
{
    private SerializerInterface $serializer;

    /**
     * @param \JMS\Serializer\SerializerInterface $serializer
     */
    public function __construct(
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
    }

    /**
     * @param string $json
     * @param string $entityName
     * @param array $groups
     *
     * @return mixed
     */
    public function deserialize(string $json, string $entityName, array $groups = [])
    {
        $context = new DeserializationContext();
        $context->setAttribute('target', $entityName);
        $context->setGroups($groups);

        return $this->serializer->deserialize(
            $json,
            $entityName,
            'json',
            $context
        );
    }

    /**
     * @param $entity
     * @param array $groups
     *
     * @return string
     */
    public function serialize($entity, array $groups = []): string
    {
        $context = new SerializationContext();
        if (!empty($groups)) {
            $context->setGroups($groups);
        }
        $context->setSerializeNull(true);

        return $this->serializer->serialize(
            $entity,
            'json',
            $context
        );
    }
}
