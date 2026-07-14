@extends('layouts.booking')

@section('title', 'NexFit - Health Check')

@php $currentStep = 2; @endphp

@section('content')

<div class="main-card">

    <div class="card-head">
        <div class="icon"><i class="bi bi-heart-pulse-fill"></i></div>
        <div>
            <h2>Pre-Exercise Health Check</h2>
            <p>Quick health screening to ensure your safety during training</p>
        </div>
    </div>

    <form method="POST" action="/booking/parq">
        @csrf

        <div class="parq-box">
            <div class="parq-title"><i class="bi bi-exclamation-triangle-fill" style="color:#9A6A0E;"></i> Par-Q Screening (Required)</div>
            <div class="parq-sub">Answer Yes or No to each question. This is for your safety and helps us assign the right trainer for you.</div>

            @php
                $questions = [
                    'heart_condition'           => 'Has your doctor ever said that you have a heart condition and that you should only do physical activity recommended by a doctor?',
                    'chest_pain_activity'       => 'Do you feel pain in your chest when you do physical activity? (e.g., walking, climbing stairs, lifting)?',
                    'bone_joint_condition'      => ' Do you have any existing bone, joint, or musculoskeletal condition (e.g., arthritis, past injuries) that could worsen or be triggered by physical activity?',
                    'chest_pain_rest'           => 'Are you pregnant or have given birth in the last 6 months?',
                    'blood_pressure_medication' => 'Do you have high blood pressure or are you currently taking medication?',
                ];
            @endphp

            @foreach($questions as $key => $text)
                <div class="yn-row" data-question="{{ $key }}">
                    <span class="q">{{ $text }}</span>
                    <div class="yn-toggle">
                        <button type="button" class="yn-btn yes" data-key="{{ $key }}" data-val="1">Yes</button>
                        <button type="button" class="yn-btn no" data-key="{{ $key }}" data-val="0">No</button>
                    </div>
                    <input type="hidden" name="{{ $key }}" id="input_{{ $key }}" value="">
                </div>
            @endforeach
        </div>

        <input type="hidden" name="dizziness_balance" value="0">
        <input type="hidden" name="other_medical_reason" value="0">

        {{-- Medical clearance warning — shown via JS when any YES is selected --}}
<div id="medicalWarning" style="display:none; background:#FFF8E6; border:1px solid #F0C070; border-radius:9px; padding:18px 20px; margin:16px 0; font-size:12.5px; line-height:1.7; color:#5A4010;">
    <div style="font-weight:700; color:#9A6A0E; margin-bottom:8px;"><i class="bi bi-exclamation-triangle-fill"></i> You answered YES to one or more questions</div>
    <p style="margin:0 0 10px;">
        This indicates that you may have a health condition or risk factor that could
        potentially affect your ability to engage safely in physical activity.
    </p>
    <p style="margin:0 0 10px;">
        You are strongly advised not to begin any fitness program or use any gym equipment
        until you have obtained clearance from a qualified medical professional. This is not
        only for your safety, but also to ensure that the physical activities and programs
        undertaken at FIT URBAN are suited to your individual health needs.
    </p>
    <p style="margin:0;">
        You may be required to submit one or more of the following prior to participating:
    </p>
    <ul style="margin:8px 0 0; padding-left:18px;">
        <li>A Medical Clearance Certificate or written consent from your physician stating that you are fit to exercise</li>
        <li>A specific exercise prescription from your healthcare provider outlining limitations or recommended modifications</li>
        <li>A follow-up consultation or health assessment by a licensed medical professional, if requested by the Club</li>
    </ul>
</div>

        <div class="field-row">
            <div class="field">
                <label>Age</label>
                <input type="number" name="age" placeholder="e.g. 28">
            </div>
            <div class="field">
                <label>Biological Sex</label>
                <select name="biological_sex">
                    <option value="">Select...</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
        </div>

        <div class="field">
            <label>Any other health notes for your trainer? (optional)</label>
            <textarea name="health_notes" rows="2" placeholder="e.g. recent knee surgery, lower back pain, etc."></textarea>
        </div>

        <div class="btn-row">
            <a href="/booking/assessment" class="btn-secondary">← Back</a>
            <button type="submit" class="btn-primary" id="continueBtn">Continue →</button>
        </div>
    </form>
</div>

<script>
    document.querySelectorAll('.yn-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const key = this.dataset.key;
            const row = document.querySelector('[data-question="' + key + '"]');

            row.querySelectorAll('.yn-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            document.getElementById('input_' + key).value = this.dataset.val;

            // Show medical warning if ANY question has YES selected
            const anyYes = Array.from(
                document.querySelectorAll('.yn-btn.active.yes')
            ).length > 0;

            document.getElementById('medicalWarning').style.display =
                anyYes ? 'block' : 'none';
        });
    });
</script>

@endsection

@section('sidebar')
    @include('partials.sidebar')
@endsection
