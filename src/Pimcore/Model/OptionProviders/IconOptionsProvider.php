<?php

declare(strict_types=1);

namespace App\OpenDxp\Model\OptionProviders;

use OpenDxp\Model\DataObject\ClassDefinition\Data;
use OpenDxp\Model\DataObject\ClassDefinition\DynamicOptionsProvider\SelectOptionsProviderInterface;

class IconOptionsProvider implements SelectOptionsProviderInterface
{
    private const ICONS = [
        'Search',
        'Filter',
        'Droplets',
        'FlaskConical',
        'Waves',
        'ShoppingBag',
        'Tag',
        'Star',
        'Zap',
        'Flame',
        'Leaf',
        'Shield',
        'Package',
        'Truck',
        'Heart',
        'ThumbsUp',
        'Award',
        'Gift',
        'Home',
        'Sparkles',
        'Sun',
        'Thermometer',
        'Wind',
        'Bath',
        'ShowerHead',
        'TrendingUp',
        'Handshake',
        'Check',
        'X',
    ];

    public function getOptions(array $context, Data $fieldDefinition): array
    {
        return array_map(fn(string $icon) => ['key' => $icon, 'value' => $icon], self::ICONS);
    }

    public function hasStaticOptions(array $context, Data $fieldDefinition): bool
    {
        return true;
    }

    public function getDefaultValue(array $context, Data $fieldDefinition): ?string
    {
        return null;
    }
}