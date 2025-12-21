<?php

declare(strict_types=1);

namespace App\Services\Exceptions;

use Illuminate\Validation\ValidationException;

class FutureDonationDateException extends ValidationException
{
    public static function make(array $messages = [])
    {
        return parent::withMessages([
            'donation_date' => 'Дата пожертвования не может быть в будущем',
        ]);
    }
}
