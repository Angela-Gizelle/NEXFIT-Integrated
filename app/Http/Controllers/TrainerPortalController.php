<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class TrainerPortalController extends Controller
{
    /**
     * Trainer landing page — simple welcome screen.
     */
    public function home()
    {
        return view('trainer.home');
    }

    public function dashboard()
    {
        return view('trainer.dashboard');
    }

    /**
     * Members assigned to the signed-in trainer.
     */
    public function members()
    {
        $trainer = Auth::guard('trainer')->user();

        $members = $trainer->members()
            ->with('activePackage')
            ->orderBy('id', 'asc')
            ->get();

        $activeCount   = $members->where('status', 'Active')->count();
        $inactiveCount = $members->where('status', 'Inactive')->count();

        return view('trainer.members', compact('members', 'activeCount', 'inactiveCount'));
    }

    public function schedule()
    {
        return view('trainer.schedule');
    }

    public function trainingPlan()
    {
        return view('trainer.training-plan');
    }
}
