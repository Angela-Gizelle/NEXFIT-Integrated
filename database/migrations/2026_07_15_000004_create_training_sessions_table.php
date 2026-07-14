<?php
// Session Management module — Session Inventory Tracking (REQ-SI-01..07).
//
// Adapted to the base project's schema (base = source of truth on conflicts):
//   - `member_id`         -> base's `members` table (not a second copy)
//   - `trainer_id` /
//     `backup_trainer_id` -> base's `trainers` table, NOT `users`. The base
//                            project keeps trainer profiles in their own
//                            table (specialization, trainer_level, etc.);
//                            `users` is only login accounts.
//   - `level`             -> uses the base's Member.fitness_level values
//                            (Fundamentals / Mid-Level / Advanced) instead
//                            of the 4-value enum the module originally used,
//                            so a session's level always matches a member's
//                            fitness_level exactly.
//   - `booking_id`        -> NEW nullable link back to the Online Booking
//                            module's `bookings` table, so a session that
//                            originated from a public/staff booking can be
//                            traced end-to-end (booking -> conducted session).

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members');
            $table->foreignId('trainer_id')->nullable()->constrained('trainers')->nullOnDelete();
            $table->foreignId('backup_trainer_id')->nullable()->constrained('trainers')->nullOnDelete();
            $table->foreignId('session_package_sale_id')->nullable()->constrained('session_package_sales')->nullOnDelete();
            // Optional traceability back to the Online Booking module (REQ-OB-10..13)
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->nullOnDelete();

            $table->enum('program', ['Pilates', 'Personal Training', 'Open Gym Access']);
            $table->enum('level', ['Fundamentals', 'Mid-Level', 'Advanced'])->nullable();
            $table->date('session_date');
            $table->time('session_time');
            $table->enum('status', [
                'confirmed',
                'conducted',
                'pending',
                'cancelled',
                'no_show',
                'rescheduled',
            ])->default('confirmed');
            $table->text('remarks')->nullable();             // per-session notes: absent, accommodation, etc. (REQ-SI-06)
            $table->timestamp('conducted_at')->nullable();  // when marked conducted
            $table->foreignId('conducted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('cancelled_at')->nullable();
            $table->boolean('credit_restored')->default(false); // track if credit was given back on cancel
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_sessions');
    }
};
