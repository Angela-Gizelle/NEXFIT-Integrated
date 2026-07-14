<?php
// NEW FILE: database/migrations/2026_07_14_000002_create_member_packages_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // REQ-MM-02 & REQ-MM-03: membership packages (sessions availed lives here)
        Schema::create('member_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->constrained()->onDelete('cascade');
            $table->enum('package_type', [
                'Single Session',
                'Monthly',
                '3-Month',
                '6-Month',
                'Annual',
                'Student Rate',
                'PWD Rate',
            ]);
            $table->date('purchase_date');
            $table->date('coverage_start');
            $table->date('coverage_end');
            $table->integer('session_credits'); // total credits given
            $table->integer('credits_used')->default(0);
            $table->integer('credits_remaining')->virtualAs('session_credits - credits_used');
            $table->decimal('amount_paid', 10, 2);
            $table->string('payment_mode')->default('Cash'); // recorded for reference only
            $table->unsignedBigInteger('processed_by')->nullable(); // staff id
            $table->enum('status', ['Active', 'Expired'])->default('Active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_packages');
    }
};
