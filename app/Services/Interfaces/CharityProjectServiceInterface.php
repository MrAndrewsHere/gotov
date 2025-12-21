<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

use App\DTOs\CreateProjectDTO;
use App\DTOs\ProjectIndexDTO;
use App\Models\CharityProject;
use Illuminate\Contracts\Pagination\Paginator;

interface CharityProjectServiceInterface
{
    public function getProjects(ProjectIndexDTO $dto): Paginator;

    public function getProjectBySlug(string $slug): ?CharityProject;

    public function createProject(CreateProjectDTO $dto): CharityProject;
}
