<?php

declare(strict_types=1);

namespace App\OpenDxp\Model\OptionProviders;

use OpenDxp\Model\DataObject\ClassDefinition\Data;
use OpenDxp\Model\DataObject\ClassDefinition\DynamicOptionsProvider\SelectOptionsProviderInterface;

/**
 * Backed by assets/icons/catalog.json (generated from FE inventory).
 * Stored value is the Iconify ID (lucide:..., ri:...) so the admin combo
 * renders previews via the iconify-icon web component.
 */
class IconOptionsProvider implements SelectOptionsProviderInterface
{
    private const CATALOG_PATH = __DIR__ . '/../../../../assets/icons/catalog.json';

    /** @var array<int, array{key: string, value: string}>|null */
    private static ?array $cachedOptions = null;

    public function getOptions(array $context, Data $fieldDefinition): array
    {
        if (self::$cachedOptions !== null) {
            return self::$cachedOptions;
        }

        self::$cachedOptions = $this->loadCatalog();

        return self::$cachedOptions;
    }

    public function hasStaticOptions(array $context, Data $fieldDefinition): bool
    {
        return true;
    }

    public function getDefaultValue(array $context, Data $fieldDefinition): ?string
    {
        return null;
    }

    /**
     * @return array<int, array{key: string, value: string}>
     */
    private function loadCatalog(): array
    {
        $path = realpath(self::CATALOG_PATH);
        if ($path === false || !is_readable($path)) {
            return [];
        }

        $raw = file_get_contents($path);
        if ($raw === false) {
            return [];
        }

        $data = json_decode($raw, true);
        if (!is_array($data) || !isset($data['icons']) || !is_array($data['icons'])) {
            return [];
        }

        $options = [];
        foreach ($data['icons'] as $icon) {
            if (!is_array($icon) || empty($icon['id']) || empty($icon['label'])) {
                continue;
            }

            $options[] = [
                'key' => $icon['label'],
                'value' => $icon['id'],
            ];
        }

        return $options;
    }
}
