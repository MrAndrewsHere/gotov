<?php

declare(strict_types=1);

namespace App\Services\Exceptions;

use Illuminate\Validation\ValidationException;

class CannotDonateToProjectException extends ValidationException
{
    public static function make(array $messages = [])
    {
        return parent::withMessages([
            'charity_project_id' => 'Жертвовать в черновик проектов нельзя',
        ]);
    }
}
