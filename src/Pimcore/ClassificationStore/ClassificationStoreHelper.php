<?php

namespace App\Pimcore\ClassificationStore;

use App\Model\ClassificationStoreMapping;
use App\Model\ClassificationStoreMappingItem;
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

                    // TODO: check what is this
//                    foreach ($groupConfig->getRelations() as $keyGroupRelation) {
//                        $keyConfig = KeyConfig::getById($keyGroupRelation->getKeyId());
//                        $groupConfig = GroupConfig::getById($keyGroupRelation->getGroupId());
//                        $classificationStoreMapping[$keyGroupRelation->getName()] = [
//                            'key' => $keyConfig->getName() ?? null,
//                            'group' => $groupConfig->getName(),
//                            'config' => $keyConfig,
//                            'label' => $keyConfig->getTitle() ?? null,
//                            'value' => null,
//                        ];
//                    }

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
                                    null,
                                    null,
                                    null,
                                    collect($definition['options'])->where('value', $defaultValue)->first()['value'] ?? null
                                );
                                $classificationStoreMapping->addItem($classificationStoreMappingItem);
                                break;

                            case 'numeric':
                            case 'textarea':
                            case 'input':
                            case 'booleanSelect':
                                $classificationStoreMappingItem = new ClassificationStoreMappingItem(
                                    $keyConfig,
                                    $groupConfig,
                                    $keyConfig->getTitle(),
                                    $item['default'],
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
                                $classificationStoreMappingItem = new ClassificationStoreMappingItem(
                                    $keyConfig,
                                    $groupConfig,
                                    $keyConfig->getTitle(),
                                    (bool)$item['default'],
                                );
                                $classificationStoreMapping->addItem($classificationStoreMappingItem);
                                break;

                            case 'rgbaColor':
                                /** @var \Pimcore\Model\DataObject\Data\RgbaColor $rgbaColor */
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
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     * @param array $keyPairsData
     * @param bool $skipPreSave
     *
     * @throws \Pimcore\Model\Element\DuplicateFullPathException
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
