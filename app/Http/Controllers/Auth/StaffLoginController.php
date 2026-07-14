<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffLoginController extends Controller
{
    /**
     * Show the login form (Staff/Admin and Trainer share one view;
     * the "role" hidden field is toggled client-side by the sliding switch).
     *
     * This is a separate portal from the member-facing Breeze login at
     * /login — staff/admin/trainer accounts live in their own tables and
     * guards ('staff', 'trainer'), not the members-facing `users` table.
     */
    public function create()
    {
        return view('auth.staff-login');
    }

    /**
     * Handle an authentication attempt.
     *
     * Both staff/admin and trainers sign in with an email + password.
     * Staff/admin accounts are predefined (seeded), not self-registered —
     * see StaffSeeder. Trainers self-register — see StaffRegisterController.
     */
    public function store(Request $request)
    {
        $request->validate([
            'role'     => ['required', 'in:staff,trainer'],
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $credentials = [
            'email'    => $request->login,
            'password' => $request->password,
        ];

        // Staff/Admin and Trainer use separate guards (RBAC).
        $guard = $request->role === 'trainer' ? 'trainer' : 'staff';

        if (Auth::guard($guard)->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(
                $request->role === 'trainer' ? route('trainer.dashboard') : route('staff.dashboard')
            );
        }

        return back()
            ->withInput($request->only('login', 'role'))
            ->withErrors(['login' => 'These credentials do not match our records.']);
    }

    public function destroy(Request $request)
    {
        // Log out whichever guard the current user is actually
        // authenticated on (staff or trainer).
        foreach (['staff', 'trainer'] as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('staff.login');
    }
}
