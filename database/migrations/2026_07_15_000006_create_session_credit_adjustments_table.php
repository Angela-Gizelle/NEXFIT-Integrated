<?php
// Session Management module (REQ-SI-05). Immutable audit log —
// `member_id` FKs into the base project's `members` table.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_credit_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained('members');
            $table->integer('adjustment_amount');             // positive = add, negative = deduct
            $table->unsignedSmallInteger('credits_before');
            $table->unsignedSmallInteger('credits_after');
            $table->string('reason');                         // mandatory reason (REQ-SI-05)
            $table->foreignId('adjusted_by')->constrained('users'); // admin id
            $table->timestamp('adjusted_at')->useCurrent();
            // No softDeletes — this table is immutable by design
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_credit_adjustments');
    }
};
