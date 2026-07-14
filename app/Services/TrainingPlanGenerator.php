<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Trainer;
use App\Models\TrainingPlan;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Ported from the standalone training-plan module's
 * training-plan-generator.php, adapted to read the member's profile
 * from NexFit's actual schema (Member, FitnessAssessment,
 * MedicalHistory) instead of a flat `$profile` array passed in from a
 * simple onboarding form.
 *
 * Calls the Gemini API to generate a personalized training plan and
 * falls back to a safe, pre-written plan if no API key is configured
 * or the request fails.
 */
class TrainingPlanGenerator
{
    private TrainerMatcher $matcher;

    public function __construct(TrainerMatcher $matcher)
    {
        $this->matcher = $matcher;
    }

    /**
     * Build the profile array the matcher/prompt expect, straight from
     * the member's existing records (population_class, fitness_level,
     * latest fitness assessment + medical history).
     */
    public function buildProfileFromMember(Member $member): array
    {
        $assessment = $member->user
            ? $member->user->assessments()->latest()->with('medicalHistory')->first()
            : null;

        $specialPopulations = [];
        if (! empty($member->population_class) && $member->population_class !== 'General') {
            // population_class values are expected to align with the
            // matcher's whitelist (pregnancy/senior/pwd/medical_condition);
            // anything else is safely ignored by the matcher's sanitizer.
            $specialPopulations[] = strtolower(str_replace(' ', '_', $member->population_class));
        }

        $goals = [];
        if ($assessment && ! empty($assessment->fitness_goal)) {
            $goals[] = strtolower(str_replace(' ', '_', $assessment->fitness_goal));
        }

        $medical = 'none reported';
        if ($assessment?->medicalHistory) {
            $mh = $assessment->medicalHistory;
            $parts = [];
            if ($mh->chronic_illness) {
                $parts[] = 'Chronic illness: '.($mh->chronic_illness_details ?: 'yes');
            }
            if ($mh->major_surgery) {
                $parts[] = 'Major surgery: '.($mh->major_surgery_details ?: 'yes');
            }
            if ($mh->current_medications) {
                $parts[] = 'Current medication: '.($mh->medication_name ?: 'yes');
            }
            if (! empty($parts)) {
                $medical = implode('; ', $parts);
            }
        }

        return [
            'special_populations' => $specialPopulations,
            'goals' => $goals,
            'fitness_level' => $member->fitness_level ?? 'fundamentals',
            'exercise_experience' => $assessment->exercise_experience ?? 'not specified',
            'medical_conditions' => $medical,
        ];
    }

    /**
     * Match a trainer for the member's user account, then generate + persist a plan.
     */
    public function generatePlanForMember(Member $member): array
    {
        if (! $member->user) {
            throw new Exception('This member has no linked user account to generate a plan for.');
        }

        $profile = $this->buildProfileFromMember($member);

        return $this->generatePlan($member->user, $profile);
    }

    public function generatePlan(User $user, array $profile): array
    {
        $matchInput = [
            'special_populations' => $profile['special_populations'] ?? [],
            'fitness_goals' => $profile['goals'] ?? $profile['fitness_goals'] ?? [],
            'fitness_level' => $profile['fitness_level'] ?? null,
        ];

        $match = $this->matcher->matchTrainer($matchInput);
        $trainer = $match['primary']['trainer'] ?? null;

        if (! $trainer) {
            throw new Exception('TrainerMatcher returned no valid trainer.');
        }

        return $this->generatePlanWithTrainer($user, $trainer, $profile);
    }

    public function generatePlanWithTrainer(User $user, Trainer $trainer, array $profile): array
    {
        $prompt = $this->buildPrompt($user, $trainer, $profile);
        $planContent = $this->callGeminiApi($prompt);
        $structured = $this->parseAiPlan($planContent, $trainer, $user);

        TrainingPlan::where('user_id', $user->id)->update(['is_current' => false]);

        $plan = TrainingPlan::create([
            'user_id' => $user->id,
            'trainer_id' => $trainer->id,
            'plan_content' => $structured,
            'is_current' => true,
            'generated_by' => $structured['generated_by'] ?? 'gemini',
        ]);

        return [
            'success' => true,
            'plan_id' => $plan->id,
            'trainer' => $trainer,
            'plan' => $structured,
        ];
    }

