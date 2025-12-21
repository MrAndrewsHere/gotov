<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'charity_project_id' => 'required|exists:charity_projects,id',
            'amount' => 'required|numeric|min:0.01',
            'donation_date' => 'nullable|date|before_or_equal:now',
            'comment' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'charity_project_id.required' => 'ID проекта обязателен',
            'charity_project_id.exists' => 'Проект не найден',
            'amount.required' => 'Сумма пожертвования обязательна',
            'amount.numeric' => 'Сумма должна быть числом',
            'amount.min' => 'Сумма должна быть больше 0',
            'donation_date.date' => 'Дата пожертвования должна быть корректной датой',
            'donation_date.before_or_equal' => 'Дата пожертвования не может быть в будущем',
            'comment.max' => 'Комментарий не должен превышать 1000 символов',
        ];
    }
}
