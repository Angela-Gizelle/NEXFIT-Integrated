<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class StaffSettingsController extends Controller
{
    /**
     * Show the Settings page. Only "Account" is functional for now —
     * Studio Configuration / User Management / Packages are admin-only
     * tabs reserved for later once those modules have real tables to
     * back them.
     */
    public function index(Request $request): View
    {
        return view('staff.settings.index', [
            'staff' => Auth::guard('staff')->user(),
        ]);
    }

    /**
     * Update the logged-in staff member's profile info.
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $staff = Auth::guard('staff')->user();

        $validated = $request->validate([
            'full_name'    => ['required', 'string', 'max:255'],
            'email'        => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('staff', 'email')->ignore($staff->staff_id, 'staff_id'),
            ],
            'contact_info' => ['nullable', 'string', 'max:255'],
        ]);

        $staff->fill($validated)->save();

        return redirect()->route('staff.settings.index')->with('status', 'profile-updated');
    }

    /**
     * Update the logged-in staff member's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $staff = Auth::guard('staff')->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password:staff'],
            'password'          => ['required', 'confirmed', Password::min(8)],
        ]);

        $staff->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        return redirect()->route('staff.settings.index')->with('status', 'password-updated');
    }
}
