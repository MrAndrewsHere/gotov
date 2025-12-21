<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ProjectStatus;
use App\Models\CharityProject;
use Carbon\Carbon;
use Tests\TestCase;

final class CharityProjectApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        CharityProject::create([
            'name' => 'Active Project 1',
            'slug' => 'active-project-1',
            'short_description' => 'Description 1',
            'status' => ProjectStatus::ACTIVE->value,
            'launch_date' => Carbon::now()->subDays(30),
            'sort_order' => 100,
        ]);

        CharityProject::create([
            'name' => 'Active Project 2',
            'slug' => 'active-project-2',
            'short_description' => 'Description 2',
            'status' => ProjectStatus::ACTIVE->value,
            'launch_date' => Carbon::now()->subDays(10),
            'sort_order' => 200,
        ]);

        CharityProject::create([
            'name' => 'Closed Project',
            'slug' => 'closed-project',
            'short_description' => 'Description 3',
            'status' => ProjectStatus::CLOSED->value,
            'launch_date' => Carbon::now()->subDays(60),
        ]);

        CharityProject::create([
            'name' => 'Draft Project',
            'slug' => 'draft-project',
            'short_description' => 'Description 4',
            'status' => ProjectStatus::DRAFT->value,
            'launch_date' => Carbon::now(),
        ]);
    }

    public function test_get_projects_returns_only_active_by_default(): void
    {
        $response = $this->getJson(route('projects.index'));

        $response->assertStatus(200);
        $response->assertJsonMissing(['status' => ProjectStatus::DRAFT->toArray()]);
        $response->assertJsonMissing(['status' => ProjectStatus::CLOSED->toArray()]);
    }

    public function test_get_projects_with_closed_status(): void
    {
        $response = $this->getJson(route('projects.index', ['status' => 'closed']));

        $response->assertStatus(200);
        $response->assertJsonFragment(['status' => ProjectStatus::CLOSED->toArray()]);
    }

    public function test_get_projects_respects_per_page_limit(): void
    {
        $response = $this->getJson(route('projects.index', ['per_page' => 1]));

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    }

    public function test_get_project_by_slug(): void
    {
        $response = $this->getJson(route('projects.show', ['slug' => 'active-project-1']));

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'slug' => 'active-project-1',
            'name' => 'Active Project 1',
        ]);
    }

    public function test_get_project_by_slug_returns_404_for_draft(): void
    {
        $response = $this->getJson(route('projects.show', ['slug' => 'draft-project']));

        $response->assertStatus(404);
    }

    public function test_get_project_by_slug_returns_closed_project(): void
    {
        $response = $this->getJson(route('projects.show', ['slug' => 'closed-project']));

        $response->assertStatus(200);
        $response->assertJsonFragment(['slug' => 'closed-project']);
    }

    public function test_get_project_by_slug_not_found(): void
    {
        $response = $this->getJson(route('projects.show', ['slug' => 'non-existent']));

        $response->assertStatus(404);
    }
}
