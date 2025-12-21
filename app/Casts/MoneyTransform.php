<?php

declare(strict_types=1);

namespace App\Casts;

use InvalidArgumentException;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

class MoneyTransform
{
    public static function get(mixed $value): Money
    {
        return Money::RUB((int) $value);
    }

    public static function set(mixed $value): int
    {
        return (int) static::parse($value)->getAmount();
    }

    public static function parse(mixed $value): Money
    {
        if ($value instanceof Money) {
            return $value;
        }

        if (is_int($value)) {
            return static::get($value);
        }

        if (is_float($value) || is_string($value)) {
            $currencies = new ISOCurrencies;
            $moneyParser = new DecimalMoneyParser($currencies);

            return $moneyParser->parse((string) $value, new Currency('RUB'));
        }

        throw new InvalidArgumentException('Cannot parse amount to Money instance.');
    }
}
