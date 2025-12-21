<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Donation;
use Illuminate\Database\Eloquent\Factories\Factory;

class CharityProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'slug' => $this->faker->unique()->slug(),
            'short_description' => $this->faker->paragraphs(2, true),
            'status' => $this->faker->randomElement(ProjectStatus::values()),
            'launch_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'additional_description' => $this->faker->paragraphs(5, true),
            'donation_amount' => 0,
            'sort_order' => $this->faker->numberBetween(1, 1000000),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ProjectStatus::ACTIVE->value,
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ProjectStatus::DRAFT->value,
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ProjectStatus::CLOSED->value,
        ]);
    }

    public function hasDonations(int $count = 5, ?callable $callback = null): static
    {
        return $this->has(
            Donation::factory()
                ->count($count)
                ->when($callback, fn ($factory) => $factory->state($callback)),
            'donations'
        );
    }
}
