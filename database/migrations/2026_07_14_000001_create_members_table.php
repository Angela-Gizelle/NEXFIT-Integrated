<?php
// NEW FILE: database/migrations/2026_07_14_000001_create_members_table.php
//
// Brings the Membership module's schema into the landing app.
// Landing's schema is the basis for any conflicts:
//   - `trainers` already exists (richer columns) — we simply FK into it,
//     we do NOT recreate a slimmer `trainers` table like the membership
//     module's own migration did.
//   - We add a nullable `user_id` so a member can be linked to the
//     account they log in with, which is what drives the dashboard
//     redirect after login.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // REQ-MM-01: member profile
        Schema::create('members', function (Blueprint $table) {
            $table->id();

            // Links this membership record to the account the person logs
            // in with. Nullable because staff can enroll a walk-in member
            // before that person ever creates a login.
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->date('birthdate')->nullable();
            $table->string('address')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            $table->date('enrollment_date');
            $table->enum('fitness_level', ['Fundamentals', 'Mid-Level', 'Advanced'])->default('Fundamentals');
            // REQ-MM-06: General or Special Population
            $table->enum('population_class', ['General', 'Special'])->default('General');
            // REQ-MM-01: member status
            $table->enum('status', ['Active', 'Inactive', 'Churned'])->default('Active');

            // FK into landing's existing `trainers` table.
            $table->foreignId('assigned_trainer_id')
                  ->nullable()
                  ->constrained('trainers')
                  ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes(); // REQ SR-2: soft delete only per RA 10173
        });

        // REQ-MM-07: Special Population flags
        Schema::create('member_health_flags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->string('condition'); // e.g. hypertension, pregnancy, injury
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_health_flags');
        Schema::dropIfExists('members');
    }
};
