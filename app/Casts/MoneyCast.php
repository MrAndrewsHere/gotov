<?php

declare(strict_types=1);

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Money\Money;

final class MoneyCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): Money
    {
        return MoneyTransform::get($value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): int
    {
        return MoneyTransform::set($value);
    }
}
