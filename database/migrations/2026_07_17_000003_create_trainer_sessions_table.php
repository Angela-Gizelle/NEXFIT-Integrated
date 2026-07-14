<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // One row = one bookable trainer session on the weekly scheduling
        // calendar. A row only exists once a slot has actually been
        // booked — empty/available slots are computed on the fly in
        // SchedulingController and never touch this table.
        //
        // Named "trainer_sessions" (not "sessions") to avoid colliding
        // with Laravel's own database session-driver table.
        Schema::create('trainer_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('trainer_id')
                  ->constrained('trainers')
                  ->onDelete('cascade');

            // Nullable so staff can block a trainer's slot (e.g. leave,
            // admin task) without attaching it to a member.
            $table->foreignId('member_id')
                  ->nullable()
                  ->constrained('members')
                  ->onDelete('cascade');

            $table->date('session_date');
            $table->time('start_time');
            $table->time('end_time');

            $table->enum('status', ['booked', 'completed', 'cancelled', 'no_show'])
                  ->default('booked');

            $table->string('notes', 500)->nullable();

            $table->timestamps();

            // A trainer can't be double-booked in the same slot.
            $table->unique(['trainer_id', 'session_date', 'start_time'], 'trainer_sessions_slot_unique');

            $table->index(['session_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainer_sessions');
    }
};
