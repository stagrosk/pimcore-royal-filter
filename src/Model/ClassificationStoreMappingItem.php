<?php

namespace App\Model;

use Pimcore\Model\DataObject\Classificationstore\GroupConfig;
use Pimcore\Model\DataObject\Classificationstore\KeyConfig;

class ClassificationStoreMappingItem
{
    /**
     * @param \Pimcore\Model\DataObject\Classificationstore\KeyConfig $keyConfig
     * @param \Pimcore\Model\DataObject\Classificationstore\GroupConfig $groupConfig
     * @param string $label
     * @param string|bool|null $value
     * @param int|float|string|null $rawValue
     * @param string|null $unit
     * @param string|null $unitLongName
     * @param string|null $optionValue
     */
    public function __construct(
        private KeyConfig             $keyConfig,
        private GroupConfig           $groupConfig,
        private string                $label,
        private string|bool|null      $value,
        private int|float|string|null $rawValue = null,
        private ?string               $unit = null,
        private ?string               $unitLongName = null,
        private ?string               $optionValue = null,
    ) {
    }

    public function getKeyConfig(): KeyConfig
    {
        return $this->keyConfig;
    }

    public function setKeyConfig(KeyConfig $keyConfig): void
    {
        $this->keyConfig = $keyConfig;
    }

    public function getGroupConfig(): GroupConfig
    {
        return $this->groupConfig;
    }

    public function setGroupConfig(GroupConfig $groupConfig): void
    {
        $this->groupConfig = $groupConfig;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function getValue(): bool|string|null
    {
        return $this->value;
    }

    public function setValue(bool|string|null $value): void
    {
        $this->value = $value;
    }

    public function getRawValue(): int|float|string
    {
        return $this->rawValue ?? 0;
    }

    public function setRawValue(int|float|string|null $rawValue): void
    {
        $this->rawValue = $rawValue;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): void
    {
        $this->unit = $unit;
    }

    public function getUnitLongName(): ?string
    {
        return $this->unitLongName;
    }

    public function setUnitLongName(?string $unitLongName): void
    {
        $this->unitLongName = $unitLongName;
    }

    public function getOptionValue(): ?string
    {
        return $this->optionValue;
    }

    public function setOptionValue(?string $optionValue): void
    {
        $this->optionValue = $optionValue;
    }
}
