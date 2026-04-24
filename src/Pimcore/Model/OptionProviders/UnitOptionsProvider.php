<?php

declare(strict_types=1);

namespace App\OpenDxp\Model\OptionProviders;

use OpenDxp\Model\DataObject\ClassDefinition\Data;
use OpenDxp\Model\DataObject\ClassDefinition\DynamicOptionsProvider\SelectOptionsProviderInterface;
use OpenDxp\Model\DataObject\QuantityValue\Unit;

class UnitOptionsProvider implements SelectOptionsProviderInterface
{
    public function getOptions(array $context, Data $fieldDefinition): array
    {
        $list = new Unit\Listing();
        $list->setOrderKey('abbreviation');
        $list->setOrder('ASC');

        $options = [];
        foreach ($list->getUnits() as $unit) {
            $label = $unit->getAbbreviation();
            if ($unit->getLongname()) {
                $label = sprintf('%s (%s)', $unit->getAbbreviation(), $unit->getLongname());
            }

            $options[] = [
                'key' => $label,
                'value' => $unit->getId(),
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
