<?php

declare(strict_types=1);

namespace App\Pimcore\DataObject\Calculator;

use App\Pimcore\ClassificationStore\ClassificationStoreHelper;
use App\Pimcore\Model\ClassificationStore\ClassificationStoreMappingItem;
use Pimcore\Model\DataObject\ClassDefinition\CalculatorClassInterface;
use Pimcore\Model\DataObject\Concrete;
use Pimcore\Model\DataObject\Data\CalculatedValue;
use Pimcore\Model\DataObject\Product;

class ParametersConfigCalculator implements CalculatorClassInterface
{
    /**
     * @param Concrete $object
     * @param CalculatedValue $context
     *
     * @return string
     */
    public function compute(Concrete $object, CalculatedValue $context): string
    {
        return $this->getParametersConfig($object);
    }

    /**
     * @param Concrete $object
     * @param CalculatedValue $context
     *
     * @return string
     */
    public function getCalculatedValueForEditMode(Concrete $object, CalculatedValue $context): string
    {
        return $this->getParametersConfig($object);
    }

    /**
     * @param Concrete $object
     * @param bool $jsonEncode
     *
     * @return string|array
     */
    public function getParametersConfig(Concrete $object, bool $jsonEncode = true): array|string
    {
        if (!$object instanceof Product) {
            return $jsonEncode ? '[]' : [];
        }

        $classificationStoreHelper = new ClassificationStoreHelper();
        $mapping = $classificationStoreHelper->getClassificationStoreMapped($object->getMetadata());
        $items = $mapping->getClassificationStoreMappingItems();

        $parameters = [];
        foreach ($items as $item) {
            $parameters[] = $this->mapItem($item);
        }

        if ($jsonEncode) {
            return json_encode($parameters, JSON_THROW_ON_ERROR);
        }

        return $parameters;
    }

    /**
     * @param ClassificationStoreMappingItem $item
     *
     * @return array
     */
    private function mapItem(ClassificationStoreMappingItem $item): array
    {
        $keyConfig = $item->getKeyConfig();
        $groupConfig = $item->getGroupConfig();

        return [
            'group' => $groupConfig->getName(),
            'groupId' => $groupConfig->getId(),
            'key' => $keyConfig->getName(),
            'keyId' => $keyConfig->getId(),
            'label' => $item->getLabel(),
            'type' => $keyConfig->getType(),
            'value' => $item->getValue(),
            'rawValue' => $item->getRawValue(),
            'unit' => $item->getUnit(),
            'unitLongName' => $item->getUnitLongName(),
            'optionValue' => $item->getOptionValue(),
        ];
    }
}