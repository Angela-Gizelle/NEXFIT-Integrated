<?php
// REPLACE: database/migrations/2026_06_18_021154_create_audit_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {

            $table->id();

            // NULL for public (non-logged-in) actions
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->string('auditable_type');       // e.g. 'Booking'
            $table->unsignedBigInteger('auditable_id');

            // 'created' | 'updated' | 'cancelled' | 'confirmed' | 'rescheduled'
            $table->string('action');

            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            $table->string('ip_address')->nullable();

            $table->timestamps();

            $table->index(['auditable_type', 'auditable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
