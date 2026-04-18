<?php

declare(strict_types=1);

namespace App\Pimcore\Model\OptionProviders;

use Pimcore\Model\DataObject\ClassDefinition\Data;
use Pimcore\Model\DataObject\ClassDefinition\DynamicOptionsProvider\SelectOptionsProviderInterface;
use Pimcore\Model\DataObject\Classificationstore\GroupConfig;

class ClassificationStoreGroupOptionsProvider implements SelectOptionsProviderInterface
{
    public function getOptions(array $context, Data $fieldDefinition): array
    {
        $list = new GroupConfig\Listing();
        $list->setOrderKey('name');
        $list->setOrder('ASC');

        $options = [];
        foreach ($list->getList() as $group) {
            $options[] = [
                'key' => sprintf('%s (ID: %d)', $group->getName(), $group->getId()),
                'value' => (string) $group->getId(),
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
