<?php

namespace App\Shopify\Model\Metafields;

enum MetafieldMetaTypeEnum: string
{
    case BOOLEAN = 'BOOLEAN';
    case COLOR = 'COLOR';
    case DATE = 'DATE';
    case DATE_TIME = 'DATE_TIME';
    case DIMENSION = 'DIMENSION';
    case ID = 'ID';
    case JSON = 'JSON';
    case LINK = 'LINK';
    case MONEY = 'MONEY';
    case RATING = 'RATING';
//    case RICH_TEXT_FIELD = 'RICH_TEXT_FIELD';
    case SINGLE_LINE_TEXT_FIELD = 'SINGLE_LINE_TEXT_FIELD';
    case MULTI_LINE_TEXT_FIELD = 'MULTI_LINE_TEXT_FIELD';
    case NUMBER_DECIMAL = 'NUMBER_DECIMAL';
    case NUMBER_INTEGER = 'NUMBER_INTEGER';
    case URL = 'URL';
    case VOLUME = 'VOLUME';
    case WEIGHT = 'WEIGHT';

    public static function getValues(): array
    {
        $values = [];
        foreach (self::cases() as $case) {
            $values[] = $case->value;
        }
        return $values;
    }
}
