<?php
// NEW FILE: database/migrations/2026_07_14_000003_add_member_fields_to_parq_responses_table.php
//
// Schema conflict resolution (per instructions, landing.zip is the basis):
// landing's `parq_responses` table is keyed to `fitness_assessment_id`
// (guest booking flow) and uses these column names:
//   heart_condition, chest_pain_activity, chest_pain_rest,
//   dizziness_balance, bone_joint_condition, blood_pressure_medication,
//   other_medical_reason, medical_clearance_required
//
// The membership module's `parq_responses` table used a different shape
// keyed to `member_id` with its own column names (has_heart_condition,
// on_medication, assessment_date, etc). Rather than keeping two divergent
// parq_responses tables, we extend landing's table so members can also
// submit/reassess ParQ from their dashboard, using landing's column
// names as the source of truth.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('parq_responses', function (Blueprint $table) {
            // A member's reassessment isn't tied to a guest booking flow,
            // so the original fitness_assessment_id must become optional.
            $table->foreignId('fitness_assessment_id')->nullable()->change();

            $table->foreignId('member_id')
                  ->nullable()
                  ->after('fitness_assessment_id')
                  ->constrained('members')
                  ->cascadeOnDelete();

            $table->date('assessment_date')->nullable()->after('member_id');
            $table->text('additional_notes')->nullable()->after('medical_clearance_required');
            $table->unsignedBigInteger('assessed_by')->nullable()->after('additional_notes');
        });
    }

    public function down(): void
    {
        Schema::table('parq_responses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('member_id');
            $table->dropColumn(['assessment_date', 'additional_notes', 'assessed_by']);
            $table->foreignId('fitness_assessment_id')->nullable(false)->change();
        });
    }
};