    private function buildPrompt(User $user, Trainer $trainer, array $profile): string
    {
        $specialPop = ! empty($profile['special_populations'])
            ? implode(', ', (array) $profile['special_populations'])
            : 'none';
        $fitnessGoals = ! empty($profile['goals'] ?? $profile['fitness_goals'] ?? null)
            ? implode(', ', (array) ($profile['goals'] ?? $profile['fitness_goals']))
            : 'general fitness';

        $name = $user->name;
        $fitnessLevel = $profile['fitness_level'] ?? 'fundamentals';
        $exerciseExperience = $profile['exercise_experience'] ?? 'not specified';
        $medicalHistory = $profile['medical_conditions'] ?? $profile['medical_history'] ?? 'none reported';

        return <<<EOT
Act as a certified fitness trainer. Generate a concise training plan for the member below. Make sure to consider their fitness level, goals, exercise experience, and population.

MEMBER PROFILE:
- Name: {$name}
- Fitness Level: {$fitnessLevel}
- Goals: {$fitnessGoals}
- Exercise Experience: {$exerciseExperience}
- Special Populations: {$specialPop}
- Medical History: {$medicalHistory}
- Assigned Trainer Specialization: {$trainer->specialization}

STRICT OUTPUT FORMAT — output ONLY the two sections below, no markdown symbols (no **, no ##, no ---), no preamble, no headings outside what is specified. Each bullet must be a single complete sentence starting with a capital letter.

PRE-TRAINING PLAN:
- [bullet 1]
- [bullet 2]
- [bullet 3]
- [bullet 4]
- [bullet 5]
- [bullet 6]
- [bullet 7]
- [bullet 8]

POST-TRAINING PLAN:
- [bullet 1]
- [bullet 2]
- [bullet 3]
- [bullet 4]
- [bullet 5]
- [bullet 6]
- [bullet 7]
- [bullet 8]

Rules:
- Cover for PRE: mental/physical preparation, attire, nutrition before session, hydration, warm-up habits, what to know about the program, health precautions, sleep.
- Cover for POST: cool-down, diet and nutrition after session, body after-care, rest and sleep, self-care and mental wellness, hydration, when to seek medical advice.
- Tailor every bullet to the trainer specialty and the member's profile (special populations and medical history if present).
- NEVER mention the trainer's name in any bullet. Never reference the trainer as a person at all.
- NEVER use phrases like "as demonstrated by", "as shown by", "as instructed by", "as directed by", or any similar phrasing that references trainer instruction or demonstration.
- NEVER tell the member to "communicate", "inform", "tell", "notify", or "report" anything to the trainer in any bullet.
- Write every bullet as a self-contained, actionable instruction the member follows independently, with no reference to any person.
- No markdown. No extra text before or after the two sections.
EOT;
    }

    private function callGeminiApi(string $prompt): string
    {
        $apiKey = config('services.gemini.key');

        if (empty($apiKey)) {
            return $this->getDefaultPlan();
        }

        try {
            $model = config('services.gemini.model', 'gemini-2.5-flash');

            $response = Http::timeout(30)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}", [
                    'contents' => [['parts' => [['text' => $prompt]]]],
                    'generationConfig' => ['maxOutputTokens' => 1500, 'temperature' => 0.6],
                ]);

            if ($response->successful()) {
                $text = $response->json('candidates.0.content.parts.0.text');
                if (! empty($text)) {
                    return $text;
                }
            }

