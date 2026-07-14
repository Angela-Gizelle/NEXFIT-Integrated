<?php
// REPLACE: database/migrations/2026_06_18_021147_create_bookings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {

            $table->id();

            // Public flow: linked to assessment, not a member account
            $table->foreignId('fitness_assessment_id')
                  ->constrained('fitness_assessments')
                  ->cascadeOnDelete();

            $table->foreignId('service_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // NULL for Open Gym
            $table->foreignId('trainer_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            // Backup trainer for conflict prevention
            $table->foreignId('backup_trainer_id')
                  ->nullable()
                  ->constrained('trainers')
                  ->nullOnDelete();

            // NULL for Open Gym
            $table->foreignId('program_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            // The exact slot reserved
            $table->foreignId('schedule_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->date('booking_date');
            $table->time('booking_time');

            $table->enum('status', [
                'Pending',
                'Confirmed',
                'Cancelled',
                'Rescheduled',
                'Walk-in',
            ])->default('Pending');

            // Policy acknowledgments (required before confirmation)
            $table->boolean('medical_clearance_acknowledged')->default(false);
            $table->boolean('rights_policy_acknowledged')->default(false);
            $table->boolean('member_declaration_signed')->default(false);

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
