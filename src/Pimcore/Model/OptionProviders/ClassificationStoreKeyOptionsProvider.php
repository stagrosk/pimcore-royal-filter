<?php

declare(strict_types=1);

namespace App\Pimcore\Model\OptionProviders;

use OpenDxp\Model\DataObject\ClassDefinition\Data;
use OpenDxp\Model\DataObject\ClassDefinition\DynamicOptionsProvider\SelectOptionsProviderInterface;
use OpenDxp\Model\DataObject\Classificationstore\KeyConfig;

class ClassificationStoreKeyOptionsProvider implements SelectOptionsProviderInterface
{
    public function getOptions(array $context, Data $fieldDefinition): array
    {
        $list = new KeyConfig\Listing();
        $list->setOrderKey('name');
        $list->setOrder('ASC');

        $options = [];
        foreach ($list->getList() as $key) {
            $options[] = [
                'key' => sprintf('%s (ID: %d)', $key->getName(), $key->getId()),
                'value' => (string) $key->getId(),
            ];
        }

        return $options;
    }

    public function hasStaticOptions(array $context, Data $fieldDefinition): bool
    {
        return false;
    }

    public function getDefaultValue(array $context, Data $fieldDefinition): ?string
    {
        return null;
    }
}
