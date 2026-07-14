@extends('layouts.staff-auth', [
    'eyebrow' => 'Staff & Trainer Access',
])

@section('title', 'Sign In')

@section('visual-title')
Coffee's on.<br>Let's get the day moving.
@endsection
@section('visual-copy', 'Sign in to pick up right where you left off — schedules, credits, and training plans, all in sync.')

@section('content')

    <p class="auth-eyebrow-tag">Sign In</p>
    <h2 class="auth-heading">Welcome back</h2>
    <p class="auth-sub">Choose your account type and sign in to continue.</p>

    {{-- Sliding segmented control: toggles which form is active and posts a role flag --}}
    @php
        $defaultRole = old('role', session('status') ? 'trainer' : 'staff');
    @endphp

    <div class="role-switch" id="roleSwitch" data-active="{{ $defaultRole }}">
        <div class="role-slider"></div>
        <button type="button" class="{{ $defaultRole === 'staff' ? 'active' : '' }}" data-role="staff">Staff</button>
        <button type="button" class="{{ $defaultRole === 'trainer' ? 'active' : '' }}" data-role="trainer">Trainer</button>
    </div>

    <form method="POST" action="{{ route('staff.login') }}" id="loginForm" novalidate>
        @csrf
        <input type="hidden" name="role" id="roleInput" value="{{ $defaultRole }}">

        <div class="field">
            <label for="login">Email</label>
            <div class="input-wrap">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
                <input
                    type="text"
                    name="login"
                    id="login"
                    value="{{ old('login') }}"
                    class="@error('login') invalid @enderror"
                    placeholder="you@fiturban-demo.com"
                    autocomplete="username"
                >
            </div>
            @error('login')
                <div class="field-error show">{{ $message }}</div>
            @enderror
        </div>

        <div class="field">
            <label for="password">Password</label>
            <div class="input-wrap">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 018 0v3"/></svg>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="@error('password') invalid @enderror"
                    placeholder="Enter your password"
                    autocomplete="current-password"
                >
                <button type="button" class="pw-toggle" data-toggle-for="password" aria-label="Show password">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
            </div>
            @error('password')
                <div class="field-error show">{{ $message }}</div>
            @enderror
        </div>

        <div class="field-row">
            <label class="remember">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                Keep me signed in
            </label>
            <a class="forgot" href="{{ route('password.request') }}">Forgot password?</a>
        </div>

        <button type="submit" class="submit-btn">Sign In</button>
    </form>

    <div id="trainerRegister" style="display: {{ $defaultRole === 'trainer' ? 'block' : 'none' }};">
        <div class="auth-divider">NEW TO NEXFIT</div>

        <p class="switch-link">
            Don&rsquo;t have a trainer account?
            <a href="{{ route('staff.register') }}">Create one</a>
        </p>
    </div>

@endsection

@push('scripts')
<script>
    const roleSwitch = document.getElementById('roleSwitch');
    const roleInput = document.getElementById('roleInput');
    const trainerRegister = document.getElementById('trainerRegister');

    roleSwitch.querySelectorAll('button').forEach(btn => {
        btn.addEventListener('click', () => {
            const role = btn.dataset.role;

            roleSwitch.dataset.active = role;
            roleInput.value = role;

            roleSwitch.querySelectorAll('button').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Show register message only for Trainer
            trainerRegister.style.display = role === 'trainer' ? 'block' : 'none';
        });
    });

    // Password visibility toggle
    document.querySelectorAll('.pw-toggle').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.getElementById(btn.dataset.toggleFor);
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    });
</script>
@endpush