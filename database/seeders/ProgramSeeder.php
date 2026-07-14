<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        Program::create([
            'name' => 'Beginner Fitness Program',
            'level' => 'Beginner',
            'description' => 'Basic fitness training for new members.'
        ]);

        Program::create([
            'name' => 'Strength and Conditioning',
            'level' => 'Intermediate',
            'description' => 'Workout program for members with exercise experience.'
        ]);

        Program::create([
            'name' => 'Performance Training',
            'level' => 'Advanced',
            'description' => 'Advanced performance-based training.'
        ]);
    }
}