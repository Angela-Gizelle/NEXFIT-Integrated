@extends('layouts.staff-auth', [
    'eyebrow' => 'New Trainer Sign Up',
])

@section('title', 'Create Account')

@section('visual-title', 'Join the Fit Urban training team.')
@section('visual-copy', 'Sign up for a trainer account to manage your assigned members and sessions.')

@section('content')

    <p class="auth-eyebrow-tag">Create Account</p>
    <h2 class="auth-heading">Join Fit Urban</h2>
    <p class="auth-sub">Sign up for a trainer account to get started.</p>

    <form method="POST" action="{{ route('staff.register') }}" id="registerForm" novalidate>
        @csrf

        <div class="field">
            <label for="name">Full Name</label>
            <div class="input-wrap">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="@error('name') invalid @enderror" placeholder="Juan Dela Cruz" autocomplete="name">
            </div>
            <div class="field-error @error('name') show @enderror" id="name-error">
                {{ $errors->first('name') ?: 'Please enter your full name.' }}
            </div>
        </div>

        <div class="grid2">
            <div class="field">
                <label for="email">Email</label>
                <div class="input-wrap">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
                    <input type="text" name="email" id="email" value="{{ old('email') }}"
                        class="@error('email') invalid @enderror" placeholder="you@email.com" autocomplete="email">
                </div>
                <div class="field-error @error('email') show @enderror" id="email-error">
                    {{ $errors->first('email') ?: 'Please enter your email.' }}
                </div>
            </div>
            <div class="field">
                <label for="phone">Mobile Number</label>
                <div class="input-wrap">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.12.9.34 1.79.66 2.63a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.45-1.23a2 2 0 012.11-.45c.84.32 1.73.54 2.63.66A2 2 0 0122 16.92z"/></svg>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                        class="@error('phone') invalid @enderror" placeholder="09XX XXX XXXX" autocomplete="tel">
                </div>
                <div class="field-error @error('phone') show @enderror" id="phone-error">
                    {{ $errors->first('phone') ?: 'Please enter your mobile number.' }}
                </div>
            </div>
        </div>

        <div class="grid2">
            <div class="field">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 018 0v3"/></svg>
                    <input type="password" name="password" id="password"
                        class="@error('password') invalid @enderror" placeholder="Create a password" autocomplete="new-password">
                    <button type="button" class="pw-toggle" data-toggle-for="password" aria-label="Show password">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                <div class="field-error @error('password') show @enderror" id="password-error">
                    {{ $errors->first('password') ?: 'Password must be at least 8 characters.' }}
                </div>
            </div>
            <div class="field">
                <label for="password_confirmation">Confirm Password</label>
                <div class="input-wrap">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 018 0v3"/></svg>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        placeholder="Re-enter password" autocomplete="new-password">
                    <button type="button" class="pw-toggle" data-toggle-for="password_confirmation" aria-label="Show password">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
                <div class="field-error" id="password_confirmation-error">Passwords do not match.</div>
            </div>
        </div>

        <div class="consent" id="consent-wrap">
            <input type="checkbox" name="consent" id="consent" {{ old('consent') ? 'checked' : '' }}>
            <label for="consent">I agree to Fit Urban&rsquo;s Terms of Service and consent to the processing of my personal data under the Data Privacy Act of 2012 (RA 10173).</label>
        </div>
        <div class="field-error @error('consent') show @enderror" id="consent-error" style="margin-top:-14px;">
            {{ $errors->first('consent') ?: 'Please accept the terms and data consent to continue.' }}
        </div>

        <button type="submit" class="submit-btn">Create Account</button>
    </form>

    <div class="auth-divider">ALREADY REGISTERED</div>
    <p class="switch-link">Already have an account? <a href="{{ route('staff.login') }}">Sign in</a></p>
    <p class="privacy-note">Your account will be signed in automatically after registering.<br>Staff will confirm your trainer profile before your first session.</p>

@endsection

@push('scripts')
<script>
    // Password visibility toggles
    document.querySelectorAll('.pw-toggle').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = document.getElementById(btn.dataset.toggleFor);
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    });

    // Inline validation before submit — presence checks only, no email format
    // enforcement (kept intentionally lax per studio's request).
    function setError(inputId, errorId, isInvalid){
        const input = document.getElementById(inputId);
        if (input) input.classList.toggle('invalid', isInvalid);
        document.getElementById(errorId).classList.toggle('show', isInvalid);
    }

    document.getElementById('registerForm').addEventListener('submit', function (e) {
        const name  = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const pass  = document.getElementById('password').value;
        const pass2 = document.getElementById('password_confirmation').value;
        const consent = document.getElementById('consent').checked;

        let valid = true;

        setError('name', 'name-error', name === '');
        if (name === '') valid = false;

        setError('email', 'email-error', email === '');
        if (email === '') valid = false;

        setError('phone', 'phone-error', phone === '');
        if (phone === '') valid = false;

        const passTooShort = pass.length < 8;
        setError('password', 'password-error', passTooShort);
        if (passTooShort) valid = false;

        const passMismatch = pass !== pass2 || pass2 === '';
        setError('password_confirmation', 'password_confirmation-error', passMismatch);
        if (passMismatch) valid = false;

        const consentWrap = document.getElementById('consent-wrap');
        document.getElementById('consent-error').classList.toggle('show', !consent);
        consentWrap.classList.toggle('invalid', !consent);
        if (!consent) valid = false;

        if (!valid) e.preventDefault();
    });

    ['name','email','phone','password','password_confirmation'].forEach(function (id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('input', function () {
            el.classList.remove('invalid');
            document.getElementById(id + '-error').classList.remove('show');
        });
    });
    document.getElementById('consent').addEventListener('change', function () {
        document.getElementById('consent-wrap').classList.remove('invalid');
        document.getElementById('consent-error').classList.remove('show');
    });
</script>
@endpush