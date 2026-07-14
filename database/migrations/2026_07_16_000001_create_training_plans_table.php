<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// NOTE: this connects the standalone "AI training plan" module into the
// merged NexFit schema. It intentionally does NOT touch the existing
// `users` or `trainers` tables — it only adds a new table that points
// at them via foreign keys, so it works with whatever trainers/members
// already exist in the merged database.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('trainer_id')->constrained()->cascadeOnDelete();
            $table->json('plan_content'); // title, pre_bullets, post_bullets, raw_content, etc.
            $table->boolean('is_current')->default(true);
            $table->string('generated_by')->default('gemini'); // gemini | default-fallback
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_plans');
    }
};
