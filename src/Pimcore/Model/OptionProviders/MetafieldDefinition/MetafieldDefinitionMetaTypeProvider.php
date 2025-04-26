<?php

namespace App\Pimcore\Model\OptionProviders\MetafieldDefinition;

use App\Shopify\Model\Metafields\MetafieldMetaTypeEnum;
use Pimcore\Model\DataObject\ClassDefinition\Data;
use Pimcore\Model\DataObject\ClassDefinition\DynamicOptionsProvider\SelectOptionsProviderInterface;

class MetafieldDefinitionMetaTypeProvider implements SelectOptionsProviderInterface
{
    public function getOptions(array $context, Data $fieldDefinition): array
    {
        $result = [];
        $metaTypes = MetafieldMetaTypeEnum::getValues();
        foreach ($metaTypes as $value) {
            $result[] = [
                'key' => $value,
                'value' => $value,
            ];
        }

        return $result;
    }

    /**
     * Returns the value which is defined in the 'Default value' field
     */
    public function getDefaultValue(array $context, Data $fieldDefinition): ?string
    {
        if (method_exists($fieldDefinition, 'getDefaultValue')) {
            return $fieldDefinition->getDefaultValue();
        }

        return null;
    }

    public function hasStaticOptions(array $context, Data $fieldDefinition): bool
    {
        return true;
    }
}
