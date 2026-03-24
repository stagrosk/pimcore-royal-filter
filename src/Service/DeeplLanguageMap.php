<?php

declare(strict_types=1);

namespace App\Service;

final class DeeplLanguageMap
{
    private const MAP = [
        'en' => 'EN-GB',
        'pt' => 'PT-PT',
    ];

    public static function resolve(string $locale): string
    {
        return self::MAP[$locale] ?? strtoupper(substr($locale, 0, 2));
    }
}
