<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Services\TrainingPlanGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TrainingPlanController extends Controller
{
    public function __construct(private TrainingPlanGenerator $generator) {}

    /**
     * Show the member's current AI training plan, if any.
     */
    public function show(): View|RedirectResponse
    {
        $member = Auth::user()->member;

        if (! $member) {
            return redirect()->route('dashboard')
                ->with('error', "We couldn't find a membership linked to your account yet.");
        }

        $plan = $member->user
            ->trainingPlans()
            ->where('is_current', true)
            ->with('trainer')
            ->first();

        return view('member.training-plan', compact('member', 'plan'));
    }

    /**
     * Generate a brand-new training plan (first time, or manual refresh).
     */
    public function generate(): RedirectResponse
    {
        $member = Auth::user()->member;

        if (! $member) {
            return redirect()->route('dashboard')
                ->with('error', "We couldn't find a membership linked to your account yet.");
        }

        try {
            $this->generator->generatePlanForMember($member);
        } catch (\Throwable $e) {
            return redirect()->route('member.training-plan.show')
                ->with('error', 'Could not generate a training plan: '.$e->getMessage());
        }

        return redirect()->route('member.training-plan.show')
            ->with('success', 'Your training plan has been generated.');
    }

    /**
     * Regenerate the plan (alias of generate — kept as a separate named
     * route so the "Regenerate" button in the view reads naturally and
     * matches the original standalone module's route names).
     */
    public function regenerate(): RedirectResponse
    {
        return $this->generate();
    }
}
