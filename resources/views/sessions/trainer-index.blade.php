@extends('layouts.trainer')

@section('title', 'My Schedule')
@section('page-title', 'My Schedule')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/nexfit.css') }}">
@endpush

@section('content')
<div class="pt-3">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="nf-page-title mb-0">My Schedule</h1>
            <p class="nf-page-sub mb-0">Read-only view of your booked sessions for the week</p>
        </div>
    </div>

    <div class="nf-card">

        {{-- Week nav + legend --}}
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('trainer.sessions', ['date' => $weekStart->copy()->subWeek()->toDateString()]) }}"
                   class="nf-page-btn"><i class="bi bi-chevron-left"></i></a>

                <a href="{{ route('trainer.sessions') }}" class="btn nf-btn-ghost d-flex align-items-center gap-2">
                    <i class="bi bi-calendar3"></i>
                    <span>{{ $weekStart->format('M d') }} – {{ $weekEnd->format('M d, Y') }}</span>
                </a>

                <a href="{{ route('trainer.sessions', ['date' => $weekStart->copy()->addWeek()->toDateString()]) }}"
                   class="nf-page-btn"><i class="bi bi-chevron-right"></i></a>
            </div>

            <div class="d-flex align-items-center gap-3 nf-cal-legend">
                <span><i class="nf-legend-dot nf-legend-available"></i> Free</span>
                <span><i class="nf-legend-dot nf-legend-booked"></i> Booked</span>
                <span><i class="nf-legend-dot nf-legend-conducted"></i> Conducted</span>
                <span><i class="nf-legend-dot nf-legend-pending"></i> Pending</span>
            </div>
        </div>

        <div class="nf-cal-wrap">
            <div class="nf-cal-grid" style="grid-template-columns: 150px repeat(7, minmax(150px, 1fr));">

                {{-- Header row: one column per day of the week --}}
                <div class="nf-cal-head nf-cal-corner">Time</div>
                @foreach($weekDates as $day)
                    <div class="nf-cal-head @if($day->isToday()) text-accent @endif">
                        {{ $day->format('D') }}<br>
                        <span class="nf-cell-meta">{{ $day->format('M d') }}</span>
                    </div>
                @endforeach

                {{-- Slot rows --}}
                @foreach($slots as $slot)
                    <div class="nf-cal-time">{{ $slot['label'] }}</div>

                    @foreach($weekDates as $day)
                        @php
                            $session = $sessions->get($day->toDateString() . '_' . $slot['time']);
                            $slotEnd = \Carbon\Carbon::parse($day->toDateString() . ' ' . $slot['time']);
                            $isPast  = $slotEnd->isPast();
                            $isEnded = $isPast && $session && in_array($session->status, ['confirmed', 'pending']);
                        @endphp

                        @if(!$session && $isPast)
                            {{-- Slot time has already passed — no longer just "free". --}}
                            <div class="nf-cal-slot nf-cal-slot-unavailable">
                                <i class="bi bi-slash-circle"></i> Unavailable
                            </div>
                        @elseif(!$session)
                            {{-- Free slot: nothing to do here, just show it's open --}}
                            <div class="nf-cal-slot nf-cal-slot-available" style="pointer-events: none;">
                                <i class="bi bi-dash-lg"></i> Free
                            </div>
                        @else
                            {{-- Booked slot: display only — sessions.show is admin/staff-only, so no link here --}}
                            <div class="nf-cal-slot nf-cal-slot-booked {{ $isEnded ? 'nf-cal-slot-ended' : 'nf-cal-slot-' . $session->status }}" style="pointer-events: none;">
                                <span class="nf-cal-slot-name">{{ $session->member->full_name ?? 'Walk-in' }}</span>
                                @if($isEnded)
                                    <span class="nf-badge nf-badge-conducted nf-cal-slot-badge">
                                        <i class="bi bi-circle-fill nf-badge-dot"></i>Ended
                                    </span>
                                @else
                                    <span class="nf-badge nf-badge-{{ $session->status }} nf-cal-slot-badge">
                                        <i class="bi bi-circle-fill nf-badge-dot"></i>{{ ucfirst($session->status) }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    @endforeach
                @endforeach

            </div>
        </div>
    </div>

</div>
@endsection