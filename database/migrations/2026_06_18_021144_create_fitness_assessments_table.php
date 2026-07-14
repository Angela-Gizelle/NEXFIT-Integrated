<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fitness_assessments', function (Blueprint $table) {

            $table->id();

            $table->string('full_name');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();

            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_number')->nullable();
            $table->string('emergency_contact_relationship')->nullable();

            $table->enum('exercise_experience', [
                'beginner', 'intermediate', 'advanced'
            ])->default('beginner');

            $table->boolean('currently_exercising')->default(false);
            $table->string('exercise_frequency')->nullable();
            $table->json('exercise_type')->nullable();
            $table->string('exercise_other')->nullable();

            // 'Personal Training' | 'Pilates' | 'Open Gym Access'
            $table->string('interested_in')->nullable();

            // Single fitness goal — drives trainer recommendation
            $table->string('fitness_goal')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fitness_assessments');
    }
};
