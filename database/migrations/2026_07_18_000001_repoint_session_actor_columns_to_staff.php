<?php
// Session Management module — integration fix.
//
// `training_sessions.conducted_by` / `cancelled_by`, `session_package_sales.processed_by`,
// and `session_credit_adjustments.adjusted_by` were created FK'd to `users`.
// In this codebase, staff/admin log in through the separate `staff` guard/table
// (see config/auth.php + Staff model), not `users`. Repointing these columns
// is required for the module to actually record who performed each action.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('training_sessions', function (Blueprint $table) {
            $table->dropForeign(['conducted_by']);
            $table->dropForeign(['cancelled_by']);
        });
        Schema::table('training_sessions', function (Blueprint $table) {
            $table->foreign('conducted_by')->references('staff_id')->on('staff')->nullOnDelete();
            $table->foreign('cancelled_by')->references('staff_id')->on('staff')->nullOnDelete();
        });

        Schema::table('session_package_sales', function (Blueprint $table) {
            $table->dropForeign(['processed_by']);
        });
        Schema::table('session_package_sales', function (Blueprint $table) {
            $table->foreign('processed_by')->references('staff_id')->on('staff')->restrictOnDelete();
        });

        Schema::table('session_credit_adjustments', function (Blueprint $table) {
            $table->dropForeign(['adjusted_by']);
        });
        Schema::table('session_credit_adjustments', function (Blueprint $table) {
            $table->foreign('adjusted_by')->references('staff_id')->on('staff')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('training_sessions', function (Blueprint $table) {
            $table->dropForeign(['conducted_by']);
            $table->dropForeign(['cancelled_by']);
            $table->foreign('conducted_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('cancelled_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('session_package_sales', function (Blueprint $table) {
            $table->dropForeign(['processed_by']);
            $table->foreign('processed_by')->references('id')->on('users');
        });

        Schema::table('session_credit_adjustments', function (Blueprint $table) {
            $table->dropForeign(['adjusted_by']);
            $table->foreign('adjusted_by')->references('id')->on('users');
        });
    }
};
