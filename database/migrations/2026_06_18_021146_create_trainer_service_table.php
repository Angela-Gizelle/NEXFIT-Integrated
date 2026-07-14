<?php
// NEW FILE: database/migrations/2026_06_18_021146_create_trainer_service_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainer_service', function (Blueprint $table) {

            $table->foreignId('trainer_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('service_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->primary(['trainer_id', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainer_service');
    }
};
