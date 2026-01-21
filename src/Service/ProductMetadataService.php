<?php

namespace App\Service;

use App\Pimcore\ClassificationStore\ClassificationStoreHelper;
use App\Pimcore\ClassificationStore\ClassificationStoreService;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Adapter;
use Pimcore\Model\DataObject\Body;
use Pimcore\Model\DataObject\Center;
use Pimcore\Model\DataObject\Classificationstore;
use Pimcore\Model\DataObject\Equipment;
use Pimcore\Model\DataObject\RoyalFilter;

class ProductMetadataService
{
    public const ALLOWED_SUM_PARAMETERS = ['body1', 'bodyMiddle', 'body2', 'center1', 'centerMiddle', 'center2'];

    /**
     * @param \App\Pimcore\ClassificationStore\ClassificationStoreHelper $classificationStoreHelper
     * @param \App\Pimcore\ClassificationStore\ClassificationStoreService $classificationStoreService
     */
    public function __construct(
        protected readonly ClassificationStoreHelper $classificationStoreHelper,
        protected readonly ClassificationStoreService $classificationStoreService
    ) {
    }

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     * @param array $partOverrides
     *
     * @return array
     */
    public function getMappedParametersOfParts(AbstractObject $object, array $partOverrides = []): array
    {
        $params = [];

        if ($object instanceof RoyalFilter) {
            if ($object->getBody1() instanceof Body) {
                $params['body1'] = [
                    'items' => $object->getBody1()->getMetadata()?->getItems(),
                    'mapping' => $this->classificationStoreHelper->getClassificationStoreMapped($object->getBody1()->getMetadata()),
                ];
            }
            if ($object->getBodyMiddle() instanceof Body) {
                $params['bodyMiddle'] = [
                    'items' => $object->getBodyMiddle()->getMetadata()?->getItems(),
                    'mapping' => $this->classificationStoreHelper->getClassificationStoreMapped($object->getBodyMiddle()->getMetadata()),
                ];
            }
            if ($object->getBody2() instanceof Body) {
                $params['body2'] = [
                    'items' => $object->getBody2()->getMetadata()?->getItems(),
                    'mapping' => $this->classificationStoreHelper->getClassificationStoreMapped($object->getBody2()->getMetadata()),
                ];
            }
            if ($object->getCenterBody1() instanceof Center) {
                $params['center1'] = [
                    'items' => $object->getCenterBody1()->getMetadata()?->getItems(),
                    'mapping' => $this->classificationStoreHelper->getClassificationStoreMapped($object->getCenterBody1()->getMetadata()),
                ];
            }
            if ($object->getCenterBodyMiddle() instanceof Center) {
                $params['centerMiddle'] = [
                    'items' => $object->getCenterBodyMiddle()->getMetadata()?->getItems(),
                    'mapping' => $this->classificationStoreHelper->getClassificationStoreMapped($object->getCenterBodyMiddle()->getMetadata()),
                ];
            }
            if ($object->getCenterBody2() instanceof Center) {
                $params['center2'] = [
                    'items' => $object->getCenterBody2()->getMetadata()?->getItems(),
                    'mapping' => $this->classificationStoreHelper->getClassificationStoreMapped($object->getCenterBody2()->getMetadata()),
                ];
            }
            if ($object->getAdapter() instanceof Adapter || isset($partOverrides['adapter'])) {
                $adapter = $partOverrides['adapter'] ?? $object->getAdapter();
                $params['adapter'] = [
                    'items' => $adapter->getMetadata()?->getItems(),
                    'mapping' => $this->classificationStoreHelper->getClassificationStoreMapped($adapter->getMetadata()),
                ];
            }
            if ($object->getEquipBody1() instanceof Equipment || isset($partOverrides['equipBody1'])) {
                $equipBody1 = $partOverrides['equipBody1'] ?? $object->getEquipBody1();
                $params['equipBody1'] = [
                    'items' => $equipBody1->getMetadata()?->getItems(),
                    'mapping' => $this->classificationStoreHelper->getClassificationStoreMapped($equipBody1->getMetadata()),
                ];
            }
            if ($object->getEquipBody2() instanceof Equipment|| isset($partOverrides['equipBody2']) ) {
                $equipBody2 = $partOverrides['equipBody2'] ?? $object->getEquipBody2();
                $params['equipBody2'] = [
                    'items' => $equipBody2->getMetadata()?->getItems(),
                    'mapping' => $this->classificationStoreHelper->getClassificationStoreMapped($equipBody2->getMetadata()),
                ];
            }
        } elseif (method_exists($object, 'getMetadata') && $object->getMetadata() instanceof Classificationstore) {
            $params[uniqid()] = [
                'items' => $object->getMetadata()->getItems(),
                'mapping' => $this->classificationStoreHelper->getClassificationStoreMapped($object->getMetadata())
            ];
        }

        return $params;
    }

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $fromObject
     * @param array $partOverrides
     *
     * @throws \Exception
     * @return array
     */
    public function getMappedParametersIndexedByGroupAndKey(AbstractObject $fromObject, array $partOverrides = []): array
    {
        $mappedParameters = $this->getMappedParametersOfParts($fromObject, $partOverrides);

        // this is not royalFilterSetup mapping
        if (count($mappedParameters) === 1) {
            /** @var \App\Pimcore\Model\ClassificationStore\ClassificationStoreMapping $classificationStoreMapping */
            $classificationStoreMapping = reset($mappedParameters)['mapping'];

            return $classificationStoreMapping->getMappedParametersIndexedByGroupAndKey();
        } else {
            throw new \Exception('[getMappedParametersIndexedByGroupAsKey] Implement if necessary');
        }
    }

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $product
     * @param \Pimcore\Model\DataObject\AbstractObject $fromObject
     * @param array $partOverrides
     * @param bool $skipPreSave
     *
     * @throws \Pimcore\Model\Element\DuplicateFullPathException
     * @return void
     */
    public function copyMetadata(AbstractObject $product, AbstractObject $fromObject, array $partOverrides = [], bool $skipPreSave = false): void
    {
        $mappedParameters = $this->getMappedParametersOfParts($fromObject, $partOverrides);

        $mappedProperties = [];
        foreach ($mappedParameters as $objectName => $objectData) {
            foreach ($objectData['items'] as $groupKeyId => $keyConfigValues) {
                $groupConfig = $this->classificationStoreService->getGroupConfigById($groupKeyId);

                foreach ($keyConfigValues as $keyConfigId => $keyConfigValue) {
                    $keyConfig = $this->classificationStoreService->getKeyConfigById($keyConfigId);

                    $ident = $groupConfig->getId() .'-'. $keyConfig->getId();
                    if (!array_key_exists($ident, $mappedProperties)) {
                        $mappedProperties[$ident] = [
                            'value' => is_object($keyConfigValue['default']) ? $keyConfigValue['default']->getValue() : $keyConfigValue['default'],
                            'groupConfig' => $groupConfig,
                            'keyConfig' => $keyConfig,
                            'language' => 'default'
                        ];
                    } else {
                        // height as SUM
                        if (in_array($objectName, self::ALLOWED_SUM_PARAMETERS, true) && $keyConfig->getName() === 'height') {
                            $mappedProperties[$ident]['value'] = (int)$mappedProperties[$ident]['value'] + (int)$keyConfigValue['default']->getValue();
                        }
                    }
                }
            }
        }

        $this->classificationStoreHelper->fillObjectDataOnClassificationStore($product, $mappedProperties, $skipPreSave);
    }
}
