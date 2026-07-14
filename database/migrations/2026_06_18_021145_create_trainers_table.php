<?php
// REPLACE: database/migrations/2026_06_18_021146_create_trainers_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainers', function (Blueprint $table) {

            $table->id();

            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();

            // 'Personal Training' | 'Pilates' | 'Both'
            $table->string('specialization')->nullable();

            // Matched against member's exercise_experience level
            $table->enum('trainer_level', ['Beginner', 'Intermediate', 'Advanced'])
                  ->default('Beginner');

            $table->boolean('is_available')->default(true);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainers');
    }
};
