<?php
// REPLACE: routes/web.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicBookingController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\ParqController;
use App\Http\Controllers\ProgramRecommendationController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\WalkInController;
use App\Http\Controllers\BookingReviewController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\ParqReassessController;
use App\Http\Controllers\Member\MembershipSignupController;
use App\Http\Controllers\Member\TrainingPlanController;

// ── Public landing page ──────────────────────────────────────────
Route::get('/', function () {
    return view('booking.landing');
});

// ── Public Booking Flow (no login required) ──────────────────────

// Step 1 – Assessment form
Route::get('/booking/assessment', [PublicBookingController::class, 'assessment'])->name('booking.assessment');
Route::post('/assessment', [AssessmentController::class, 'store']);

// Step 2 – PAR-Q safety questionnaire
Route::get('/booking/parq/{id}', function ($id) {
    session(['assessment_id' => $id]);
    return view('booking.parq');
});
Route::post('/booking/parq', [ParqController::class, 'store']);

// Step 3 – Recommendation + slot selection
// (branches internally to Personal Training / Pilates / Open Gym view)
Route::get('/booking/program', [ProgramRecommendationController::class, 'show']);

// Step 3b – Open Gym direct access page (no trainer/program)
Route::get('/booking/open-gym', [PublicBookingController::class, 'openGym']);

// Step 3.5 – Review & Confirm page   ← ADD THIS LINE
Route::get('/booking/review', [BookingReviewController::class, 'show']);

// Step 4 – Confirm booking (policy sign-off + slot reservation)
Route::post('/booking', [BookingController::class, 'store']);

// Step 5 – Confirmation page
Route::get('/booking/confirmation/{id}', [BookingController::class, 'show'])
    ->name('booking.confirmation');

// ── Cancel / Reschedule an existing booking ──────────────────────
Route::patch('/booking/{id}/cancel',     [BookingController::class, 'cancel']);
Route::patch('/booking/{id}/reschedule', [BookingController::class, 'reschedule']);

// ── Walk-in inquiry (logged for staff follow-up) ─────────────────
Route::get('/walk-in', function () {
    return view('booking.walk_in');
});

Route::get('/booking/membership', function () {
    return view('booking.membership');
})->name('booking.membership');

// ── Authenticated routes (Laravel Breeze defaults) ───────────────
Route::get('/dashboard', function () {
    // Every login lands here first. If this account is linked to a
    // membership record, send them on to the member dashboard instead —
    // this check runs on every visit, so it always re-routes members.
    if (auth()->user()->member()->exists()) {
        return redirect()->route('member.dashboard');
    }

    $bookings = auth()->user()
        ->bookings()
        ->with(['service', 'trainer', 'program'])
        ->orderByDesc('booking_date')
        ->orderByDesc('booking_time')
        ->get();

    return view('dashboard', compact('bookings'));
})->middleware(['auth'])
  ->name('dashboard');

// ── Member dashboard (Membership module) ──────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/member/dashboard', [MemberDashboardController::class, 'show'])->name('member.dashboard');
    Route::post('/member/parq', [ParqReassessController::class, 'store'])->name('member.parq.store');

    // Self-service "Become a Member" flow — this is what actually
    // writes a row to `members` (+ their first `member_packages` row)
    // for the logged-in user, the same way registering writes to
    // `users` or booking writes to `bookings`.
    Route::get('/membership/join', [MembershipSignupController::class, 'show'])->name('member.join');
    Route::post('/membership/join', [MembershipSignupController::class, 'store'])->name('member.join.store');

    // AI-assisted training plan (training-plan module)
    Route::get('/member/training-plan', [TrainingPlanController::class, 'show'])->name('member.training-plan.show');
    Route::post('/member/training-plan/generate', [TrainingPlanController::class, 'generate'])->name('member.training-plan.generate');
    Route::post('/member/training-plan/regenerate', [TrainingPlanController::class, 'regenerate'])->name('member.training-plan.regenerate');
});

// ── Staff: membership management (Membership module) ─────────────
// NOTE: only gated behind `auth` for now — there's no staff/admin role
// on the User model yet, so any logged-in account can reach these.
// Tighten this with a role/permission check before going live.
Route::middleware('auth')->group(function () {
    Route::post('members/expire-memberships', [MemberController::class, 'expireMemberships'])
        ->name('members.expire');
    Route::get('members/{member}/renew',  [MemberController::class, 'renewForm'])->name('members.renew');
    Route::post('members/{member}/renew', [MemberController::class, 'renew'])->name('members.renew.store');
    Route::resource('members', MemberController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';