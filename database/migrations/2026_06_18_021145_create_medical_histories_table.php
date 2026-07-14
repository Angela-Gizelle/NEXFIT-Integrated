<?php
// REPLACE: database/migrations/2026_06_18_021145_create_medical_histories_table.php
// ALSO DELETE:
//   2026_06_18_034852_add_medical_history_fields_to_fitness_assessments_table.php
//   2026_06_18_090703_add_fitness_assessment_id_to_medical_histories_table.php
// (both are now covered here)

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_histories', function (Blueprint $table) {

            $table->id();

            // Links directly to the assessment — no login required
            $table->foreignId('fitness_assessment_id')
                  ->constrained('fitness_assessments')
                  ->cascadeOnDelete();

            // ── Medical History fields ───────────────────────────
          $table->boolean('chronic_illness')->nullable()->default(false);
            $table->text('chronic_illness_details')->nullable();

          $table->boolean('major_surgery')->nullable()->default(false);
            $table->text('major_surgery_details')->nullable();

         $table->boolean('current_medications')->nullable()->default(false);
            $table->string('medication_name')->nullable();

            // Mirrors 'interested_in' on assessment for quick queries
            $table->string('interested_in')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_histories');
    }
};
