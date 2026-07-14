<?php
// Session Management module (REQ-SP-01..07).
// `member_id` FKs into the base project's existing `members` table
// (created by 2026_07_14_000001_create_members_table) — no separate
// members table is created here.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('session_package_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->string('walkin_name')->nullable();        // for non-member walk-ins (REQ-OB-08)
            $table->foreignId('session_package_id')->constrained('session_packages');
            $table->enum('pricing_type', ['standard', 'student', 'pwd', 'promo'])->default('standard');
            $table->decimal('amount_paid', 10, 2);
            $table->enum('payment_mode', ['cash', 'gcash', 'bank_transfer', 'other']);
            $table->string('reference_number')->nullable();   // for e-wallet / bank refs
            $table->enum('sale_type', ['new_enrollment', 'renewal', 'additional', 'walkin'])->default('new_enrollment');
            $table->foreignId('processed_by')->constrained('users');  // staff who recorded the sale
            $table->date('sale_date');
            $table->time('sale_time');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_package_sales');
    }
};
