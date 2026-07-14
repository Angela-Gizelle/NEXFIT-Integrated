<?php
// REPLACE: database/migrations/2026_06_18_021145_create_programs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {

            $table->id();

            // Which service this program belongs to
            $table->foreignId('service_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('name');

            // Used by recommendation engine to match member's fitness level
            $table->enum('level', ['Beginner', 'Intermediate', 'Advanced'])
                  ->default('Beginner');

            // Mirrors service name — used to filter via assessment->interested_in
            $table->string('type')->nullable();

            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
