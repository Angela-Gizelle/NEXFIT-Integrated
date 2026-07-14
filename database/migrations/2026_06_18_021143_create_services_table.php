<?php
// REPLACE: database/migrations/2026_06_19_035513_create_services_table.php
// DELETE:  database/migrations/2026_06_19_053202_create_services_table.php  ← duplicate, remove it

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {

            $table->id();

            $table->string('name')->unique(); // 'Personal Training' | 'Pilates' | 'Open Gym Access'
            $table->text('description')->nullable();

            $table->time('open_time')->default('05:00:00');   // Studio opens 5 AM
            $table->time('close_time')->default('22:00:00');  // Studio closes 10 PM

            $table->boolean('requires_trainer')->default(true);
            $table->boolean('requires_program')->default(true);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
