@extends('layouts.booking')

@section('title', 'NexFit - About You')

@php $currentStep = 1; @endphp

@section('content')

<div class="main-card">

    <div class="card-head">
        <div class="icon"><i class="bi bi-person-fill"></i></div>
        <div>
            <h2>Tell us about yourself</h2>
            <p>We'll use this to recommend the best program for you</p>
        </div>
    </div>

    <form method="POST" action="/assessment" id="assessmentForm">
        @csrf

       {{-- 1. Personal Info --}}
<div class="field-row">
    <div class="field">
        <label>First Name <span class="req">*</span></label>
        <input type="text" name="first_name" placeholder="e.g. Maria" required>
    </div>
    <div class="field">
        <label>Last Name <span class="req">*</span></label>
        <input type="text" name="last_name" placeholder="e.g. Santos" required>
    </div>
</div>

<input type="hidden" name="full_name" id="full_name">

<div class="field-row">
    <div class="field">
        <label>Mobile Number <span class="req">*</span></label>
        <input type="tel" name="phone_number" placeholder="09XXXXXXXXX" required>
    </div>
    <div class="field">
        <label>Email Address</label>
        <input type="email" name="email" placeholder="you@email.com">
    </div>
</div>

{{-- 2. Interested In --}}
<div class="field">
    <label>Interested in <span class="req">*</span></label>
    <select name="interested_in" id="interestedIn" required>
        <option value="">Select a service...</option>
        <option value="Personal Training">Personal Training (PT)</option>
        <option value="Pilates">Pilates (Reformer / Mat)</option>
        <option value="Open Gym Access">Open Gym Access</option>
    </select>
</div>

{{-- 3. Fitness Goal (filtered by service) --}}
<div class="field" id="goalSection" style="display:none;">
    <label>What's your fitness goal? <span class="req">*</span></label>
    <p style="font-size:12px; color:var(--text-soft); margin-bottom:10px;">
        Based on your selected service.
    </p>
    <div class="pill-group" id="goalPills">
        {{-- Personal Training goals --}}
        <label class="pill goal-pill" data-service="Personal Training" style="display:none;">
            <input type="radio" name="fitness_goal" value="Build Muscle">
            <i class="bi bi-lightning-charge-fill" style="color:var(--orange);"></i> Build Muscle
        </label>
        <label class="pill goal-pill" data-service="Personal Training" style="display:none;">
            <input type="radio" name="fitness_goal" value="Strength & Conditioning">
            <i class="bi bi-bar-chart-fill" style="color:var(--orange);"></i> Strength &amp; Conditioning
        </label>
        <label class="pill goal-pill" data-service="Personal Training" style="display:none;">
            <input type="radio" name="fitness_goal" value="Athletic Performance">
            <i class="bi bi-trophy-fill" style="color:var(--orange);"></i> Athletic Performance
        </label>
        <label class="pill goal-pill" data-service="Personal Training" style="display:none;">
            <input type="radio" name="fitness_goal" value="Weight Loss & Healthy Lifestyle">
            <i class="bi bi-heart-fill" style="color:var(--orange);"></i> Weight Loss &amp; Healthy Lifestyle
        </label>
        <label class="pill goal-pill" data-service="Personal Training" style="display:none;">
            <input type="radio" name="fitness_goal" value="Flexibility & Mobility">
            <i class="bi bi-arrows-fullscreen" style="color:var(--orange);"></i> Flexibility &amp; Mobility
        </label>
        <label class="pill goal-pill" data-service="Personal Training" style="display:none;">
            <input type="radio" name="fitness_goal" value="Posture & Core Strength">
            <i class="bi bi-person-standing" style="color:var(--orange);"></i> Posture &amp; Core Strength
        </label>

        {{-- Pilates goals --}}
        <label class="pill goal-pill" data-service="Pilates" style="display:none;">
            <input type="radio" name="fitness_goal" value="Flexibility & Mobility">
            <i class="bi bi-arrows-fullscreen" style="color:var(--orange);"></i> Flexibility &amp; Mobility
        </label>
        <label class="pill goal-pill" data-service="Pilates" style="display:none;">
            <input type="radio" name="fitness_goal" value="Posture & Core Strength">
            <i class="bi bi-person-standing" style="color:var(--orange);"></i> Posture &amp; Core Strength
        </label>

       {{-- Open Gym goals - all except Pilates-specific --}}
        <label class="pill goal-pill" data-service="Open Gym Access" style="display:none;">
            <input type="radio" name="fitness_goal" value="Build Muscle">
            <i class="bi bi-lightning-charge-fill" style="color:var(--orange);"></i> Build Muscle
        </label>
        <label class="pill goal-pill" data-service="Open Gym Access" style="display:none;">
            <input type="radio" name="fitness_goal" value="Strength & Conditioning">
            <i class="bi bi-bar-chart-fill" style="color:var(--orange);"></i> Strength &amp; Conditioning
        </label>
        <label class="pill goal-pill" data-service="Open Gym Access" style="display:none;">
            <input type="radio" name="fitness_goal" value="Athletic Performance">
            <i class="bi bi-trophy-fill" style="color:var(--orange);"></i> Athletic Performance
        </label>
        <label class="pill goal-pill" data-service="Open Gym Access" style="display:none;">
            <input type="radio" name="fitness_goal" value="Weight Loss & Healthy Lifestyle">
            <i class="bi bi-heart-fill" style="color:var(--orange);"></i> Weight Loss &amp; Healthy Lifestyle
        </label>
        <label class="pill goal-pill" data-service="Open Gym Access" style="display:none;">
            <input type="radio" name="fitness_goal" value="Flexibility & Mobility">
            <i class="bi bi-arrows-fullscreen" style="color:var(--orange);"></i> Flexibility &amp; Mobility
        </label>
        <label class="pill goal-pill" data-service="Open Gym Access" style="display:none;">
            <input type="radio" name="fitness_goal" value="Posture & Core Strength">
            <i class="bi bi-person-standing" style="color:var(--orange);"></i> Posture &amp; Core Strength
        </label>
