<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreCharityProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:128|unique:charity_projects,slug',
            'short_description' => 'required|string|max:5000',
            'status' => ['required', new Enum(ProjectStatus::class)],
            'launch_date' => 'required|date',
            'additional_description' => 'nullable|string|max:50000',
            'sort_order' => 'nullable|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Наименование проекта обязательно',
            'name.max' => 'Наименование не должно превышать 255 символов',
            'slug.required' => 'Slug обязателен',
            'slug.unique' => 'Этот slug уже используется',
            'slug.max' => 'Slug не должен превышать 128 символов',
            'short_description.required' => 'Краткое описание обязательно',
            'short_description.max' => 'Краткое описание не должно превышать 5000 символов',
            'status.required' => 'Статус обязателен',
            'status.in' => 'Статус должен быть: draft, active или closed',
            'launch_date.required' => 'Дата запуска обязательна',
            'launch_date.date' => 'Дата запуска должна быть корректной датой',
            'additional_description.max' => 'Дополнительное описание не должно превышать 50000 символов',
            'sort_order.integer' => 'Порядок сортировки должен быть целым числом',
            'sort_order.min' => 'Порядок сортировки не может быть меньше 1',
        ];
    }

    public function getStatus(): ProjectStatus
    {
        $statusValue = $this->input('status', ProjectStatus::DRAFT->value);

        return ProjectStatus::tryFrom($statusValue) ?? ProjectStatus::DRAFT;
    }
}
