<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * The member's own dashboard: profile, active package / sessions
     * availed, latest ParQ, and bookings. Training plan and session
     * management sections are placeholders for now.
     */
    public function show()
    {
        $user = Auth::user();

        $memberQuery = $user->member()->with([
            'trainer',
            'packages' => fn ($q) => $q->orderByDesc('coverage_start'),
            'activePackage',
            'healthFlags',
            'latestParq',
        ]);

        $member = $memberQuery->first();

        if (!$member) {
            // Not (yet) linked to a membership record — send them back to
            // their profile instead of a hard 404.
            return redirect()->route('dashboard')
                ->with('error', 'We couldn\'t find a membership linked to your account yet.');
        }

        $bookings = $user->bookings()
            ->with(['service', 'trainer', 'program'])
            ->orderByDesc('booking_date')
            ->orderByDesc('booking_time')
            ->get();

        return view('member.dashboard', compact('member', 'bookings'));
    }
}
