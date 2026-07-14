@extends('layouts.booking')

@section('title', 'NexFit - Review & Confirm')

@php
    $currentStep = session('booking_type') === 'member' ? 4 : 3;
@endphp

@section('content')

<div class="main-card">

    <div class="card-head">
        <div class="icon"><i class="bi bi-check-circle-fill"></i></div>
        <div>
            <h2>Review & Confirm</h2>
            <p>Please check your booking details before submitting</p>
        </div>
    </div>

    <form method="POST" action="/booking" id="confirmForm">
        @csrf

        @foreach($hiddenFields ?? [] as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach

        <div class="parq-box" style="background:#FBF6E9;">
            <div class="parq-title" style="color:#8A7A4E; text-transform:uppercase; letter-spacing:0.5px; font-size:11px;">Booking Summary</div>

            <div class="summary-row" style="border-top:1px solid #EFE2B8; padding-top:14px; margin-top:8px;">
                <span class="k">Name</span><span class="v">{{ $assessment->full_name }}</span>
            </div>
            <div class="summary-row">
                <span class="k">Program</span><span class="v">{{ $service->name ?? '—' }}</span>
            </div>
            <div class="summary-row">
                <span class="k">Date</span><span class="v">{{ \Carbon\Carbon::parse($bookingDate)->format('F j, Y') }}</span>
            </div>
            <div class="summary-row">
                <span class="k">Time Slot</span><span class="v">{{ \Carbon\Carbon::parse($bookingTime)->format('g:i A') }}</span>
            </div>
            @if($trainer ?? null)
                <div class="summary-row">
                    <span class="k">Trainer</span><span class="v">{{ $trainer->name }}</span>
                </div>
            @endif
            <div class="summary-row">
                <span class="k">Fitness Level</span><span class="v">{{ strtolower($level ?? 'beginner') }}</span>
            </div>
            <div class="summary-row">
                <span class="k">Goal</span><span class="v">{{ $goal ?? '—' }}</span>
            </div>
        </div>

        {{-- Medical Clearance Warning (shown only if PAR-Q had a YES answer) --}}
@if(session('parq_has_risk'))
<div style="background:#FFF8E6; border:1px solid #F0C070; border-radius:9px; padding:18px 20px; margin-bottom:16px; font-size:12.5px; line-height:1.7; color:#5A4010;">
    <div style="font-weight:700; color:#9A6A0E; margin-bottom:8px;"><i class="bi bi-exclamation-triangle-fill"></i> You answered YES to one or more PAR-Q questions</div>
    <p style="margin:0 0 10px;">You are strongly advised not to begin any fitness program until you have obtained clearance from a qualified medical professional.</p>
    <p style="margin:0 0 10px;">You may be required to submit one or more of the following prior to participating:</p>
    <ul style="margin:0 0 10px; padding-left:18px;">
        <li>A Medical Clearance Certificate or written consent from your physician stating that you are fit to exercise</li>
        <li>A specific exercise prescription from your healthcare provider outlining limitations or recommended modifications</li>
        <li>A follow-up consultation or health assessment by a licensed medical professional, if requested by the Club</li>
    </ul>
    <p style="margin:0;">Failure to obtain and present medical clearance may result in restricted access to the Club's fitness areas, classes, equipment, or services.</p>
</div>
@endif

{{-- Fit Urban Rights & Safety Policy --}}
<div style="background:#F9F6EE; border:1px solid #E7E0CF; border-radius:9px; padding:18px 20px; margin-bottom:16px; font-size:12.5px; line-height:1.7; color:#4A4235;">
    <div style="font-weight:700; margin-bottom:8px;">Fit Urban Rights & Safety Policy</div>
    <p style="margin:0 0 8px;">FIT URBAN reserves the right to:</p>
    <ul style="margin:0; padding-left:18px;">
        <li>Delay or deny access to gym facilities or services for any Member whose health condition may pose a risk to themselves or others</li>
        <li>Request updated health declarations or medical documentation as part of its ongoing commitment to safety and responsible fitness practices</li>
    </ul>
</div>

{{-- Member Declaration --}}
<div style="background:#F9F6EE; border:1px solid #E7E0CF; border-radius:9px; padding:18px 20px; margin-bottom:16px; font-size:12.5px; line-height:1.7; color:#4A4235;">
    <div style="font-weight:700; margin-bottom:8px;">Member Declaration</div>
    <p style="margin:0;">I certify that the above information is true and correct to the best of my knowledge. I acknowledge that I have disclosed all relevant health conditions that may impact my ability to participate in physical activities at FIT URBAN. I understand that I am voluntarily assuming all risks associated with physical exercise, including but not limited to injury, illness, or exacerbation of a pre-existing medical condition.</p>
</div>

<label style="display:flex; align-items:flex-start; gap:8px; font-size:12.5px; color:#4A4235; margin-top:14px; cursor:pointer;">
    <input type="checkbox" name="member_declaration_signed" value="1" required style="margin-top:2px;">
    I have read and agree to the Member Declaration, Fit Urban Rights & Safety Policy, and acknowledge the medical clearance requirement above.
</label> 

<label style="display:flex; align-items:flex-start; gap:8px; font-size:12.5px; color:#4A4235; margin-top:14px; cursor:pointer;">
     <input type="checkbox" name="member_declaration_signed" value="1" required style="margin-top:2px;">
            I agree that my health and contact information will be used by Fit Urban for training
            and scheduling purposes, in accordance with the Philippine Data Privacy Act (RA 10173).
     </label>

        <input type="hidden" name="medical_clearance_acknowledged" value="1">
        <input type="hidden" name="rights_policy_acknowledged" value="1">

        <div class="btn-row">
            <a href="/booking/program" class="btn-secondary">← Back</a>
            <button type="submit" class="btn-primary green"><i class="bi bi-check-lg"></i> Confirm Booking</button>
        </div>
    </form>
</div>

@endsection

@section('sidebar')
    @include('partials.sidebar', [
        'name'        => $assessment->full_name,
        'programName' => $service->name ?? '—',
        'bookingDate' => \Carbon\Carbon::parse($bookingDate)->format('F j, Y'),
        'timeSlot'    => \Carbon\Carbon::parse($bookingTime)->format('g:i A'),
        'trainerName' => optional($trainer ?? null)->name,
    ])
@endsection
