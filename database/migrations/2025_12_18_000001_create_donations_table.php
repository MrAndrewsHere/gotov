<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('charity_project_id')
                ->constrained('charity_projects')
                ->cascadeOnDelete();
            $table->timestamp('donation_date');
            $table->unsignedBigInteger('amount');
            $table->string('comment', 1000)->nullable();
            $table->timestamps();

            $table->index('charity_project_id');
            $table->index('donation_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
