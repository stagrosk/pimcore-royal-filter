<?php

declare(strict_types=1);

namespace App\Pimcore\DataObject\Calculator;

use App\Pimcore\ClassificationStore\ClassificationStoreHelper;
use App\Pimcore\Model\ClassificationStore\ClassificationStoreMappingItem;
use App\Service\ClassificationStoreTranslationService;
use Pimcore\Model\DataObject\ClassDefinition\CalculatorClassInterface;
use Pimcore\Model\DataObject\Concrete;
use Pimcore\Model\DataObject\Data\CalculatedValue;
use Pimcore\Model\DataObject\Product;

class ParametersConfigCalculator implements CalculatorClassInterface
{
    private ?ClassificationStoreTranslationService $translationService = null;

    /**
     * @return ClassificationStoreTranslationService
     */
    private function getTranslationService(): ClassificationStoreTranslationService
    {
        if ($this->translationService === null) {
            $this->translationService = \Pimcore::getContainer()->get(ClassificationStoreTranslationService::class);
        }

        return $this->translationService;
    }

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
        $translationService = $this->getTranslationService();

        // Get translations from Pimcore shared translations (auto-creates if missing)
        // Note: GroupConfig doesn't have getTitle(), use getDescription() instead
        $groupTranslations = $translationService->getGroupTranslations(
            $groupConfig->getName(),
            $groupConfig->getDescription() ?: $groupConfig->getName()
        );

        $keyTranslations = $translationService->getKeyTranslations(
            $keyConfig->getName(),
            $keyConfig->getTitle() ?: $keyConfig->getName()
        );

        return [
            'group' => $groupConfig->getName(),
            'groupId' => $groupConfig->getId(),
            'groupTranslations' => $groupTranslations,
            'key' => $keyConfig->getName(),
            'keyId' => $keyConfig->getId(),
            'keyTranslations' => $keyTranslations,
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