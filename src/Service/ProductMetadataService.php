<?php

namespace App\Service;

use App\OpenDxp\ClassificationStore\ClassificationStoreHelper;
use App\OpenDxp\ClassificationStore\ClassificationStoreService;
use OpenDxp\Model\DataObject\AbstractObject;
use OpenDxp\Model\DataObject\Adapter;
use OpenDxp\Model\DataObject\Body;
use OpenDxp\Model\DataObject\Center;
use OpenDxp\Model\DataObject\Classificationstore;
use OpenDxp\Model\DataObject\Data\QuantityValue;
use OpenDxp\Model\DataObject\Equipment;
use OpenDxp\Model\DataObject\FilterSet;
use OpenDxp\Model\DataObject\QuantityValue\Unit;

class ProductMetadataService
{
    public const ALLOWED_SUM_PARAMETERS = ['body1', 'bodyMiddle', 'body2', 'center1', 'centerMiddle', 'center2'];
    public const WEIGHT_SUM_PARAMETERS = ['body1', 'bodyMiddle', 'body2', 'center1', 'centerMiddle', 'center2', 'adapter', 'equipBody1', 'equipBody2'];

    /**
     * @param \App\OpenDxp\ClassificationStore\ClassificationStoreHelper $classificationStoreHelper
     * @param \App\OpenDxp\ClassificationStore\ClassificationStoreService $classificationStoreService
     */
    public function __construct(
        protected readonly ClassificationStoreHelper $classificationStoreHelper,
        protected readonly ClassificationStoreService $classificationStoreService
    ) {
    }

    /**
     * @param \OpenDxp\Model\DataObject\AbstractObject $object
     * @param array $partOverrides
     *
     * @return array
     */
    public function getMappedParametersOfParts(AbstractObject $object, array $partOverrides = []): array
    {
        $params = [];

        if ($object instanceof FilterSet) {
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
     * @param \OpenDxp\Model\DataObject\AbstractObject $fromObject
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
            /** @var \App\OpenDxp\Model\ClassificationStore\ClassificationStoreMapping $classificationStoreMapping */
            $classificationStoreMapping = reset($mappedParameters)['mapping'];

            return $classificationStoreMapping->getMappedParametersIndexedByGroupAndKey();
        } else {
            throw new \Exception('[getMappedParametersIndexedByGroupAsKey] Implement if necessary');
        }
    }

    /**
     * @param \OpenDxp\Model\DataObject\AbstractObject $product
     * @param \OpenDxp\Model\DataObject\AbstractObject $fromObject
     * @param array $partOverrides
     * @param bool $skipPreSave
     *
     * @throws \OpenDxp\Model\Element\DuplicateFullPathException
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
                        $value = $keyConfigValue['default'];
                        // convert weight to target unit on first occurrence
                        if ($keyConfig->getName() === 'weight' && $value instanceof QuantityValue) {
                            $definition = json_decode($keyConfig->getDefinition());
                            $targetUnitId = $definition->defaultUnit ?? null;
                            $targetUnit = $targetUnitId ? Unit::getById($targetUnitId) : null;
                            if ($targetUnit && $value->getUnitId() !== $targetUnitId) {
                                $value = $value->convertTo($targetUnit);
                            }
                            $value = $value->getValue();
                        } elseif (is_object($value)) {
                            $value = $value->getValue();
                        }
                        $mappedProperties[$ident] = [
                            'value' => $value,
                            'groupConfig' => $groupConfig,
                            'keyConfig' => $keyConfig,
                            'language' => 'default'
                        ];
                    } else {
                        // height as SUM (bodies and centers only)
                        if (in_array($objectName, self::ALLOWED_SUM_PARAMETERS, true) && $keyConfig->getName() === 'height') {
                            $mappedProperties[$ident]['value'] = (int)$mappedProperties[$ident]['value'] + (int)$keyConfigValue['default']->getValue();
                        }
                        // weight as SUM (all components) with unit conversion
                        if (in_array($objectName, self::WEIGHT_SUM_PARAMETERS, true) && $keyConfig->getName() === 'weight') {
                            $weightValue = $keyConfigValue['default'];
                            if ($weightValue instanceof QuantityValue) {
                                $definition = json_decode($keyConfig->getDefinition());
                                $targetUnitId = $definition->defaultUnit ?? null;
                                $targetUnit = $targetUnitId ? Unit::getById($targetUnitId) : null;
                                if ($targetUnit && $weightValue->getUnitId() !== $targetUnitId) {
                                    $weightValue = $weightValue->convertTo($targetUnit);
                                }
                                $mappedProperties[$ident]['value'] = (float)$mappedProperties[$ident]['value'] + (float)$weightValue->getValue();
                            } elseif (is_numeric($weightValue)) {
                                $mappedProperties[$ident]['value'] = (float)$mappedProperties[$ident]['value'] + (float)$weightValue;
                            }
                        }
                    }
                }
            }
        }

        $this->classificationStoreHelper->fillObjectDataOnClassificationStore($product, $mappedProperties, $skipPreSave);
    }
}
