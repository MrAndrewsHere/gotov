<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\ProjectStatus;
use App\Models\CharityProject;
use App\Models\Donation;
use Carbon\Carbon;
use Tests\TestCase;

final class DonationApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        CharityProject::create([
            'name' => 'Active Project',
            'slug' => 'active-project',
            'short_description' => 'Description',
            'status' => ProjectStatus::ACTIVE->value,
            'launch_date' => Carbon::now(),
        ]);

        CharityProject::create([
            'name' => 'Draft Project',
            'slug' => 'draft-project',
            'short_description' => 'Description',
            'status' => ProjectStatus::DRAFT->value,
            'launch_date' => Carbon::now(),
        ]);

        CharityProject::create([
            'name' => 'Closed Project',
            'slug' => 'closed-project',
            'short_description' => 'Description',
            'status' => ProjectStatus::CLOSED->value,
            'launch_date' => Carbon::now(),
        ]);
    }

    public function test_create_donation(): void
    {
        $project = CharityProject::where('slug', 'active-project')->first();

        $response = $this->postJson(route('donations.store'), [
            'charity_project_id' => $project->id,
            'amount' => 100.50,
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'charity_project_id' => $project->id,
        ]);

        $this->assertDatabaseHas('donations', [
            'charity_project_id' => $project->id,
            'amount' => 10050,
        ]);
    }

    public function test_cannot_donate_to_draft_project(): void
    {
        $project = CharityProject::where('slug', 'draft-project')->first();

        $response = $this->postJson(route('donations.store'), [
            'charity_project_id' => $project->id,
            'amount' => 100.00,
        ]);

        $response->assertStatus(422);
    }

    public function test_cannot_donate_to_non_existent_project(): void
    {
        $response = $this->postJson(route('donations.store'), [
            'charity_project_id' => 99999,
            'amount' => 100.00,
        ]);

        $response->assertStatus(422);
    }

    public function test_cannot_donate_with_future_date(): void
    {
        $project = CharityProject::where('slug', 'active-project')->first();
        $futureDate = Carbon::now()->addDays(5)->format('Y-m-d H:i:s');

        $response = $this->postJson(route('donations.store'), [
            'charity_project_id' => $project->id,
            'amount' => 100.00,
            'donation_date' => $futureDate,
        ]);

        $response->assertStatus(422);
    }

    public function test_cannot_donate_with_zero_amount(): void
    {
        $project = CharityProject::where('slug', 'active-project')->first();

        $response = $this->postJson(route('donations.store'), [
            'charity_project_id' => $project->id,
            'amount' => 0,
        ]);

        $response->assertStatus(422);
    }

    public function test_can_donate_to_closed_project(): void
    {
        $project = CharityProject::where('slug', 'closed-project')->first();

        $response = $this->postJson(route('donations.store'), [
            'charity_project_id' => $project->id,
            'amount' => 100.00,
        ]);

        $response->assertStatus(201);
    }

    public function test_donation_date_defaults_to_now(): void
    {
        $project = CharityProject::where('slug', 'active-project')->first();

        $response = $this->postJson(route('donations.store'), [
            'charity_project_id' => $project->id,
            'amount' => 100.00,
        ]);

        $response->assertStatus(201);

        $donation = Donation::latest()->first();

        $this->assertLessThanOrEqual(60, $donation->donation_date->diffInSeconds(Carbon::now()));
    }
}
