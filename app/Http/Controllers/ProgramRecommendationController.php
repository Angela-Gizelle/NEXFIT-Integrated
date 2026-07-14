<?php

namespace App\Http\Controllers;

use App\Models\FitnessAssessment;
use App\Models\ParqResponse;
use App\Models\Program;
use App\Models\Service;
use App\Models\Trainer;
use App\Models\Schedule;
use Illuminate\Routing\Controller as BaseController;

class ProgramRecommendationController extends BaseController
{
    // Fitness goal → recommended trainer name
    private array $goalTrainerMap = [
        'Build Muscle'                    => 'Coach Marco Dela Cruz',
        'Strength & Conditioning'         => 'Coach Adrian Reyes',
        'Athletic Performance'            => 'Coach Ethan Villanueva',
        'Weight Loss & Healthy Lifestyle' => 'Coach Camille Santos',
        'Flexibility & Mobility'          => 'Coach Sophia Mendoza',
        'Posture & Core Strength'         => 'Coach Sophia Mendoza',
    ];

    // Fitness goal → recommended program name
    private array $goalProgramMap = [
        'Build Muscle'                    => 'Muscle Building Program',
        'Strength & Conditioning'         => 'Strength & Conditioning Program',
        'Athletic Performance'            => 'Athletic Performance Program',
        'Weight Loss & Healthy Lifestyle' => 'Weight Loss & Lifestyle Program',
        'Flexibility & Mobility'          => 'Flexibility & Mobility Program',
        'Posture & Core Strength'         => 'Posture & Core Strength Program',
    ];

    public function show()
    {
        // ── Guard ────────────────────────────────────────────────
        $assessmentId = session('assessment_id');

        if (!$assessmentId) {
            return redirect('/booking/assessment')
                ->with('error', 'Please complete the fitness assessment first.');
        }

        $assessment = FitnessAssessment::find($assessmentId);

        if (!$assessment) {
            return redirect('/booking/assessment')
                ->with('error', 'Assessment record not found.');
        }

        // ── PAR-Q check ──────────────────────────────────────────
        $parq = ParqResponse::where('fitness_assessment_id', $assessmentId)->first();

        if ($parq && $parq->medical_clearance_required) {
            return view('booking.medical_clearance', compact('assessment'));
        }

        // ── Fitness level (display only, not used for filtering) ─
        $level = match (strtolower($assessment->exercise_experience)) {
            'intermediate' => 'Intermediate',
            'advanced'     => 'Advanced',
            default        => 'Beginner',
        };

        // ── Selected date from date picker ───────────────────────
        $requestedDate = request('date', now()->toDateString());

        // ── Route by service interest ────────────────────────────
        switch ($assessment->interested_in) {

            // ── 1. Personal Training ─────────────────────────────
            case 'Personal Training':

                $service = Service::where('name', 'Personal Training')->first();

                // Get recommended trainer by goal
                $fitnessGoal            = $assessment->fitness_goal;
                $recommendedTrainerName = $this->goalTrainerMap[$fitnessGoal] ?? null;
                $recommendedProgramName = $this->goalProgramMap[$fitnessGoal] ?? null;

                $recommendedTrainer = $recommendedTrainerName
                    ? Trainer::where('name', $recommendedTrainerName)
                              ->where('is_active', true)
                              ->first()
                    : null;

                // Find the best date + trainer combination
                [$selectedDate, $trainer] = $this->findBestDateAndTrainer(
                    $service->id,
                    $recommendedTrainer,
                    $requestedDate,
                    'Personal Training'
                );

                // Backup = any other available PT trainer
                $backupTrainer = Trainer::where('is_available', true)
                    ->where('is_active', true)
                    ->whereHas('services', fn($q) => $q->where('name', 'Personal Training'))
                    ->where('id', '!=', $trainer?->id)
                    ->first();

                // Program by goal name
                $program = $recommendedProgramName
                    ? Program::where('name', $recommendedProgramName)->first()
                    : Program::where('service_id', $service->id)->first();

                // Get slots for the resolved trainer + date
                $availableSlots = $this->getSlots($service->id, $trainer?->id, $selectedDate);

                return view('booking.program', compact(
                    'assessment', 'program', 'trainer', 'backupTrainer',
                    'availableSlots', 'service', 'level', 'selectedDate'
                ));

            // ── 2. Pilates ───────────────────────────────────────
            case 'Pilates':

                $service = Service::where('name', 'Pilates')->first();

                // Sophia is primary, Camille covers her off days
                $sophia  = Trainer::where('name', 'Coach Sophia Mendoza')->where('is_active', true)->first();
                $camille = Trainer::where('name', 'Coach Camille Santos')->where('is_active', true)->first();

                [$selectedDate, $trainer] = $this->findBestDateAndTrainer(
                    $service->id,
                    $sophia,
                    $requestedDate,
                    'Pilates'
                );

                $backupTrainer = $trainer?->id === $sophia?->id ? $camille : $sophia;

                $program = Program::where('service_id', $service->id)
                    ->where('level', $level)
                    ->first()
                    ?? Program::where('service_id', $service->id)->first();

                $availableSlots = $this->getSlots($service->id, $trainer?->id, $selectedDate);

                return view('booking.program', compact(
                    'assessment', 'program', 'trainer', 'backupTrainer',
                    'availableSlots', 'service', 'level', 'selectedDate'
                ));

            // ── 3. Open Gym Access ───────────────────────────────
            case 'Open Gym Access':

                $service = Service::where('name', 'Open Gym Access')->first();
                return view('booking.open_gym', compact('assessment', 'service'));

            default:
                return redirect('/booking/assessment')
                    ->with('error', 'Could not determine your service. Please try again.');
        }
    }