            Log::warning('Gemini API returned no usable content', ['status' => $response->status()]);
        } catch (Exception $e) {
            Log::error('callGeminiApi: '.$e->getMessage());
        }

        return $this->getDefaultPlan();
    }

    private function parseAiPlan(string $content, Trainer $trainer, User $user): array
    {
        $preRaw = '';
        if (preg_match('/PRE-TRAINING PLAN\s*:\s*\n(.*?)(?=POST-TRAINING PLAN\s*:|$)/si', $content, $m)) {
            $preRaw = trim($m[1]);
        }

        $postRaw = '';
        if (preg_match('/POST-TRAINING PLAN\s*:\s*\n(.*?)$/si', $content, $m)) {
            $postRaw = trim($m[1]);
        }

        if (empty($preRaw) && empty($postRaw)) {
            $preRaw = $content;
        }

        return [
            'title' => 'AI-Assisted Training Plan',
            'member_name' => trim((string) $user->name),
            'trainer_id' => $trainer->id,
            'trainer_name' => trim((string) $trainer->name),
            'trainer_specialty' => $trainer->specialization ?? '',
            'pre_bullets' => $this->extractBullets($preRaw),
            'post_bullets' => $this->extractBullets($postRaw),
            'raw_content' => $content,
            'generated_by' => empty(config('services.gemini.key')) ? 'default-fallback' : 'gemini',
            'generated_at' => now()->toDateTimeString(),
        ];
    }

    private function extractBullets(string $raw): array
    {
        $lines = preg_split('/\r?\n/', trim($raw));
        $bullets = [];
        foreach ($lines as $line) {
            $line = preg_replace('/^[\s\-\*\•\d\.]+/', '', $line);
            $line = preg_replace('/\*{1,2}|#{1,3}/', '', $line);
            $line = trim($line);
            if (strlen($line) > 8) {
                $bullets[] = $line;
            }
        }

        return $bullets;
    }

    private function getDefaultPlan(): string
    {
        return <<<'EOT'
PRE-TRAINING PLAN:
- Review your fitness goals and any physical limitations before your first session so you arrive mentally prepared and focused.
- Wear comfortable, breathable athletic clothing and supportive footwear appropriate for studio or gym exercises.
- Eat a light, balanced meal 1 to 2 hours before your session such as a banana with peanut butter, oatmeal, or a light sandwich, and avoid heavy meals within one hour of training.
- Hydrate well by drinking at least 500ml of water in the two hours leading up to your session and bring a water bottle to every class.
- Arrive 10 to 15 minutes early to mentally transition, review your session goals, and complete a light self-directed warm-up such as slow walking or gentle joint rotations.
- Stop any exercise immediately and rest if you notice new or worsening pain, unusual fatigue, or dizziness before or during your session.
- Get at least 7 to 8 hours of sleep the night before your session to ensure your body has adequate energy for training.
- Keep a brief training journal and note how you feel before each session to track your progress and energy levels over time.

POST-TRAINING PLAN:
- Spend 5 to 10 minutes on a gentle cool-down immediately after your session through light walking, slow breathing, and progressive static stretches held for 20 to 30 seconds each.
- Refuel within 30 to 60 minutes post-session with a meal containing protein and complex carbohydrates such as grilled chicken with rice or eggs with whole grain toast to support muscle recovery.
- Rehydrate promptly by drinking at least 500ml of water after your session and continue sipping water throughout the rest of the day.
- Apply a cold pack to any sore joints or muscles for 10 to 15 minutes if you experience discomfort or inflammation after training.
- Prioritize 7 to 9 hours of quality sleep each night to allow your muscles and nervous system to fully recover between sessions.
- On rest days, engage in light active recovery activities such as walking, gentle yoga, or stretching to maintain mobility without overexerting.
- Practice stress-management techniques such as deep breathing, meditation, or journaling to support mental wellness alongside physical training.
- If you experience persistent sharp pain, dizziness, shortness of breath, or unusual fatigue, stop training immediately and consult a healthcare professional before your next session.
EOT;
    }
}
