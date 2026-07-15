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
use App\Http\Controllers\Auth\StaffLoginController;
use App\Http\Controllers\Auth\StaffRegisterController;
use App\Http\Controllers\TrainerPortalController;
use App\Http\Controllers\SchedulingController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\SessionPackageSaleController;

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

// ── Staff: membership management + scheduling (Membership module) ─
// Now properly gated behind the `staff` guard (added alongside the
// staff/trainer/admin auth portal).
Route::middleware('auth:staff')->group(function () {
    Route::post('members/expire-memberships', [MemberController::class, 'expireMemberships'])
        ->name('members.expire');
    Route::get('members/{member}/renew',  [MemberController::class, 'renewForm'])->name('members.renew');
    Route::post('members/{member}/renew', [MemberController::class, 'renew'])->name('members.renew.store');
    Route::resource('members', MemberController::class);

    Route::get('scheduling', [SchedulingController::class, 'index'])->name('scheduling.index');
    Route::post('scheduling', [SchedulingController::class, 'store'])->name('scheduling.store');
    Route::delete('scheduling/{trainerSession}', [SchedulingController::class, 'destroy'])->name('scheduling.destroy');

    // ── Session Management module (Session Inventory Tracking) ────
    Route::prefix('session-management')->name('session-management.')->group(function () {
        Route::get('/',                 [SessionController::class, 'index'])->name('index');
        Route::get('/create',           [SessionController::class, 'create'])->name('create');
        Route::post('/',                [SessionController::class, 'store'])->name('store');
        Route::get('/{session}',        [SessionController::class, 'show'])->name('show');
        Route::patch('/{session}/mark-conducted', [SessionController::class, 'markConducted'])->name('markConducted');
        Route::patch('/{session}/cancel',         [SessionController::class, 'cancel'])->name('cancel');
        Route::patch('/{session}/restore',        [SessionController::class, 'restore'])->name('restore');
    });

    // ── Session Credit Inventory (per-member balances + manual adjustment) ─
    Route::prefix('session-credit-inventory')->name('session-credit-inventory.')->group(function () {
        Route::get('/', [SessionController::class, 'inventory'])->name('index');
        Route::post('/members/{member}/adjust-credit', [SessionController::class, 'adjustCredit'])->name('adjustCredit');
    });

    // ── Session Package Sales ──────────────────────────────────────
    Route::prefix('package-sales')->name('package-sales.')->group(function () {
        Route::get('/',              [SessionPackageSaleController::class, 'index'])->name('index');
        Route::get('/create',        [SessionPackageSaleController::class, 'create'])->name('create');
        Route::post('/',             [SessionPackageSaleController::class, 'store'])->name('store');
        Route::get('/{packageSale}', [SessionPackageSaleController::class, 'show'])->name('show');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// ── Staff / Admin / Trainer portal (Membership module) ────────────
// Separate from the member-facing Breeze auth above: staff/admin
// accounts live in `staff` (role: Staff|Admin) and trainers in
// `trainers`, each behind their own guard.
Route::middleware('guest')->prefix('staff')->name('staff.')->group(function () {
    Route::get('/login', [StaffLoginController::class, 'create'])->name('login');
    Route::post('/login', [StaffLoginController::class, 'store']);

    // Trainers are the only self-registering account type here.
    Route::get('/register', [StaffRegisterController::class, 'create'])->name('register');
    Route::post('/register', [StaffRegisterController::class, 'store']);
});

Route::post('/staff/logout', [StaffLoginController::class, 'destroy'])
    ->middleware('auth:staff,trainer')
    ->name('staff.logout');

Route::get('/staff/dashboard', function () {
    return view('staff.dashboard');
})->middleware('auth:staff')->name('staff.dashboard');

Route::middleware('auth:trainer')->prefix('trainer')->name('trainer.')->group(function () {
    Route::get('/home', [TrainerPortalController::class, 'home'])->name('home');
    Route::get('/dashboard', [TrainerPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/members', [TrainerPortalController::class, 'members'])->name('members');
    Route::get('/schedule', [TrainerPortalController::class, 'schedule'])->name('schedule');
    Route::get('/training-plan', [TrainerPortalController::class, 'trainingPlan'])->name('training-plan');

    // Session Management module — read-only view of this trainer's own sessions
    Route::get('/sessions', [SessionController::class, 'trainerIndex'])->name('sessions');
});