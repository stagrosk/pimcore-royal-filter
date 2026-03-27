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

    // source_lang only accepts 2-char codes (no region variant)
    public static function resolveSource(string $locale): string
    {
        return strtoupper(substr($locale, 0, 2));
    }
}
