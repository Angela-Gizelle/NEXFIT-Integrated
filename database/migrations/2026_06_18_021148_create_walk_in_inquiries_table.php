<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('walk_in_inquiries', function (Blueprint $table) {

            $table->id();

            // Nullable — walk-ins can happen without a prior assessment
            $table->foreignId('fitness_assessment_id')
                  ->nullable()
                  ->constrained('fitness_assessments')
                  ->nullOnDelete();

            $table->string('full_name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('interested_in')->nullable();
            $table->text('notes')->nullable();

            // Staff follow-up tracking
            $table->boolean('followed_up')->default(false);
            $table->timestamp('followed_up_at')->nullable();
            $table->string('followed_up_by')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('walk_in_inquiries');
    }
};
