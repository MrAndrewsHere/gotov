<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\CharityProject;
use App\Models\Donation;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class DonationTriggerTest extends TestCase
{
    public function test_trigger_increases_donation_amount_on_insert(): void
    {
        $project = CharityProject::factory()->create([
            'donation_amount' => 0,
        ]);

        $this->assertEquals(0, $project->donation_amount->getAmount());

        Donation::factory()->forProject($project)->create([
            'amount' => 100.50,
        ]);

        $project->refresh();
        $this->assertEquals(10050, $project->donation_amount->getAmount());
    }

    public function test_trigger_decreases_donation_amount_on_delete(): void
    {
        $project = CharityProject::factory()->active()->create([
            'donation_amount' => 0,
        ]);

        $donation = Donation::factory()->forProject($project)->create([
            'amount' => 100.50,
        ]);

        $project->refresh();
        $this->assertEquals(10050, $project->donation_amount->getAmount());

        $donation->delete();

        $project->refresh();
        $this->assertEquals(0, $project->donation_amount->getAmount());
    }

    public function test_trigger_updates_donation_amount_on_update(): void
    {
        $project = CharityProject::factory()->active()->create([
            'donation_amount' => 0,
        ]);

        $donation = Donation::factory()->forProject($project)->create([
            'amount' => 100.00,
        ]);

        $project->refresh();
        $this->assertEquals(10000, $project->donation_amount->getAmount());

        $donation->update(['amount' => 250.00]);

        $project->refresh();
        $this->assertEquals(25000, $project->donation_amount->getAmount());
    }

    public function test_trigger_handles_multiple_donations(): void
    {
        $project = CharityProject::factory()->active()->create([
            'donation_amount' => 0,
        ]);

        Donation::factory()->forProject($project)->create([
            'amount' => 100.00,
        ]);

        Donation::factory()->forProject($project)->create([
            'amount' => 250.00,
        ]);

        Donation::factory()->forProject($project)->create([
            'amount' => 150.00,
        ]);

        $project->refresh();
        $this->assertEquals(50000, $project->donation_amount->getAmount());
    }

    public function test_trigger_works_with_direct_sql_insert(): void
    {
        $project = CharityProject::factory()->active()->create([
            'donation_amount' => 0,
        ]);

        DB::insert(
            'INSERT INTO donations (charity_project_id, amount, donation_date, created_at, updated_at) VALUES (?, ?, NOW(),NOW(), NOW())',
            [$project->id, 50000]
        );

        $project->refresh();
        $this->assertEquals(50000, $project->donation_amount->getAmount());
    }
}
