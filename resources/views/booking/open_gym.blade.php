@extends('layouts.booking')

@section('title', 'NexFit - Open Gym Access')

@php $currentStep = 3; @endphp

@section('content')

<div class="main-card">

    <div class="card-head">
        <div class="icon"><i class="bi bi-door-open-fill"></i></div>
        <div>
            <h2>Open Gym Access</h2>
            <p>No trainer needed — just pick when you'll drop by</p>
        </div>
    </div>

    <form method="GET" action="/booking/review">

        <input type="hidden" name="fitness_assessment_id" value="{{ $assessment->id ?? session('assessment_id') }}">
        <input type="hidden" name="service_id" value="{{ $service->id }}">

        <div class="field">
            <label>Gym Operating Hours</label>
            <input type="text" value="5:00 AM – 10:00 PM" disabled style="background:#f3f0e6;">
        </div>

        <div class="field-row">
            <div class="field">
                <label>Preferred Visit Date <span class="req">*</span></label>
                <input type="date" name="booking_date" min="{{ now()->toDateString() }}" required>
            </div>
            <div class="field">
                <label>Estimated Time of Arrival <span class="req">*</span></label>
                <input type="time" name="booking_time" min="05:00" max="22:00" required>
            </div>
        </div>

        <div class="btn-row">
            <a href="/booking/assessment" class="btn-secondary">← Back</a>
            <button type="submit" class="btn-primary">Review Booking →</button>
        </div>
    </form>
</div>

@endsection

@section('sidebar')
    @include('partials.sidebar', [
        'name'        => $assessment->full_name ?? null,
        'programName' => 'Open Gym Access',
    ])
@endsection