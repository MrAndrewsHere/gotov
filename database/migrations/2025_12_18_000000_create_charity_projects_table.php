<?php

declare(strict_types=1);

use App\Enums\ProjectStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('charity_projects', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 255);
            $table->string('slug', 128)->unique();
            $table->text('short_description');
            $table->string('status', 20)->default(ProjectStatus::default()->value);
            $table->timestamp('launch_date');
            $table->text('additional_description')->nullable();
            $table->unsignedBigInteger('donation_amount')->default(0);
            $table->integer('sort_order')->default(1000000);
            $table->timestamps();

            $table->index('slug');
            $table->index('status');
            $table->index('launch_date');
            $table->index('sort_order');
            $table->index('donation_amount');
        });

        DB::statement('CREATE INDEX idx_sort_order_launch_date ON charity_projects (sort_order ASC, launch_date DESC)');
    }

    public function down(): void
    {
        Schema::dropIfExists('charity_projects');
    }
};
