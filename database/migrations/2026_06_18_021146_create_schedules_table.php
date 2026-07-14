<?php
// REPLACE: database/migrations/2026_06_18_021146_create_schedules_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {

            $table->id();

            // NULL for open gym slots (no trainer assigned)
            $table->foreignId('trainer_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->foreignId('service_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');

            // 1 for personal training, 6+ for pilates classes
            $table->unsignedInteger('max_capacity')->default(1);

            // Incremented on each booking, decremented on cancel
            $table->unsignedInteger('booked_count')->default(0);

            // Set to TRUE when booked_count >= max_capacity
            $table->boolean('is_full')->default(false);

            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Prevent double-booking the same trainer at the same time
            $table->unique(['trainer_id', 'date', 'start_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
