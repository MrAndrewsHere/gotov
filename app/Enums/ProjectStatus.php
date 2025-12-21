<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Traits\HasBaseEnum;

enum ProjectStatus: string
{
    use HasBaseEnum;

    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case CLOSED = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Черновик',
            self::ACTIVE => 'Активный',
            self::CLOSED => 'Закрытый',
        };
    }

    public static function default(): self
    {
        return self::DRAFT;
    }
}
