<?php

namespace PimcoreVendureBridgeBundle\Serialization\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\JsonSerializationVisitor;
use Pimcore\Model\DataObject;
use Pimcore\Tool;
use PimcoreVendureBridgeBundle\Model\PimcoreVendureInterface;

class LocalizationHandler
{
    /**
     * @param \JMS\Serializer\JsonSerializationVisitor $visitor
     * @param int $objectId
     * @param array $type
     * @param \JMS\Serializer\Context $context
     *
     * @return array|null
     */
    public function serializeLocalizationNameField(JsonSerializationVisitor $visitor, int $objectId, array $type, Context $context): ?array
    {
        return $this->serializeLocalizationField($objectId, 'name');
    }

    /**
     * @param \JMS\Serializer\JsonSerializationVisitor $visitor
     * @param int $objectId
     * @param array $type
     * @param \JMS\Serializer\Context $context
     *
     * @return array|null
     */
    public function serializeLocalizationTitleField(JsonSerializationVisitor $visitor, int $objectId, array $type, Context $context): ?array
    {
        return $this->serializeLocalizationField($objectId, 'title');
    }
    
    /**
     * @param \JMS\Serializer\JsonSerializationVisitor $visitor
     * @param int $objectId
     * @param array $type
     * @param \JMS\Serializer\Context $context
     *
     * @return array|null
     */
    public function serializeLocalizationDescriptionShortField(JsonSerializationVisitor $visitor, int $objectId, array $type, Context $context): ?array
    {
        return $this->serializeLocalizationField($objectId, 'descriptionShort');
    }

    /**
     * @param \JMS\Serializer\JsonSerializationVisitor $visitor
     * @param int $objectId
     * @param array $type
     * @param \JMS\Serializer\Context $context
     *
     * @return array|null
     */
    public function serializeLocalizationDescriptionField(JsonSerializationVisitor $visitor, int $objectId, array $type, Context $context): ?array
    {
        return $this->serializeLocalizationField($objectId, 'description');
    }

    /**
     * @param \JMS\Serializer\JsonSerializationVisitor $visitor
     * @param int $objectId
     * @param array $type
     * @param \JMS\Serializer\Context $context
     *
     * @return array|null
     */
    public function serializeLocalizationSeoDescriptionField(JsonSerializationVisitor $visitor, int $objectId, array $type, Context $context): ?array
    {
        return $this->serializeLocalizationField($objectId, 'seoDescription');
    }

    /**
     * @param \JMS\Serializer\JsonSerializationVisitor $visitor
     * @param int $objectId
     * @param array $type
     * @param \JMS\Serializer\Context $context
     *
     * @return array|null
     */
    public function serializeLocalizationSlugField(JsonSerializationVisitor $visitor, int $objectId, array $type, Context $context): ?array
    {
        return $this->serializeLocalizationField($objectId, 'slug');
    }

    /**
     * @param \JMS\Serializer\JsonSerializationVisitor $visitor
     * @param int $objectId
     * @param array $type
     * @param \JMS\Serializer\Context $context
     *
     * @return array|null
     */
    public function serializeLocalizationAbsolutePathField(JsonSerializationVisitor $visitor, int $objectId, array $type, Context $context): ?array
    {
        return $this->serializeLocalizationField($objectId, 'absolutePath');
    }

    /**
     * @param int $objectId
     * @param string $fieldName
     *
     * @return array|null
     */
    private function serializeLocalizationField(int $objectId, string $fieldName): ?array
    {
        $object = DataObject::getById($objectId);
        if (!$object instanceof PimcoreVendureInterface) {
            return null;
        }

        $data = [];
        foreach (Tool::getValidLanguages() as $language) {
            $getter = 'get' . ucfirst($fieldName);
            $value = $object->{$getter}($language);
            $data[$language] = $value;
        }

        return $data;
    }
}
