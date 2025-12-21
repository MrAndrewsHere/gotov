<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Enums\ProjectStatus;
use App\Http\Requests\GetCharityProjectsRequest;
use Carbon\Carbon;
use Spatie\LaravelData\Data;

class ProjectIndexDTO extends Data
{
    public function __construct(
        public ?ProjectStatus $status = null,
        public ?Carbon $launchDateFrom = null,
        public ?Carbon $launchDateTo = null,
        public string $sortBy = 'sort_order',
        public int $perPage = 3,
        public int $page = 1,
    ) {}

    public static function fromRequest(GetCharityProjectsRequest $request): self
    {
        return new self(
            status: $request->getStatus(),
            launchDateFrom: $request->getLaunchDateFrom(),
            launchDateTo: $request->getLaunchDateTo(),
            sortBy: $request->getSortBy(),
            perPage: $request->getPerPage() ?? 3,
            page: $request->page ?? 1,
        );
    }
}
