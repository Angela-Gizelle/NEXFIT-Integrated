<?php
// Session Management module (4.3.3.2 Session Package Sales catalog).
// New table — no naming conflict with the base project.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');                                    // e.g. "10-Session Pilates Package"
            $table->string('type')->nullable();                        // single, monthly, 3-month, 6-month, annual, student, pwd (REQ-MM-03)
            // Aligned to the base project's `services.name` values so the two
            // catalogs read the same way across modules.
            $table->enum('program', ['Pilates', 'Personal Training', 'Open Gym Access']);
            $table->unsignedSmallInteger('session_credits');           // credits granted on purchase (REQ-SI-01)
            $table->unsignedSmallInteger('validity_days')->nullable(); // null = no expiry (TBD-01)
            $table->decimal('base_price', 10, 2);
            $table->decimal('student_price', 10, 2)->nullable();
            $table->decimal('pwd_price', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_packages');
    }
};
