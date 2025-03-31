<?php

namespace App\Pimcore\ClassificationStore;

use App\Pimcore\Helpers\InheritanceHelper;
use App\Pimcore\Helpers\VersionHelper;
use Pimcore\Logger;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Classificationstore;
use Pimcore\Model\DataObject\Classificationstore\GroupConfig;
use Pimcore\Model\DataObject\Classificationstore\KeyConfig;
use Pimcore\Model\DataObject\Data\QuantityValue;

class ClassificationStoreHelper
{
    /**
     * @param \Pimcore\Model\DataObject\Classificationstore|null $classificationStore
     *
     * @return array
     */
    public function getClassificationStoreMapped(?Classificationstore $classificationStore): array
    {
        $mappedData = [];

        if ($classificationStore instanceof Classificationstore) {
            foreach ($classificationStore->getItems() as $groupKey => $group) {
                $classificationGroup = GroupConfig::getById($groupKey);
                if ($classificationGroup instanceof GroupConfig) {
                    foreach ($classificationGroup->getRelations() as $keyGroupRelation) {
                        $keyConfig = KeyConfig::getById($keyGroupRelation->getKeyId());
                        $keyGroup = GroupConfig::getById($keyGroupRelation->getGroupId());
                        $mappedData[$keyGroupRelation->getName()] = [
                            'key' => $keyConfig->getName() ?? null,
                            'group' => $keyGroup->getName() ?? null,
                            'config' => $keyConfig,
                            'label' => $keyConfig->getTitle() ?? null,
                            'value' => null,
                        ];
                    }

                    foreach ($group as $key => $item) {
                        $keyConfig = KeyConfig::getById(strtolower($key));

                        if ($keyConfig instanceof KeyConfig && $keyConfig->getType() === 'select') {
                            $definition = json_decode($keyConfig->getDefinition(), true);
                            $defaultValue = array_key_exists('default', $item) ? $item['default'] : null;

                            $mappedData[$keyConfig->getName()] = [
                                'key' => $keyConfig->getName(),
                                'group' => $classificationGroup->getName() ?? null,
                                'config' => $keyConfig,
                                'label' => $keyConfig->getTitle(),
                                'value' => collect($definition['options'])->where('value', $defaultValue)->first()['key'] ?? null,
                                'optionValue' => collect($definition['options'])->where('value', $defaultValue)->first()['value'] ?? null,
                            ];
                        } elseif ($keyConfig instanceof KeyConfig && $keyConfig->getType() === 'numeric') {
                            $mappedData[$keyConfig->getName()] = [
                                'key' => $keyConfig->getName(),
                                'group' => $classificationGroup->getName() ?? null,
                                'config' => $keyConfig,
                                'label' => $keyConfig->getTitle(),
                                'value' => $item['default'],
                            ];
                        } elseif ($keyConfig instanceof KeyConfig && $keyConfig->getType() === 'input') {
                            $mappedData[$keyConfig->getName()] = [
                                'key' => $keyConfig->getName(),
                                'group' => $classificationGroup->getName() ?? null,
                                'config' => $keyConfig,
                                'label' => $keyConfig->getTitle(),
                                'value' => $item['default'],
                            ];
                        } elseif ($keyConfig instanceof KeyConfig && $keyConfig->getType() === 'quantityValue') {
                            if ($item['default']) {
                                $value = $item['default'];
                                $mappedData[$keyConfig->getName()] = [
                                    'key' => $keyConfig->getName(),
                                    'group' => $classificationGroup->getName() ?? null,
                                    'config' => $keyConfig,
                                    'label' => $keyConfig->getTitle(),
                                    'unit' => (!is_string($value) && $item['default']->getUnit()) ? $item['default']->getUnit()->getAbbreviation() : null,
                                    'unitLongname' => ($item['default']->getUnit()) ? $item['default']->getUnit()->getLongName() : null,
                                    'rawValue' => $item['default']->getValue(),
                                    'value' => is_string($value) ? $value : sprintf(
                                        '%s %s',
                                        $item['default']->getValue(),
                                        ($item['default']->getUnit()) ? $item['default']->getUnit()->getAbbreviation() : null
                                    ),
                                ];
                            }
                        } elseif ($keyConfig instanceof KeyConfig && $keyConfig->getType() === 'checkbox') {
                            $mappedData[$keyConfig->getName()] = [
                                'key' => $keyConfig->getName(),
                                'group' => $classificationGroup->getName() ?? null,
                                'config' => $keyConfig,
                                'label' => $keyConfig->getTitle(),
                                'value' => (bool)$item['default'],
                            ];
                        }
                    }
                }
            }
        }

        return $mappedData;
    }

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     * @param array $keyPairsData
     * @param bool $skipPreSave
     *
     * @throws \Pimcore\Model\Element\DuplicateFullPathException
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
                    /** @var \Pimcore\Model\DataObject\Classificationstore\GroupConfig $groupConfig */
                    $groupConfig = $keyPairData['groupConfig'];
                    /** @var \Pimcore\Model\DataObject\Classificationstore\KeyConfig $keyConfig */
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
     * @param \Pimcore\Model\DataObject\Classificationstore $classificationStore
     * @param \Pimcore\Model\DataObject\Classificationstore\GroupConfig[] $activeGroupConfigs
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
