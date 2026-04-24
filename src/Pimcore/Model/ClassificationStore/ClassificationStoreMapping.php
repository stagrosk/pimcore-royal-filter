<?php

namespace App\OpenDxp\Model\ClassificationStore;

class ClassificationStoreMapping
{
    /**
     * @param ClassificationStoreMappingItem[] $classificationStoreMappingItems
     */
    public function __construct(
        private array $classificationStoreMappingItems = []
    ) {
    }

    /**
     * @return \App\OpenDxp\Model\ClassificationStore\ClassificationStoreMappingItem[]
     */
    public function getClassificationStoreMappingItems(): array
    {
        return $this->classificationStoreMappingItems;
    }

    /**
     * @param array $classificationStoreMappingItems
     *
     * @return void
     */
    public function setClassificationStoreMappingItems(array $classificationStoreMappingItems): void
    {
        $this->classificationStoreMappingItems = $classificationStoreMappingItems;
    }

    /**
     * @param \App\OpenDxp\Model\ClassificationStore\ClassificationStoreMappingItem $classificationStoreMappingItem
     *
     * @return void
     */
    public function addItem(ClassificationStoreMappingItem $classificationStoreMappingItem): void
    {
        $this->classificationStoreMappingItems[] = $classificationStoreMappingItem;
    }

    /**
     * @param string $groupConfigName
     * @param string $keyConfigName
     *
     * @return \App\OpenDxp\Model\ClassificationStore\ClassificationStoreMappingItem|null
     */
    public function findItemByKeyConfigName(string $groupConfigName, string $keyConfigName): ?ClassificationStoreMappingItem
    {
        foreach ($this->classificationStoreMappingItems as $classificationStoreMappingItem) {
            if ($classificationStoreMappingItem->getGroupConfig()->getName() === $groupConfigName
                && $classificationStoreMappingItem->getKeyConfig()->getName() === $keyConfigName) {
                return $classificationStoreMappingItem;
            }
        }

        return null;
    }

    /**
     * @return array
     */
    public function getMappedParametersIndexedByGroupAndKey(): array
    {
        $data = [];
        foreach ($this->classificationStoreMappingItems as $classificationStoreMappingItem) {
            $groupConfig = $classificationStoreMappingItem->getGroupConfig();
            $keyConfig = $classificationStoreMappingItem->getKeyConfig();

            $data[$groupConfig->getName()][$keyConfig->getName()] = $classificationStoreMappingItem->getValue();
        }

        return $data;
    }
}