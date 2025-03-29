<?php

namespace App\Pimcore\ClassificationStore;

use App\Pimcore\Helpers\InheritanceHelper;
use App\Pimcore\Helpers\VersionHelper;
use Pimcore\Logger;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Classificationstore;
use Pimcore\Model\DataObject\Data\QuantityValue;

class ClassificationStoreHelper
{
    /**
     * @param \Pimcore\Model\DataObject\Whirlpool $object
     * @param array $keyPairsData
     *
     * @throws \Exception
     * @return void
     */
    public function fillObjectDataOnClassificationStore(
        AbstractObject $object,
        array $keyPairsData
    ): void {
        Logger::notice(' Fill data on object classification store. Id: ' . $object->getId() . ' Class: '. $object->getClassName());

        // set product classification store data
        InheritanceHelper::useInheritedValues(function () use ($object, $keyPairsData) {
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
                VersionHelper::useVersioning(function () use ($object) {
                    $object->save();
                    Logger::notice('            -> object SAVE');
                }, false);
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
