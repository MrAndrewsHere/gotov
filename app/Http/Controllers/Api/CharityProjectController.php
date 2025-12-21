<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\DTOs\ProjectIndexDTO;
use App\Http\Requests\GetCharityProjectsRequest;
use App\Http\Resources\CharityProjectDetailResource;
use App\Http\Resources\CharityProjectResource;
use App\Services\Interfaces\CharityProjectServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class CharityProjectController
{
    public function __construct(
        private readonly CharityProjectServiceInterface $charityProjectService,
    ) {}

    public function index(GetCharityProjectsRequest $request): AnonymousResourceCollection
    {
        $projects = $this->charityProjectService->getProjects(
            dto: ProjectIndexDTO::fromRequest($request),
        );

        return CharityProjectResource::collection($projects);
    }

    public function show(string $slug): JsonResource
    {
        return new CharityProjectDetailResource($this->charityProjectService->getProjectBySlug($slug));
    }
}