</label>
    </div>
</div>

{{-- 4. Fitness Level --}}
<div class="field" id="levelSection" style="display:none;">
    <label>What's your current fitness level? <span class="req">*</span></label>
    <div class="level-group" id="levelCards">
        <label class="level-card" data-value="beginner">
            <input type="radio" name="exercise_experience" value="beginner" required>
            <div class="icon"><i class="bi bi-circle-fill" style="color:#22c55e;font-size:18px;"></i></div>
            <div class="title">Fundamentals</div>
            <div class="desc">New to exercise or returning after a long break</div>
        </label>
        <label class="level-card" data-value="intermediate">
            <input type="radio" name="exercise_experience" value="intermediate">
            <div class="icon"><i class="bi bi-circle-fill" style="color:var(--orange);font-size:18px;"></i></div>
            <div class="title">Mid-Level</div>
            <div class="desc">Active 1–3x per week with some gym experience</div>
        </label>
        <label class="level-card" data-value="advanced">
            <input type="radio" name="exercise_experience" value="advanced">
            <div class="icon"><i class="bi bi-circle-fill" style="color:#eab308;font-size:18px;"></i></div>
            <div class="title">Advanced</div>
            <div class="desc">Consistent training, comfortable with intensity</div>
        </label>
    </div>
</div>

{{-- 5. Emergency Contact --}}
<div class="field" id="emergencySection" style="display:none;">
    <div style="margin-top:8px; padding-top:20px; border-top:1px solid var(--border-soft);">
        <h3 style="font-size:14px; font-weight:700; margin:0 0 16px;">Emergency Contact</h3>
        <div class="field-row">
            <div class="field">
                <label>Contact Name <span class="req">*</span></label>
                <input type="text" name="emergency_contact_name" placeholder="e.g. Juan dela Cruz">
            </div>
            <div class="field">
                <label>Relationship <span class="req">*</span></label>
                <input type="text" name="emergency_contact_relationship" placeholder="e.g. Parent, Spouse">
            </div>
        </div>
        <div class="field">
            <label>Contact Number <span class="req">*</span></label>
            <input type="tel" name="emergency_contact_number" placeholder="09XXXXXXXXX">
        </div>
    </div>
</div>

<input type="hidden" name="currently_exercising" value="1">
<input type="hidden" name="exercise_frequency" value="">
<input type="hidden" name="type" value="{{ request('type', 'guest') }}">

<div class="btn-row">
    <span class="login-hint">Already a member? <a href="#">Log in here</a></span>
    <button type="submit" class="btn-primary">Continue →</button>
</div>

<script>
    // Combine first + last name
    document.getElementById('assessmentForm').addEventListener('submit', function () {
        const first = this.first_name.value.trim();
        const last  = this.last_name.value.trim();
        document.getElementById('full_name').value = (first + ' ' + last).trim();
    });

    const serviceSelect  = document.getElementById('interestedIn');
    const goalSection    = document.getElementById('goalSection');
    const levelSection   = document.getElementById('levelSection');
    const emergencySection = document.getElementById('emergencySection');

    function filterGoals(selectedService) {
        // Hide goal section if no service selected
        if (!selectedService) {
            goalSection.style.display    = 'none';
            levelSection.style.display   = 'none';
            emergencySection.style.display = 'none';
            return;
        }

        // Show goal section
        goalSection.style.display = 'block';

        // Hide all pills, clear selection
        document.querySelectorAll('.goal-pill').forEach(pill => {
            pill.style.display = 'none';
            pill.classList.remove('selected');
            pill.querySelector('input').checked = false;
        });

        // Show only matching service pills
        document.querySelectorAll(`.goal-pill[data-service="${selectedService}"]`).forEach(pill => {
            pill.style.display = 'flex';
        });

        // Show level + emergency after goal section appears
        levelSection.style.display    = 'block';
        emergencySection.style.display = 'block';
    }

    // Run on load
    filterGoals(serviceSelect.value);

    // Run on change
    serviceSelect.addEventListener('change', function () {
        filterGoals(this.value);
    });

    // Goal pill single-select
    document.querySelectorAll('#goalPills .goal-pill').forEach(function (pill) {
        pill.addEventListener('click', function () {
            document.querySelectorAll('#goalPills .goal-pill').forEach(p => p.classList.remove('selected'));
            this.classList.add('selected');
            this.querySelector('input').checked = true;
        });
    });

    // Level card single-select
    document.querySelectorAll('#levelCards .level-card').forEach(function (card) {
        card.addEventListener('click', function () {
            document.querySelectorAll('#levelCards .level-card').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            this.querySelector('input').checked = true;
        });
    });
</script>

    </div>

@endsection

@section('sidebar')
    @include('partials.sidebar')
@endsection