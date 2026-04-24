<?php

namespace PimcoreVendureBridgeBundle\Serialization\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\JsonSerializationVisitor;
use OpenDxp\Bundle\EcommerceFrameworkBundle\Model\ProductInterface;
use OpenDxp\Model\DataObject;
use PimcoreVendureBridgeBundle\Model\PimcoreVendureInterface;

class ProductHandler
{
    private const HIERARCHY_TYPE_PRODUCT = 'P';
    private const HIERARCHY_TYPE_VARIANT = 'V';
    private const HIERARCHY_TYPE_GROUP = 'G';

    /**
     * @param \JMS\Serializer\JsonSerializationVisitor $visitor
     * @param int $objectId
     * @param array $type
     * @param \JMS\Serializer\Context $context
     *
     * @return string|null
     */
    public function serializeHierarchyType(JsonSerializationVisitor $visitor, int $objectId, array $type, Context $context): ?string
    {
        $object = DataObject::getById($objectId);
        if (!$object instanceof PimcoreVendureInterface && !$object instanceof ProductInterface) {
            return null;
        }

        return $this->getHierarchyType($object);
    }

    /**
     * @param \OpenDxp\Bundle\EcommerceFrameworkBundle\Model\ProductInterface $product
     *
     * @return string
     */
    private function getHierarchyType(ProductInterface $product): string
    {
        $type = self::HIERARCHY_TYPE_PRODUCT;
        $parent = $product->getParent();

        // don`t have children
        if (!$product->hasChildren()) {
            if ($parent instanceof ProductInterface) {
                // if parent is product then it is variant
                $type = self::HIERARCHY_TYPE_VARIANT;
            }
        } else {
            // decide if is master or group
            foreach ($product->getChildren() as $child) {
                // check if any child is product
                if ($child instanceof ProductInterface) {
                    foreach ($child->getChildren() as $subChild) {
                        if ($subChild instanceof ProductInterface) {
                            $type = self::HIERARCHY_TYPE_GROUP;
                            break;
                        }
                    }

                    // break because any child in is product
                    break;
                }
            }
        }

        return $type;
    }
}
