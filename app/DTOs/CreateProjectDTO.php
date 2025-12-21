<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Enums\ProjectStatus;
use Carbon\Carbon;
use Spatie\LaravelData\Data;

class CreateProjectDTO extends Data
{
    public function __construct(
        public string $name,
        public ?string $shortDescription = null,
        public ?string $additionalDescription = null,
        public ProjectStatus $status = ProjectStatus::DRAFT,
        public ?Carbon $launchDate = null,
        public int $sortOrder = 1000000,
    ) {}
}
