<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CharityProject;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $factory = fn () => CharityProject::factory()->hasDonations(random_int(0, 5));

        $factory()->active()->count(100)
            ->create();

        $factory()->closed()->count(50)
            ->create();

        $factory()->draft()->count(20)
            ->create();
    }
}
