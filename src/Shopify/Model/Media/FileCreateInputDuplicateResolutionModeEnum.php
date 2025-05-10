<?php

namespace App\Shopify\Model\Media;

enum FileCreateInputDuplicateResolutionModeEnum: string
{
    case APPEND_UUID = 'APPEND_UUID';
    case RAISE_ERROR = 'RAISE_ERROR';
    case REPLACE = 'REPLACE';
}
