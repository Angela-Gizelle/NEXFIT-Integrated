{{--
    Reusable booking calendar grid — weekly view (Monday–Sunday) for one trainer.
    Expects: $weekDates (Collection<Carbon> Mon..Sun), $trainers (Collection<User>),
             $selectedTrainerId (int|null), $selectedTrainer (User|null), $slots (array),
             $sessions (Collection keyed by "Y-m-d_H:i:s"), $members (Collection<Member>)
    Optional: $calRouteName (string, defaults to current route name) — the route
              used for the date-nav / trainer-select links, so this partial can
              be dropped into any page that has its own date-aware GET route.
--}}
@php
    $calRouteName = $calRouteName ?? Route::currentRouteName();
    $weekStartDate = $weekDates->first();
    $weekEndDate   = $weekDates->last();
@endphp

<div class="nf-card">

    {{-- Week nav + trainer select + legend --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route($calRouteName, array_merge(request()->except('date'), ['date' => $weekStartDate->copy()->subWeek()->toDateString()])) }}"
               class="nf-page-btn"><i class="bi bi-chevron-left"></i></a>

            <a href="{{ route($calRouteName, request()->except('date')) }}" class="btn nf-btn-ghost d-flex align-items-center gap-2">
                <i class="bi bi-calendar3"></i>
                <span>{{ $weekStartDate->format('M d') }} – {{ $weekEndDate->format('M d, Y') }}</span>
            </a>

            <a href="{{ route($calRouteName, array_merge(request()->except('date'), ['date' => $weekStartDate->copy()->addWeek()->toDateString()])) }}"
               class="nf-page-btn"><i class="bi bi-chevron-right"></i></a>
        </div>

        @if($trainers->isNotEmpty())
            <form method="GET" action="{{ route($calRouteName) }}" class="d-flex align-items-center gap-2">
                @if(request('date'))
                    <input type="hidden" name="date" value="{{ request('date') }}">
                @endif
                <label class="nf-label mb-0"><i class="bi bi-person-badge me-1 text-accent"></i>Trainer</label>
                <select name="trainer_id" class="form-select nf-input" onchange="this.form.submit()">
                    @foreach($trainers as $trainer)
                        <option value="{{ $trainer->id }}" @selected($trainer->id === $selectedTrainerId)>
                            {{ $trainer->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        @endif

        <div class="d-flex align-items-center gap-3 nf-cal-legend">
            <span><i class="nf-legend-dot nf-legend-available"></i> Available</span>
            <span><i class="nf-legend-dot nf-legend-booked"></i> Booked</span>
            <span><i class="nf-legend-dot nf-legend-conducted"></i> Conducted</span>
            <span><i class="nf-legend-dot nf-legend-pending"></i> Pending</span>
        </div>
    </div>

    @if($trainers->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-person-x nf-empty-icon"></i>
            <p class="nf-empty-text mt-2">No trainers are set up yet. Add a trainer account to start booking sessions.</p>
        </div>
    @else
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
                        $session   = $sessions->get($day->toDateString() . '_' . $slot['time']);
                        $cellId    = 'cell_' . $selectedTrainerId . '_' . $day->format('Ymd') . '_' . str_replace(':', '', $slot['time']) . '_' . md5($calRouteName);
                        $slotEnd   = \Carbon\Carbon::parse($day->toDateString() . ' ' . $slot['time']);
                        $isPast    = $slotEnd->isPast();
                        $isEnded   = $isPast && $session && in_array($session->status, ['confirmed', 'pending']);
                    @endphp

                    @if(!$session && $isPast)
                        {{-- Slot time has already passed and nothing was booked — no longer offered. --}}
                        <div class="nf-cal-slot nf-cal-slot-unavailable">
                            <i class="bi bi-slash-circle"></i> Unavailable
                        </div>

                    @elseif(!$session)
                        {{-- Available slot: click to book --}}
                        <button type="button"
                                class="nf-cal-slot nf-cal-slot-available"
                                data-bs-toggle="modal"
                                data-bs-target="#bookModal_{{ $cellId }}">
                            <i class="bi bi-plus-lg"></i> Available
                        </button>

                        {{-- Quick-book modal for this slot --}}
                        <div class="modal fade" id="bookModal_{{ $cellId }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content nf-modal">
                                    <div class="modal-header nf-modal-header">
                                        <h5 class="modal-title">
                                            <i class="bi bi-calendar-check me-2"></i>Book Session
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('session-management.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="trainer_id" value="{{ $selectedTrainerId }}">
                                        <input type="hidden" name="session_date" value="{{ $day->toDateString() }}">
                                        <input type="hidden" name="session_time" value="{{ $slot['time'] }}">

                                        <div class="modal-body">
                                            <p class="nf-info-note mb-3">
                                                <strong class="text-accent">{{ $selectedTrainer->name ?? '' }}</strong>
                                                &middot; {{ $slot['label'] }}
                                                &middot; {{ $day->format('D, M d, Y') }}
                                            </p>

                                            <div class="mb-3">
                                                <label class="nf-label">Member <span class="text-accent">*</span></label>
                                                @if($members->isEmpty())
                                                    <p class="nf-info-note mb-2">
                                                        <i class="bi bi-exclamation-triangle me-1 text-warning"></i>
                                                        No members currently have session credits.
                                                    </p>
                                                    <a href="{{ route('package-sales.create') }}" class="btn nf-btn-secondary w-100">
                                                        <i class="bi bi-bag-plus me-1"></i> Record Package Sale
                                                    </a>
                                                @else
                                                    <select name="member_id" class="form-select nf-input" required>
                                                        <option value="">— Select member —</option>
                                                        @foreach($members as $member)
                                                            <option value="{{ $member->id }}">
                                                                {{ $member->full_name }} &mdash; {{ $member->creditBalance->credits_remaining }} credit{{ $member->creditBalance->credits_remaining == 1 ? '' : 's' }} left
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="nf-label">Program <span class="text-accent">*</span></label>
                                                    <select name="program" class="form-select nf-input" required>
                                                        <option value="">— Select —</option>
                                                        <option value="Pilates">Pilates</option>
                                                        <option value="Personal Training">Personal Training</option>
                                                        <option value="Open Gym Access">Open Gym Access</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="nf-label">Level</label>
                                                    <select name="level" class="form-select nf-input">
                                                        <option value="">— N/A —</option>
                                                        <option value="Fundamentals">Fundamentals</option>
                                                        <option value="Mid-Level">Mid-Level</option>
                                                        <option value="Advanced">Advanced</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <label class="nf-label">Remarks</label>
                                                <textarea name="remarks" rows="2" class="form-control nf-input"
                                                          placeholder="Optional notes..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer nf-modal-footer">
                                            <button type="button" class="btn nf-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                                            @if($members->isNotEmpty())
                                                <button type="submit" class="btn nf-btn-primary">
                                                    <i class="bi bi-calendar-check me-1"></i> Confirm Booking
                                                </button>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    @else
                        {{-- Booked slot --}}
                        <a href="{{ route('session-management.show', $session) }}"
                           class="nf-cal-slot nf-cal-slot-booked {{ $isEnded ? 'nf-cal-slot-ended' : 'nf-cal-slot-' . $session->status }}">
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
                        </a>
                    @endif
                @endforeach
            @endforeach

        </div>
    </div>
    @endif
</div>