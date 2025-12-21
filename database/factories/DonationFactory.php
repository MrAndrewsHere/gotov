<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CharityProject;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'charity_project_id' => CharityProject::factory(),
            'donation_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'amount' => $this->faker->randomNumber(),
            // 'amount' => $this->faker->randomFloat(2, 0, 1000000),
            'comment' => $this->faker->optional(0.5)->text(500),
        ];
    }

    public function forProject(CharityProject $project): static
    {
        return $this->state(fn (array $attributes): array => [
            'charity_project_id' => $project->id,
        ]);
    }
}
