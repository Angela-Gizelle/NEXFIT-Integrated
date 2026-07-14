<?php

namespace App\Services;

use App\Models\Trainer;
use Exception;

/**
 * Adapted from the standalone training-plan module's trainer-matcher.php.
 *
 * The original matched by hardcoded trainer ID (1 = Pilates, 2 = PT,
 * 3 = Special Population) because it assumed a fixed 3-trainer seed.
 * The merged NexFit database seeds 5 trainers with free-text
 * `specialization` strings and a `trainer_level` enum instead, and has
 * no dedicated "Special Population" trainer — so this version matches
 * on keyword overlap with `specialization` plus `trainer_level`, and
 * falls back to the trainer with the most relevant specialization
 * rather than a hardcoded ID.
 */
class TrainerMatcher
{
    private const VALID_POPULATIONS = ['pregnancy', 'senior', 'pwd', 'medical_condition'];

    // Keywords looked for in a trainer's free-text specialization field.
    private const SPECIALIZATION_KEYWORDS = [
        'pilates' => ['flexibility', 'posture', 'core_strength', 'mobility', 'balance'],
        'strength' => ['strength', 'muscle_building', 'functional_fitness'],
        'muscle' => ['muscle_building', 'strength'],
        'performance' => ['functional_fitness', 'weight_management'],
        'nutrition' => ['weight_management'],
        'wellness' => ['flexibility', 'mobility', 'balance'],
        'conditioning' => ['strength', 'functional_fitness'],
    ];

    // Special-population needs get routed to whichever active trainer has
    // the lowest trainer_level (safest default: beginner-friendly coaching),
    // since the merged seed has no dedicated special-population specialist.
    private const SPECIAL_POP_SCORE = [
        'pregnancy' => 100,
        'senior' => 90,
        'pwd' => 85,
        'medical_condition' => 95,
    ];

    /**
     * @param  array{special_populations?: mixed, fitness_goals?: mixed, fitness_level?: string}  $user
     * @return array{primary: array, backup: ?array, all: array}
     */
    public function matchTrainer(array $user): array
    {
        $trainers = Trainer::where('is_active', true)->orderBy('id')->get();

        if ($trainers->isEmpty()) {
            throw new Exception('No active trainers found in the database.');
        }

        $user['special_populations'] = $this->sanitizeArray(
            $this->decodeField($user['special_populations'] ?? []),
            self::VALID_POPULATIONS
        );
        $user['fitness_goals'] = $this->sanitizeArray(
            $this->decodeField($user['fitness_goals'] ?? []),
            null
        );

        $scores = [];

        foreach ($trainers as $trainer) {
            $score = $this->calculateMatchScore($user, $trainer);
            $scores[] = [
                'trainer' => $trainer,
                'score' => $score['total'],
                'reasoning' => $score['reasoning'],
            ];
        }

        usort($scores, fn ($a, $b) => $b['score'] <=> $a['score']);

        if ($scores[0]['score'] === 0) {
            // No signal at all — default to the trainer with the most
            // clients (a reasonable "generalist" proxy), or the first
            // active trainer if that field is empty/tied.
            usort($scores, fn ($a, $b) => ($b['trainer']->total_clients ?? 0) <=> ($a['trainer']->total_clients ?? 0));
            $scores[0]['reasoning'] = ['Default match — general fitness trainer'];
        }

        return [
            'primary' => $scores[0],
            'backup' => $scores[1] ?? null,
            'all' => $scores,
        ];
    }

    private function decodeField($value): array
    {
        if (is_array($value)) {
            return $value;
        }
        if (is_string($value)) {
            return json_decode($value, true) ?? [];
        }

        return [];
    }

    private function sanitizeArray(array $arr, ?array $whitelist): array
    {
        $filtered = array_filter($arr, function ($v) use ($whitelist) {
            if (! is_string($v) || trim($v) === '') {
                return false;
            }
            if ($whitelist !== null && ! in_array(trim($v), $whitelist, true)) {
                return false;
            }

            return true;
        });

        return array_values(array_map('trim', $filtered));
    }

    private function calculateMatchScore(array $user, Trainer $trainer): array
    {
        $score = 0;
        $reasoning = [];
        $specialization = strtolower((string) $trainer->specialization);

        // Special population needs: prefer trainers whose level is
        // 'Beginner' (safest/most adaptive) since there's no dedicated
        // special-population specialist in the merged trainer roster.
        if (! empty($user['special_populations'])) {
            foreach ($user['special_populations'] as $pop) {
                if (isset(self::SPECIAL_POP_SCORE[$pop]) && $trainer->trainer_level === 'Beginner') {
                    $score += self::SPECIAL_POP_SCORE[$pop];
                    $reasoning[] = 'Adaptive/beginner-level coaching suited for '.str_replace('_', ' ', $pop);
                }
            }

            if ($score >= 85) {
                return ['total' => $score, 'reasoning' => $reasoning];
            }
        }

        // Fitness-goal keyword match against the trainer's specialization text.
        if (! empty($user['fitness_goals'])) {
            foreach (self::SPECIALIZATION_KEYWORDS as $keyword => $goals) {
                if (str_contains($specialization, $keyword)) {
                    foreach ($user['fitness_goals'] as $goal) {
                        if (in_array($goal, $goals, true)) {
                            $score += 35;
                            $reasoning[] = ucfirst($keyword).' expertise in '.str_replace('_', ' ', $goal);
                        }
                    }
                }
            }
        }

        // Fitness-level match against trainer_level.
        if (! empty($user['fitness_level'])) {
            if ($user['fitness_level'] === 'fundamentals' && $trainer->trainer_level === 'Beginner') {
                $score += 10;
                $reasoning[] = 'Great for beginners';
            }
            if ($user['fitness_level'] === 'advanced' && $trainer->trainer_level === 'Advanced') {
                $score += 15;
                $reasoning[] = 'Experienced with advanced clients';
            }
        }

        return [
            'total' => max(0, $score),
            'reasoning' => ! empty($reasoning) ? $reasoning : ['General match'],
        ];
    }
}
