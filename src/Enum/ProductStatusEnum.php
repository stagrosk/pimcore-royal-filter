<?php

declare(strict_types=1);

namespace App\Enum;

enum ProductStatusEnum: string
{
    case ACTIVE = 'active';
    case DRAFT = 'draft';
    case ARCHIVED = 'archived';
}