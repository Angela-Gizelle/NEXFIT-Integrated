@extends('layouts.booking')

@section('title', 'NexFit - Choose Schedule')

@php
    $currentStep = session('booking_type') === 'member' ? 3 : 2;
@endphp

@section('content')

<div class="main-card">

    <div class="card-head">
        <div class="icon"><i class="bi bi-calendar3"></i></div>
        <div>
            <h2>Choose Your Schedule</h2>
            <p>Based on your level, we've highlighted the best slot for you</p>
        </div>
    </div>

    @if(optional($availableSlots ?? null)->isNotEmpty())

        <div class="reco-banner">
            <div class="reco-title"><i class="bi bi-star-fill" style="color:var(--orange);"></i> Recommended for you</div>
            Based on your fitness level (<b>{{ $level ?? 'Fundamentals' }}</b>), we recommend the
            <b>{{ \Carbon\Carbon::parse($availableSlots->first()->start_time)->format('g:i A') }}</b> slot.
            @if($trainer)
                This session is led by <b>{{ $trainer->name }}</b>, who specializes in {{ strtolower($level ?? 'beginner') }} {{ $service->name ?? 'training' }} programs.
            @endif
        </div>

        <form method="GET" action="/booking/review" id="scheduleForm">

            <input type="hidden" name="fitness_assessment_id" value="{{ $assessment->id }}">
            <input type="hidden" name="service_id" value="{{ $service->id }}">
            <input type="hidden" name="trainer_id" value="{{ optional($trainer)->id }}">
            <input type="hidden" name="backup_trainer_id" value="{{ optional($backupTrainer)->id }}">
            <input type="hidden" name="program_id" value="{{ optional($program)->id }}">
            <input type="hidden" name="booking_date" id="booking_date">
            <input type="hidden" name="booking_time" id="booking_time">
            <input type="hidden" name="fitness_goal" value="{{ $assessment->fitness_goal }}">

           <div class="field">
             <label>Select Date <span class="req">*</span></label>
             <input type="date"
              id="datePicker"
              name="selected_date"
              min="{{ now()->toDateString() }}"
              value="{{ now()->toDateString() }}"
              style="cursor:pointer;"
              required>
            </div>

            <div class="field">
                <label>Available Slots</label>

                @foreach($availableSlots as $i => $slot)
                    @php
                        $remaining = $slot->max_capacity - $slot->booked_count;
                        $isFull = $remaining <= 0;
                        $isRecommended = $i === 0;
                    @endphp

                    <label class="slot-card {{ $isRecommended ? 'recommended' : '' }} {{ $isFull ? 'full' : '' }}"
                           data-date="{{ $slot->date->format('Y-m-d') }}"
                           data-time="{{ $slot->start_time }}">

                        <input type="radio" name="schedule_id" value="{{ $slot->id }}" {{ $isFull ? 'disabled' : '' }} {{ $isRecommended ? 'checked' : '' }}>

                        <div>
                            <div class="slot-time">
                                {{ \Carbon\Carbon::parse($slot->start_time)->format('g:i') }} – {{ \Carbon\Carbon::parse($slot->end_time)->format('g:i A') }}
                                @if($isRecommended)<span class="slot-badge">Recommended</span>@endif
                            </div>
                            <div class="slot-meta">
                                {{ $slot->date->format('D, M j') }} · Trainer {{ optional($slot->trainer)->name ?? 'TBA' }}
                            </div>
                        </div>

                        <div class="slot-right">
                            @if($isFull)
                                <span class="slot-full-label">Full</span>
                            @else
                                <span class="slot-left">{{ $remaining }} slots left</span>
                            @endif
                        </div>
                    </label>
                @endforeach
            </div>

            <div class="btn-row">
                <a href="/booking/parq/{{ $assessment->id }}" class="btn-secondary">← Back</a>
                <button type="submit" class="btn-primary" id="reviewBtn" disabled>Review Booking →</button>
            </div>
        </form>

    @else

        <div class="error-banner">
            No available slots right now for this trainer. Please check back later, or use the walk-in option below.
        </div>

    @endif

</div>

<script>
    const slotCards = document.querySelectorAll('.slot-card:not(.full)');
    const reviewBtn = document.getElementById('reviewBtn');

    function selectSlot(card) {
        slotCards.forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');
        card.querySelector('input[type=radio]').checked = true;
        document.getElementById('booking_date').value = card.dataset.date;
        document.getElementById('booking_time').value = card.dataset.time;
        reviewBtn.disabled = false;
    }

    slotCards.forEach(function (card) {
        card.addEventListener('click', () => selectSlot(card));
        if (card.querySelector('input[type=radio]').checked) selectSlot(card);
    });

    // Reload the page with the selected date as a query param
    document.getElementById('datePicker').addEventListener('change', function () {
        const url = new URL(window.location.href);
        url.searchParams.set('date', this.value);
        window.location.href = url.toString();
    });
</script>

@endsection

@section('sidebar')
    @include('partials.sidebar', [
        'name'        => $assessment->full_name,
        'programName' => optional($program)->name ?? $service->name,
        'bookingDate' => optional($availableSlots->first())->date?->format('F j, Y'),
        'timeSlot'    => $availableSlots->isNotEmpty() ? \Carbon\Carbon::parse($availableSlots->first()->start_time)->format('g:i A') . ' – ' . \Carbon\Carbon::parse($availableSlots->first()->end_time)->format('g:i A') : null,
        'trainerName' => optional($trainer)->name,
    ])
@endsection
