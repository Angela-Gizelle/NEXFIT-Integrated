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
        $pt = Service::create([
            'name'             => 'Personal Training',
            'description'      => 'One-on-one personal training sessions.',
            'open_time'        => '05:00:00',
            'close_time'       => '22:00:00',
            'requires_trainer' => true,
            'requires_program' => true,
        ]);

        $pilates = Service::create([
            'name'             => 'Pilates',
            'description'      => 'Mat and reformer pilates classes.',
            'open_time'        => '05:00:00',
            'close_time'       => '22:00:00',
            'requires_trainer' => true,
            'requires_program' => true,
        ]);

        Service::create([
            'name'             => 'Open Gym Access',
            'description'      => 'Unrestricted gym floor access.',
            'open_time'        => '05:00:00',
            'close_time'       => '22:00:00',
            'requires_trainer' => false,
            'requires_program' => false,
        ]);

        // ── 2. PROGRAMS ───────────────────────────────────────────
        Program::insert([
            // Personal Training — one program per fitness goal
            ['service_id' => $pt->id, 'name' => 'Muscle Building Program',           'level' => 'Intermediate', 'type' => 'Personal Training', 'description' => 'Hypertrophy-focused strength training.',      'created_at' => now(), 'updated_at' => now()],
            ['service_id' => $pt->id, 'name' => 'Strength & Conditioning Program',   'level' => 'Intermediate', 'type' => 'Personal Training', 'description' => 'Compound lifts and athletic conditioning.',    'created_at' => now(), 'updated_at' => now()],
            ['service_id' => $pt->id, 'name' => 'Athletic Performance Program',      'level' => 'Advanced',     'type' => 'Personal Training', 'description' => 'Sport-specific performance coaching.',         'created_at' => now(), 'updated_at' => now()],
            ['service_id' => $pt->id, 'name' => 'Weight Loss & Lifestyle Program',   'level' => 'Beginner',     'type' => 'Personal Training', 'description' => 'Cardio and nutrition for fat loss.',           'created_at' => now(), 'updated_at' => now()],
            ['service_id' => $pt->id, 'name' => 'Flexibility & Mobility Program',    'level' => 'Beginner',     'type' => 'Personal Training', 'description' => 'Stretch, mobility, and recovery work.',        'created_at' => now(), 'updated_at' => now()],
            ['service_id' => $pt->id, 'name' => 'Posture & Core Strength Program',   'level' => 'Beginner',     'type' => 'Personal Training', 'description' => 'Core stability and postural correction.',      'created_at' => now(), 'updated_at' => now()],
            // Pilates
            ['service_id' => $pilates->id, 'name' => 'Beginner Pilates',              'level' => 'Beginner',     'type' => 'Pilates', 'description' => 'Introduction to mat pilates.',                        'created_at' => now(), 'updated_at' => now()],
            ['service_id' => $pilates->id, 'name' => 'Reformer Pilates',              'level' => 'Intermediate', 'type' => 'Pilates', 'description' => 'Machine-assisted pilates for core strength.',         'created_at' => now(), 'updated_at' => now()],
            ['service_id' => $pilates->id, 'name' => 'Mobility & Flexibility Pilates','level' => 'Advanced',     'type' => 'Pilates', 'description' => 'Deep stretch and advanced mobility work.',            'created_at' => now(), 'updated_at' => now()],
        ]);

        // ── 3. TRAINERS ───────────────────────────────────────────
        $adrian = Trainer::create([
            'name'           => 'Coach Adrian Reyes',
            'email'          => 'adrian@fiturban.com',
            'specialization' => 'Strength & Conditioning Coach',
            'trainer_level'  => 'Intermediate',
            'is_available'   => true,
            'is_active'      => true,
        ]);

        $camille = Trainer::create([
            'name'           => 'Coach Camille Santos',
            'email'          => 'camille@fiturban.com',
            'specialization' => 'Nutrition & Lifestyle Coach',
            'trainer_level'  => 'Beginner',
            'is_available'   => true,
            'is_active'      => true,
        ]);

        $marco = Trainer::create([
            'name'           => 'Coach Marco Dela Cruz',
            'email'          => 'marco@fiturban.com',
            'specialization' => 'Muscle Building Coach',
            'trainer_level'  => 'Intermediate',
            'is_available'   => true,
            'is_active'      => true,
        ]);

        $ethan = Trainer::create([
            'name'           => 'Coach Ethan Villanueva',
            'email'          => 'ethan@fiturban.com',
            'specialization' => 'Performance Coach',
            'trainer_level'  => 'Advanced',
            'is_available'   => true,
            'is_active'      => true,
        ]);

        $sophia = Trainer::create([
            'name'           => 'Coach Sophia Mendoza',
            'email'          => 'sophia@fiturban.com',
            'specialization' => 'Wellness & Group Classes Coach',
            'trainer_level'  => 'Beginner',
            'is_available'   => true,
            'is_active'      => true,
        ]);

        // Attach trainers to services
        $adrian->services()->attach($pt->id);
        $camille->services()->attach($pt->id);
        $camille->services()->attach($pilates->id); // covers Sophia's off days
        $marco->services()->attach($pt->id);
        $ethan->services()->attach($pt->id);
        $sophia->services()->attach($pilates->id);

        // ── 4. SCHEDULES (next 30 days, real weekly availability) ─
        // 0=Sun, 1=Mon, 2=Tue, 3=Wed, 4=Thu, 5=Fri, 6=Sat
        $weekly = [
            1 => [ // Monday
                [$adrian,  $pt,     '05:00', '13:00'],
                [$ethan,   $pt,     '06:00', '14:00'],
                [$camille, $pt,     '13:00', '21:00'],
                [$sophia,  $pilates,'09:00', '17:00'],
            ],
            2 => [ // Tuesday
                [$adrian,  $pt,     '05:00', '13:00'],
                [$ethan,   $pt,     '06:00', '14:00'],
                [$marco,   $pt,     '14:00', '22:00'],
                [$sophia,  $pilates,'09:00', '17:00'],
            ],
            3 => [ // Wednesday
                [$camille, $pt,     '13:00', '21:00'],
                [$marco,   $pt,     '14:00', '22:00'],
                [$camille, $pilates,'09:00', '17:00'],
            ],
            4 => [ // Thursday
                [$adrian,  $pt,     '05:00', '13:00'],
                [$ethan,   $pt,     '06:00', '14:00'],
                [$camille, $pt,     '13:00', '21:00'],
                [$sophia,  $pilates,'09:00', '17:00'],
            ],
            5 => [ // Friday
                [$adrian,  $pt,     '05:00', '13:00'],
                [$marco,   $pt,     '14:00', '22:00'],
                [$sophia,  $pilates,'09:00', '17:00'],
            ],
            6 => [ // Saturday
                [$ethan,   $pt,     '06:00', '14:00'],
                [$camille, $pt,     '13:00', '21:00'],
                [$marco,   $pt,     '14:00', '22:00'],
                [$camille, $pilates,'09:00', '17:00'],
            ],
            0 => [ // Sunday
                [$adrian,  $pt,     '05:00', '13:00'],
                [$ethan,   $pt,     '06:00', '14:00'],
                [$camille, $pt,     '13:00', '21:00'],
                [$marco,   $pt,     '14:00', '22:00'],
                [$sophia,  $pilates,'09:00', '17:00'],
            ],
        ];

        for ($day = 0; $day < 30; $day++) {
            $date    = now()->addDays($day);
            $dateStr = $date->toDateString();
            $dow     = (int) $date->format('w');

            if (!isset($weekly[$dow])) continue;

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
    }
}
