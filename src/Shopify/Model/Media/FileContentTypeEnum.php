<?php

namespace App\Shopify\Model\Media;

enum FileContentTypeEnum: string
{
    case IMAGE = 'IMAGE';
    case VIDEO = 'VIDEO';
    case AUDIO = 'AUDIO';
    case TEXT = 'TEXT';
    case APPLICATION = 'APPLICATION';
    case FONT = 'FONT';
    case MODEL = 'MODEL';
}
