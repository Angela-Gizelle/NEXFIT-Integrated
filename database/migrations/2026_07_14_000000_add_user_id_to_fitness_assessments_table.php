<?php
// NEW FILE: database/migrations/2026_07_14_000000_add_user_id_to_fitness_assessments_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fitness_assessments', function (Blueprint $table) {
            // Nullable — guests booking without an account still work fine.
            // When a logged-in member books, we stamp their user_id so it
            // can be pulled onto their dashboard.
            $table->foreignId('user_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('fitness_assessments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
