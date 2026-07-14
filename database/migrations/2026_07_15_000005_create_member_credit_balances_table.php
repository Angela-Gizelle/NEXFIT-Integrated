<?php
// Session Management module (REQ-SI-03). `member_id` FKs into the
// base project's `members` table.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Aggregate credit balance per member — updated after each sale / session / adjustment
        Schema::create('member_credit_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id')->unique()->constrained('members');
            $table->unsignedSmallInteger('credits_purchased')->default(0);
            $table->unsignedSmallInteger('credits_conducted')->default(0);
            $table->unsignedSmallInteger('credits_remaining')->default(0);
            $table->unsignedSmallInteger('credits_forfeited')->default(0); // expired unused credits
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_credit_balances');
    }
};
