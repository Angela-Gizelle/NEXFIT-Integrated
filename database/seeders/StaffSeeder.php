<?php

namespace Database\Seeders;

use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Predefined Admin + Staff accounts for Fit Urban Fitness Studio.
     * Uses updateOrCreate so re-running the seeder is safe and won't
     * duplicate rows or wipe out a changed password/role.
     */
    public function run(): void
    {
        Staff::updateOrCreate(
            ['email' => 'admin@fiturban-demo.com'],
            [
                'full_name'    => 'Studio Administrator',
                'password'     => Hash::make('Admin@2026'),
                'role'         => 'Admin',
                'contact_info' => null,
            ]
        );

        Staff::updateOrCreate(
            ['email' => 'staff@fiturban-demo.com'],
            [
                'full_name'    => 'Studio Staff',
                'password'     => Hash::make('Staff@2026'),
                'role'         => 'Staff',
                'contact_info' => null,
            ]
        );
    }
}
