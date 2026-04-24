<?php

namespace App\OpenDxp\ClassificationStore;

use App\OpenDxp\Model\ClassificationStore\ClassificationStoreMapping;
use App\OpenDxp\Model\ClassificationStore\ClassificationStoreMappingItem;
use App\OpenDxp\Helpers\InheritanceHelper;
use App\OpenDxp\Helpers\VersionHelper;
use OpenDxp\Logger;
use OpenDxp\Model\DataObject\AbstractObject;
use OpenDxp\Model\DataObject\Classificationstore;
use OpenDxp\Model\DataObject\Classificationstore\GroupConfig;
use OpenDxp\Model\DataObject\Classificationstore\KeyConfig;
use OpenDxp\Model\DataObject\Data\QuantityValue;

class ClassificationStoreHelper
{
    /**
     * @param \OpenDxp\Model\DataObject\Classificationstore|null $classificationStore
     *
     * @return ClassificationStoreMapping
     */
    public function getClassificationStoreMapped(?Classificationstore $classificationStore): ClassificationStoreMapping
    {
        $classificationStoreMapping = new ClassificationStoreMapping();

        if ($classificationStore instanceof Classificationstore) {
            foreach ($classificationStore->getItems() as $groupConfigId => $group) {
                // get group config
                $groupConfig = GroupConfig::getById($groupConfigId);
                if ($groupConfig instanceof GroupConfig) {
                    foreach ($group as $key => $item) {
                        $keyConfig = KeyConfig::getById(strtolower($key));
                        if (!$keyConfig instanceof KeyConfig) {
                            continue;
                        }

                        switch ($keyConfig->getType()) {
                            case 'select':
                                $definition = json_decode($keyConfig->getDefinition(), true);
                                $defaultValue = array_key_exists('default', $item) ? $item['default'] : null;

                                $classificationStoreMappingItem = new ClassificationStoreMappingItem(
                                    $keyConfig,
                                    $groupConfig,
                                    $keyConfig->getTitle(),
                                    collect($definition['options'])->where('value', $defaultValue)->first()['key'] ?? null,
                                    $defaultValue,
                                    null,
                                    null,
                                    collect($definition['options'])->where('value', $defaultValue)->first()['value'] ?? null
                                );
                                $classificationStoreMapping->addItem($classificationStoreMappingItem);
                                break;

                            case 'numeric':
                            case 'textarea':
                            case 'input':
                                $defaultValue = array_key_exists('default', $item) ? $item['default'] : null;
                                $classificationStoreMappingItem = new ClassificationStoreMappingItem(
                                    $keyConfig,
                                    $groupConfig,
                                    $keyConfig->getTitle(),
                                    $defaultValue,
                                    $defaultValue,
                                );
                                $classificationStoreMapping->addItem($classificationStoreMappingItem);
                                break;

                            case 'booleanSelect':
                                $boolValue = $item['default'];
                                $classificationStoreMappingItem = new ClassificationStoreMappingItem(
                                    $keyConfig,
                                    $groupConfig,
                                    $keyConfig->getTitle(),
                                    $boolValue,
                                    $boolValue ? 1 : 0,
                                );
                                $classificationStoreMapping->addItem($classificationStoreMappingItem);
                                break;

                            case 'quantityValue':
//                            case 'quantityValueRange':
                                $value = $item['default'];
                                if ($value) {
                                    $classificationStoreMappingItem = new ClassificationStoreMappingItem(
                                        $keyConfig,
                                        $groupConfig,
                                        $keyConfig->getTitle(),
                                        is_string($value) ? $value : sprintf(
                                            '%s %s',
                                            $value->getValue(),
                                            ($value->getUnit()) ? $value->getUnit()->getAbbreviation() : null
                                        ),
                                        $value->getValue(),
                                        (!is_string($value) && $value->getUnit()) ? $value->getUnit()->getAbbreviation() : null,
                                        ($value->getUnit()) ? $value->getUnit()->getLongName() : null,
                                    );
                                    $classificationStoreMapping->addItem($classificationStoreMappingItem);
                                }
                                break;

                            case 'checkbox':
                                $checkboxValue = (bool)$item['default'];
                                $classificationStoreMappingItem = new ClassificationStoreMappingItem(
                                    $keyConfig,
                                    $groupConfig,
                                    $keyConfig->getTitle(),
                                    $checkboxValue,
                                    $checkboxValue ? 1 : 0,
                                );
                                $classificationStoreMapping->addItem($classificationStoreMappingItem);
                                break;

                            case 'rgbaColor':
                                /** @var \OpenDxp\Model\DataObject\Data\RgbaColor $rgbaColor */
                                $rgbaColor = $item['default'];
                                $classificationStoreMappingItem = new ClassificationStoreMappingItem(
                                    $keyConfig,
                                    $groupConfig,
                                    $keyConfig->getTitle(),
                                    $rgbaColor->getHex(),
                                );
                                $classificationStoreMapping->addItem($classificationStoreMappingItem);
                                break;

                            case 'date':
                                $classificationStoreMappingItem = new ClassificationStoreMappingItem(
                                    $keyConfig,
                                    $groupConfig,
                                    $keyConfig->getTitle(),
                                    $item['default']->format('Y-m-d'),
                                );
                                $classificationStoreMapping->addItem($classificationStoreMappingItem);
                                break;

                            case 'datetime':
                                $classificationStoreMappingItem = new ClassificationStoreMappingItem(
                                    $keyConfig,
                                    $groupConfig,
                                    $keyConfig->getTitle(),
                                    $item['default']->format('Y-m-d\TH:i:s'),
                                );
                                $classificationStoreMapping->addItem($classificationStoreMappingItem);
                                break;
                        }
                    }
                }
            }
        }

        return $classificationStoreMapping;
    }

