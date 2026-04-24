<?php

namespace OpendxpVendureBridgeBundle\Serialization\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\JsonSerializationVisitor;
use OpenDxp\Model\DataObject;
use OpenDxp\Tool;
use OpendxpVendureBridgeBundle\Component\Serializer\JMSSerializerFactory;
use OpendxpVendureBridgeBundle\Model\OpendxpVendureInterface;

class CustomFieldsHandler
{
    private JMSSerializerFactory $jmsSerializerFactory;

    /**
     * @param \OpendxpVendureBridgeBundle\Component\Serializer\JMSSerializerFactory $jmsSerializerFactory
     */
    public function __construct(
        JMSSerializerFactory $jmsSerializerFactory
    ) {
        $this->jmsSerializerFactory = $jmsSerializerFactory;
    }

    /**
     * @param \JMS\Serializer\JsonSerializationVisitor $visitor
     * @param int $objectId
     * @param array $type
     * @param \JMS\Serializer\Context $context
     *
     * @return array|null
     */
    public function serializeCustomFieldTypeImagesArray(JsonSerializationVisitor $visitor, int $objectId, array $type, Context $context): ?array
    {
        $object = DataObject::getById($objectId);
        if (!$object instanceof OpendxpVendureInterface) {
            return null;
        }

        $data = [];
        $images = $object->getImages();
        foreach ($images as $image) {
            $json = $this->jmsSerializerFactory->serialize($image, ['api']);
            $data[] = json_decode($json);
        }

        return [
            'type' => 'images',
            'value' => $data
        ];
    }

    /**
     * @param \JMS\Serializer\JsonSerializationVisitor $visitor
     * @param int $objectId
     * @param array $type
     * @param \JMS\Serializer\Context $context
     *
     * @return array|null
     */
    public function serializeCustomFieldTypeImageGallery(JsonSerializationVisitor $visitor, int $objectId, array $type, Context $context): ?array
    {
        $object = DataObject::getById($objectId);
        if (!$object instanceof OpendxpVendureInterface) {
            return null;
        }

        $data = [];
        $imageGallery = $object->getImages();
        if ($imageGallery instanceof DataObject\Data\ImageGallery) {
            foreach ($imageGallery->getItems() as $item) {
                // this will serialize image by yaml definition
                $json = $this->jmsSerializerFactory->serialize($item->getImage(), ['api']);
                $data[] = json_decode($json);
            }
        }

        return [
            'type' => 'images',
            'value' => $data
        ];
    }

    public function serializeCustomFieldTypeProducts(JsonSerializationVisitor $visitor, int $objectId, array $type, Context $context): ?array
    {
        $object = DataObject::getById($objectId);
        if (!$object instanceof OpendxpVendureInterface) {
            return null;
        }

        $data = [];

        return [
            'type' => 'products',
            'value' => $data
        ];
    }

//    /**
//     * @param \JMS\Serializer\JsonSerializationVisitor $visitor
//     * @param int $objectId
//     * @param array $type
//     * @param \JMS\Serializer\Context $context
//     *
//     * @return array|null
//     */
//    public function serializeCustomFieldTypeLocaleString(JsonSerializationVisitor $visitor, int $objectId, array $type, Context $context): ?array
//    {
//        $object = DataObject::getById($objectId);
//        if (!$object instanceof OpendxpVendureInterface) {
//            return null;
//        }
//
//        $data = [];
//        foreach (Tool::getValidLanguages() as $language) {
//            $getter = 'get' . ucfirst($fieldName);
//            $value = $object->{$getter}($language);
//            $data[$language] = $value;
//        }
//
//        return [
//            'type' => 'localeString',
//            'value' => $data
//        ];
//    }

    /**
     * @param \JMS\Serializer\JsonSerializationVisitor $visitor
     * @param string $value
     * @param array $type
     * @param \JMS\Serializer\Context $context
     *
     * @return string[]|null
     */
    public function serializeCustomFieldTypeString(JsonSerializationVisitor $visitor, string $value, array $type, Context $context): ?array
    {
        return [
            'type' => 'string',
            'value' => $value
        ];
    }

    /**
     * @param \JMS\Serializer\JsonSerializationVisitor $visitor
     * @param mixed $value
     * @param array $type
     * @param \JMS\Serializer\Context $context
     *
     * @return array|null
     */
    public function serializeCustomFieldTypeNumber(JsonSerializationVisitor $visitor, mixed $value, array $type, Context $context): ?array
    {
        return [
            'type' => 'number',
            'value' => $value
        ];
    }
}