    /**
     * Find the best trainer + date combination.
     *
     * Priority:
     * 1. Recommended trainer on requested date
     * 2. Any available trainer on requested date
     * 3. Recommended trainer on their next available date
     * 4. Any available trainer on any date (within 30 days)
     *
     * Always returns [$date, $trainer] — never null trainer if data is seeded.
     */
    private function findBestDateAndTrainer(
        int     $serviceId,
        ?Trainer $recommended,
        string  $requestedDate,
        string  $serviceName
    ): array {

        // 1. Recommended trainer on requested date
        if ($recommended) {
            $slots = $this->getSlots($serviceId, $recommended->id, $requestedDate);
            if ($slots->isNotEmpty()) {
                return [$requestedDate, $recommended];
            }
        }

        // 2. Any available trainer on the requested date
        $anyTrainerOnDate = Schedule::where('service_id', $serviceId)
            ->where('date', $requestedDate)
            ->where('is_full', false)
            ->where('is_active', true)
            ->with('trainer')
            ->first();

        if ($anyTrainerOnDate && $anyTrainerOnDate->trainer) {
            return [$requestedDate, $anyTrainerOnDate->trainer];
        }

        // 3. Recommended trainer on their next available date
        if ($recommended) {
            $nextSlot = Schedule::where('service_id', $serviceId)
                ->where('trainer_id', $recommended->id)
                ->where('date', '>', $requestedDate)
                ->where('is_full', false)
                ->where('is_active', true)
                ->orderBy('date')
                ->first();

            if ($nextSlot) {
                return [$nextSlot->date->toDateString(), $recommended];
            }
        }

        // 4. Any trainer on any upcoming date
        $anySlot = Schedule::where('service_id', $serviceId)
            ->where('date', '>=', $requestedDate)
            ->where('is_full', false)
            ->where('is_active', true)
            ->orderBy('date')
            ->orderBy('start_time')
            ->with('trainer')
            ->first();

        if ($anySlot && $anySlot->trainer) {
            return [$anySlot->date->toDateString(), $anySlot->trainer];
        }

        // Absolute fallback — return recommended trainer + today (will show no slots message)
        return [$requestedDate, $recommended];
    }

    /**
     * Get available slots for a trainer on a date.
     */
    private function getSlots(int $serviceId, ?int $trainerId, string $date)
    {
        if (!$trainerId) {
            return collect();
        }

        return Schedule::where('service_id', $serviceId)
            ->where('trainer_id', $trainerId)
            ->where('date', $date)
            ->where('is_full', false)
            ->where('is_active', true)
            ->orderBy('start_time')
            ->get();
    }
}