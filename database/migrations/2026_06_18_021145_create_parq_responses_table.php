<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parq_responses', function (Blueprint $table) {

            $table->id();

            $table->foreignId('fitness_assessment_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->boolean('heart_condition')->default(false);
            $table->boolean('chest_pain_activity')->default(false);
            $table->boolean('chest_pain_rest')->default(false);
            $table->boolean('dizziness_balance')->default(false);
            $table->boolean('bone_joint_condition')->default(false);
            $table->boolean('blood_pressure_medication')->default(false);
            $table->boolean('other_medical_reason')->default(false);

            $table->boolean('medical_clearance_required')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parq_responses');
    }
};