    /**
     * @param \OpenDxp\Model\DataObject\AbstractObject $object
     * @param array $keyPairsData
     * @param bool $skipPreSave
     *
     * @throws \OpenDxp\Model\Element\DuplicateFullPathException
     * @throws \Exception
     * @return void
     */
    public function fillObjectDataOnClassificationStore(
        AbstractObject $object,
        array $keyPairsData,
        bool $skipPreSave = false
    ): void {
        Logger::notice(' Fill data on object classification store. Id: ' . $object->getId() . ' Class: '. $object->getClassName());

        // set product classification store data
        InheritanceHelper::useInheritedValues(function () use ($object, $keyPairsData, $skipPreSave) {
            $classificationStore = $object->getMetadata();
            if (!$classificationStore instanceof Classificationstore) {
                $classificationStore = new Classificationstore();
            }

            if (!empty($keyPairsData)) {
                $activeGroupConfigs = [];
                foreach ($keyPairsData as $keyPairData) {
                    /** @var \OpenDxp\Model\DataObject\Classificationstore\GroupConfig $groupConfig */
                    $groupConfig = $keyPairData['groupConfig'];
                    /** @var \OpenDxp\Model\DataObject\Classificationstore\KeyConfig $keyConfig */
                    $keyConfig = $keyPairData['keyConfig'];

                    $value = $keyPairData['value'];

                    // value for quantity value must be transformed as data quantity value
                    if ($keyConfig instanceof Classificationstore\KeyConfig && $keyConfig->getType() === 'quantityValue') {
                        $definition = json_decode($keyConfig->getDefinition());

                        $quantityValue = new QuantityValue();
                        $quantityValue->setUnitId($definition->defaultUnit);
                        $quantityValue->setValue($value);

                        $value = $quantityValue;
                    }

                    // set on classification store
                    $classificationStore->setLocalizedKeyValue(
                        $groupConfig->getId(),
                        $keyConfig->getId(),
                        $value,
                        $keyPairData['language']
                    );

                    $activeGroupConfigs[$groupConfig->getId()] = $groupConfig;
                }

                // active groups
                $this->resolveActiveGroups($classificationStore, $activeGroupConfigs);

                // set on product and SAVE
                $object->setMetadata($classificationStore);

                // save
                if (!$skipPreSave) {
                    VersionHelper::useVersioning(function () use ($object) {
                        $object->save();
                        Logger::notice('            -> object SAVE');
                    }, false);
                }
            }
        }, false);
    }

    /**
     * NOTE: this is solving only active groups with name starting with NUMBER => that means it was created from feature. all other from another source is untouched
     *
     * @param \OpenDxp\Model\DataObject\Classificationstore $classificationStore
     * @param \OpenDxp\Model\DataObject\Classificationstore\GroupConfig[] $activeGroupConfigs
     */
    private function resolveActiveGroups(Classificationstore $classificationStore, array $activeGroupConfigs): void
    {
        $classificationStoreActiveGroups = $classificationStore->getActiveGroups();
        foreach ($classificationStoreActiveGroups as $groupConfigId => $value) {
            // get group by id
            $groupConfigLoaded = Classificationstore\GroupConfig::getById($groupConfigId);
            if ($groupConfigLoaded instanceof Classificationstore\GroupConfig) {
                // check if name is starting with number and remove it from active groups.
                $name = $groupConfigLoaded->getName();
                preg_match('/^([0-9]{1,}-?)/', $name, $matches);
                if (!empty($matches)) {
                    unset($classificationStoreActiveGroups[$groupConfigId]);
                }
            } else {
                // not found ... unset!
                unset($classificationStoreActiveGroups[$groupConfigId]);
            }
        }

        // set active groups by processed group configs
        foreach ($activeGroupConfigs as $groupConfig) {
            $classificationStoreActiveGroups[$groupConfig->getId()] = true;
        }

        Logger::notice('            -> resolved active groups');

        $classificationStore->setActiveGroups($classificationStoreActiveGroups);
    }
}
