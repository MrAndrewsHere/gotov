<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\CreateProjectDTO;
use App\DTOs\ProjectIndexDTO;
use App\Enums\ProjectStatus;
use App\Models\CharityProject;
use App\Services\Interfaces\CharityProjectServiceInterface;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Stevebauman\Purify\Facades\Purify;

class CharityProjectService implements CharityProjectServiceInterface
{
    public function getProjects(
        ProjectIndexDTO $dto
    ): Paginator {
        $query = $this->buildQuery($dto->status, $dto->launchDateFrom, $dto->launchDateTo);

        $this->applySorting($query, $dto->sortBy);

        return $query->paginate(perPage: $dto->perPage, page: $dto->page)->withQueryString();
    }

    public function getProjectBySlug(string $slug): ?CharityProject
    {
        return CharityProject::query()
            ->activeOrClosed()
            ->slugIs($slug)
            ->firstOrFail();
    }

    public function createProject(
        CreateProjectDTO $dto
    ): CharityProject {
        return CharityProject::create([
            'name' => trim($dto->name),
            'slug' => $this->generateUniqueSlug(Str::slug($dto->name)),
            'short_description' => Purify::clean($dto->shortDescription),
            'additional_description' => Purify::clean($dto->additionalDescription),
            'status' => $dto->status,
            'launch_date' => $dto->launchDate ?? now(),
            'donation_amount' => 0,
            'sort_order' => $dto->sortOrder,
        ]);
    }

    private function buildQuery(
        ?ProjectStatus $status,
        ?string $launchDateFrom,
        ?string $launchDateTo,
    ): Builder {
        $query = CharityProject::query();

        if ($status instanceof ProjectStatus) {
            $query->status($status);
        } else {
            $query->where('status', '!=', ProjectStatus::DRAFT->value);
        }

        if ($launchDateFrom) {
            $query->whereDate('launch_date', '>=', $launchDateFrom);
        }

        if ($launchDateTo) {
            $query->whereDate('launch_date', '<=', $launchDateTo);
        }

        return $query;
    }

    private function applySorting(Builder $query, string $sortBy): void
    {
        match ($sortBy) {
            'launch_date' => $query->orderBy('launch_date', 'desc'),
            'donation_amount' => $query->orderBy('donation_amount', 'desc'),
            default => $query->orderBy('sort_order', 'asc')
                ->orderBy('launch_date', 'desc'),
        };
    }

    private function generateUniqueSlug(string $baseSlug): string
    {
        $slug = $baseSlug;
        $counter = 1;

        while (CharityProject::isSlugExists($slug)) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
