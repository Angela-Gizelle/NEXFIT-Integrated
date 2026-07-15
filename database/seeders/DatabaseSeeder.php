<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\Program;
use App\Models\Trainer;
use App\Models\Schedule;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. SERVICES ──────────────────────────────────────────

        $pt = Service::updateOrCreate(
            ['name' => 'Personal Training'],
            [
                'description'      => 'One-on-one personal training sessions.',
                'open_time'        => '05:00:00',
                'close_time'       => '22:00:00',
                'requires_trainer' => true,
                'requires_program' => true,
            ]
        );

        $pilates = Service::updateOrCreate(
            ['name' => 'Pilates'],
            [
                'description'      => 'Mat and reformer pilates classes.',
                'open_time'        => '05:00:00',
                'close_time'       => '22:00:00',
                'requires_trainer' => true,
                'requires_program' => true,
            ]
        );

        $openGym = Service::updateOrCreate(
            ['name' => 'Open Gym Access'],
            [
                'description'      => 'Unrestricted gym floor access.',
                'open_time'        => '05:00:00',
                'close_time'       => '22:00:00',
                'requires_trainer' => false,
                'requires_program' => false,
            ]
        );

        // ── 2. PROGRAMS ───────────────────────────────────────────

        $programs = [

            [
                'service_id' => $pt->id,
                'name' => 'Muscle Building Program',
                'level' => 'Intermediate',
                'type' => 'Personal Training',
                'description' => 'Hypertrophy-focused strength training.',
            ],

            [
                'service_id' => $pt->id,
                'name' => 'Strength & Conditioning Program',
                'level' => 'Intermediate',
                'type' => 'Personal Training',
                'description' => 'Compound lifts and athletic conditioning.',
            ],

            [
                'service_id' => $pt->id,
                'name' => 'Athletic Performance Program',
                'level' => 'Advanced',
                'type' => 'Personal Training',
                'description' => 'Sport-specific performance coaching.',
            ],

            [
                'service_id' => $pt->id,
                'name' => 'Weight Loss & Lifestyle Program',
                'level' => 'Beginner',
                'type' => 'Personal Training',
                'description' => 'Cardio and nutrition for fat loss.',
            ],

            [
                'service_id' => $pt->id,
                'name' => 'Flexibility & Mobility Program',
                'level' => 'Beginner',
                'type' => 'Personal Training',
                'description' => 'Stretch, mobility, and recovery work.',
            ],

            [
                'service_id' => $pt->id,
                'name' => 'Posture & Core Strength Program',
                'level' => 'Beginner',
                'type' => 'Personal Training',
                'description' => 'Core stability and postural correction.',
            ],

            [
                'service_id' => $pilates->id,
                'name' => 'Beginner Pilates',
                'level' => 'Beginner',
                'type' => 'Pilates',
                'description' => 'Introduction to mat pilates.',
            ],

            [
                'service_id' => $pilates->id,
                'name' => 'Reformer Pilates',
                'level' => 'Intermediate',
                'type' => 'Pilates',
                'description' => 'Machine-assisted pilates for core strength.',
            ],

            [
                'service_id' => $pilates->id,
                'name' => 'Mobility & Flexibility Pilates',
                'level' => 'Advanced',
                'type' => 'Pilates',
                'description' => 'Deep stretch and advanced mobility work.',
            ],

        ];

        foreach ($programs as $program) {

            Program::updateOrCreate(

                [
                    'service_id' => $program['service_id'],
                    'name' => $program['name'],
                ],

                [
                    'level' => $program['level'],
                    'type' => $program['type'],
                    'description' => $program['description'],
                ]

            );
        }

        // ── 3. TRAINERS ───────────────────────────────────────────

        $adrian = Trainer::updateOrCreate(

            ['email' => 'adrian@fiturban.com'],

            [
                'name' => 'Coach Adrian Reyes',
                'specialization' => 'Strength & Conditioning Coach',
                'trainer_level' => 'Intermediate',
                'is_available' => true,
                'is_active' => true,
            ]

        );

        $camille = Trainer::updateOrCreate(

            ['email' => 'camille@fiturban.com'],

            [
                'name' => 'Coach Camille Santos',
                'specialization' => 'Nutrition & Lifestyle Coach',
                'trainer_level' => 'Beginner',
                'is_available' => true,
                'is_active' => true,
            ]

        );

        $marco = Trainer::updateOrCreate(

            ['email' => 'marco@fiturban.com'],

            [
                'name' => 'Coach Marco Dela Cruz',
                'specialization' => 'Muscle Building Coach',
                'trainer_level' => 'Intermediate',
                'is_available' => true,
                'is_active' => true,
            ]

        );

        $ethan = Trainer::updateOrCreate(

            ['email' => 'ethan@fiturban.com'],

            [
                'name' => 'Coach Ethan Villanueva',
                'specialization' => 'Performance Coach',
                'trainer_level' => 'Advanced',
                'is_available' => true,
                'is_active' => true,
            ]

        );

        $sophia = Trainer::updateOrCreate(

            ['email' => 'sophia@fiturban.com'],

            [
                'name' => 'Coach Sophia Mendoza',
                'specialization' => 'Wellness & Group Classes Coach',
                'trainer_level' => 'Beginner',
                'is_available' => true,
                'is_active' => true,
            ]

        );

        // Attach trainers safely

        $adrian->services()->syncWithoutDetaching([$pt->id]);

        $camille->services()->syncWithoutDetaching([
            $pt->id,
            $pilates->id,
        ]);

        $marco->services()->syncWithoutDetaching([$pt->id]);

        $ethan->services()->syncWithoutDetaching([$pt->id]);

        $sophia->services()->syncWithoutDetaching([$pilates->id]);

        // ── 4. SCHEDULES (next 30 days, real weekly availability) ─
        // 0=Sun, 1=Mon, 2=Tue, 3=Wed, 4=Thu, 5=Fri, 6=Sat
        $weekly = [
            1 => [ // Monday
                [$adrian,  $pt,     '05:00', '13:00'],
                [$ethan,   $pt,     '06:00', '14:00'],
                [$camille, $pt,     '13:00', '21:00'],
                [$sophia,  $pilates, '09:00', '17:00'],
            ],
            2 => [ // Tuesday
                [$adrian,  $pt,     '05:00', '13:00'],
                [$ethan,   $pt,     '06:00', '14:00'],
                [$marco,   $pt,     '14:00', '22:00'],
                [$sophia,  $pilates, '09:00', '17:00'],
            ],
            3 => [ // Wednesday
                [$camille, $pt,     '13:00', '21:00'],
                [$marco,   $pt,     '14:00', '22:00'],
                [$camille, $pilates, '09:00', '17:00'],
            ],
            4 => [ // Thursday
                [$adrian,  $pt,     '05:00', '13:00'],
                [$ethan,   $pt,     '06:00', '14:00'],
                [$camille, $pt,     '13:00', '21:00'],
                [$sophia,  $pilates, '09:00', '17:00'],
            ],
            5 => [ // Friday
                [$adrian,  $pt,     '05:00', '13:00'],
                [$marco,   $pt,     '14:00', '22:00'],
                [$sophia,  $pilates, '09:00', '17:00'],
            ],
            6 => [ // Saturday
                [$ethan,   $pt,     '06:00', '14:00'],
                [$camille, $pt,     '13:00', '21:00'],
                [$marco,   $pt,     '14:00', '22:00'],
                [$camille, $pilates, '09:00', '17:00'],
            ],
            0 => [ // Sunday
                [$adrian,  $pt,     '05:00', '13:00'],
                [$ethan,   $pt,     '06:00', '14:00'],
                [$camille, $pt,     '13:00', '21:00'],
                [$marco,   $pt,     '14:00', '22:00'],
                [$sophia,  $pilates, '09:00', '17:00'],
            ],
        ];

               for ($day = 0; $day < 30; $day++) {
            $date    = now()->addDays($day);
            $dateStr = $date->toDateString();
            $dow     = (int) $date->format('w');

            if (!isset($weekly[$dow])) {
                continue;
            }

            foreach ($weekly[$dow] as [$trainer, $service, $start, $end]) {
                Schedule::firstOrCreate(
                    [
                        'trainer_id' => $trainer->id,
                        'date'       => $dateStr,
                        'start_time' => $start . ':00',
                    ],
                    [
                        'service_id'   => $service->id,
                        'end_time'     => $end . ':00',
                        'max_capacity' => $service->name === 'Pilates' ? 10 : 1,
                        'booked_count' => 0,
                        'is_full'      => false,
                        'is_active'    => true,
                    ]
                );
            }
        }

        // ── 5. STAFF / ADMIN LOGIN ACCOUNTS ────────────────────────
        $this->call([
            StaffSeeder::class,
        ]);
    }
}