<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class GetCharityProjectsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['nullable', (new Enum(ProjectStatus::class))->except([ProjectStatus::DRAFT])],
            'launch_date_from' => 'nullable|date',
            'launch_date_to' => 'nullable|date',
            'sort_by' => 'nullable|in:sort_order,launch_date,donation_amount',
            'order' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:1',
            'page' => 'nullable|integer|min:1',
        ];
    }

    public function defaults(): array
    {
        return [
            'status' => 'active',
            'sort_by' => 'sort_order',
            'order' => 'asc,desc',
            'per_page' => 3,
            'page' => 1,
        ];
    }

    public function getPerPage(): int
    {
        $perPage = $this->integer('per_page', 3);

        return min($perPage, 10);
    }

    public function getSortBy(): string
    {
        return $this->input('sort_by', 'sort_order');
    }

    public function getStatus(): ProjectStatus
    {
        return ProjectStatus::tryFrom($this->input('status', ProjectStatus::ACTIVE->value)) ?? ProjectStatus::ACTIVE;
    }

    public function getLaunchDateFrom(): ?string
    {
        return $this->date('launch_date_from');
    }

    public function getLaunchDateTo(): ?string
    {
        return $this->date('launch_date_to');
    }
}
