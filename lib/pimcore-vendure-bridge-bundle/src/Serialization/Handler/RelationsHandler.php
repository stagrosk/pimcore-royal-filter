<?php

namespace PimcoreVendureBridgeBundle\Serialization\Handler;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use JMS\Serializer\Context;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\JsonSerializationVisitor;

class RelationsHandler
{
    private EntityManagerInterface $manager;

    /**
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param JsonSerializationVisitor $visitor
     * @param array|\Traversable       $relation
     * @param array                    $type
     * @param Context                  $context
     *
     * @return array
     */
    public function serializeRelation(JsonSerializationVisitor $visitor, $relation, array $type, Context $context): array
    {
        if ($relation instanceof \Traversable) {
            $relation = iterator_to_array($relation);
        }

        $manager = $this->manager;

        if ($context->hasAttribute('em') && $context->getAttribute('em') instanceof EntityManagerInterface) {
            $manager = $context->getAttribute('em');
        }

        if (is_array($relation)) {
            return array_map(function ($rel) use ($manager) {
                return $this->getSingleEntityRelation($rel, $manager);
            }, $relation);
        }

        return $this->getSingleEntityRelation($relation, $manager);
    }

    /**
     * @param JsonDeserializationVisitor $visitor
     * @param array                      $relation
     * @param array                      $type
     * @param Context                    $context
     *
     * @return array|object
     */
    public function deserializeRelation(JsonDeserializationVisitor $visitor, $relation, array $type, Context $context): object|array|null
    {
        $className = $type['params'][0]['name'] ?? null;

        $manager = $this->manager;

        if ($context->hasAttribute('em') && $context->getAttribute('em') instanceof EntityManagerInterface) {
            $manager = $context->getAttribute('em');
        }

        $metadata = $manager->getClassMetadata($className);

        if (!is_array($relation)) {
            return $this->findById($relation, $metadata, $manager);
        }

        $single = false;
        if ($metadata->isIdentifierComposite) {
            $single = true;
            foreach ($metadata->getIdentifierFieldNames() as $idName) {
                $single = $single && array_key_exists($idName, $relation);
            }
        }

        if ($single) {
            return $this->findById($relation, $metadata, $manager);
        }

        $objects = [];
        foreach ($relation as $idSet) {
            $objects[] = $this->findById($idSet, $metadata, $manager);
        }

        return $objects;
    }

    /**
     * @param mixed                  $relation
     * @param EntityManagerInterface $entityManager
     *
     * @return array
     */
    protected function getSingleEntityRelation($relation, EntityManagerInterface $entityManager): array
    {
        $metadata = $entityManager->getClassMetadata(get_class($relation));

        $ids = $metadata->getIdentifierValues($relation);
        if (!$metadata->isIdentifierComposite) {
            $ids = array_shift($ids);
        }

        return $ids;
    }

    /**
     * @param mixed                  $id
     * @param ClassMetadata          $metadata
     * @param EntityManagerInterface $manager
     *
     * @return object|null
     */
    protected function findById($id, ClassMetadata $metadata, EntityManagerInterface $manager): ?object
    {
        return $manager->find($metadata->getName(), $id);
    }
}
