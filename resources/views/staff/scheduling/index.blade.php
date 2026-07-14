@extends('layouts.scheduling')

@section('title', 'Scheduling')
@section('page-title', 'Scheduling')
@section('page-subtitle')
    <span>{{ $weekStart->format('M j') }} &ndash; {{ $weekEnd->format('M j, Y') }}</span>
    <span style="opacity:.45">&middot;</span>
    <span>{{ collect($bookings)->flatten()->count() }} sessions booked this week</span>
@endsection

@push('styles')
<style>
    /* =====================  SECTION EYEBROW  ===================== */
    .sched-eyebrow {
        display: flex;
        align-items: center;
        gap: .55rem;
        font-weight: 800;
        font-size: .95rem;
        color: var(--text-strong);
        margin-bottom: .65rem;
    }
    .sched-eyebrow-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 26px;
        height: 26px;
        border-radius: 7px;
        background: var(--accent-soft);
        color: var(--accent);
        font-size: .85rem;
        flex-shrink: 0;
    }

    /* =====================  SCHEDULING TOOLBAR  ===================== */
    .sched-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem 1.25rem;
        flex-wrap: wrap;
        padding: .85rem 1.1rem;
        border-bottom: 1px solid var(--border);
    }
    .sched-toolbar-left,
    .sched-toolbar-right {
        display: flex;
        align-items: center;
        gap: .65rem;
        flex-wrap: wrap;
    }
    .sched-nav-group {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
    }
    .sched-nav-group .btn {
        border: 1px solid var(--border-soft);
        border-radius: var(--radius-sm);
        background: var(--surface-2);
        width: 30px;
        height: 30px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .sched-date-badge {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        border: 1px solid var(--border-soft);
        background: var(--surface-2);
        border-radius: var(--radius-sm);
        padding: .38rem .8rem;
        font-weight: 700;
        font-size: .82rem;
        color: var(--text-strong);
        white-space: nowrap;
    }
    .sched-date-badge i { color: var(--accent); }
    .sched-today-link {
        font-size: .78rem;
        font-weight: 700;
        border: 1px solid var(--border-soft);
        border-radius: var(--radius-sm);
        padding: .35rem .7rem;
        background: var(--surface-2);
        color: var(--text-muted);
    }
    .sched-today-link:hover { color: var(--accent); border-color: var(--accent); }

    .sched-trainer-group {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
    }
    .sched-trainer-label {
        font-size: .68rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--text-faint);
    }
    .sched-trainer-group .form-select { max-width: 190px; }

    .sched-legend {
        display: flex;
        align-items: center;
        gap: .9rem;
        flex-wrap: wrap;
        font-size: .72rem;
        font-weight: 600;
        color: var(--text-muted);
        padding-left: .5rem;
        border-left: 1px solid var(--border);
    }
    .sched-legend-item { display: inline-flex; align-items: center; gap: .35rem; }
    .sched-swatch { width: 10px; height: 10px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
    .sw-available { background: var(--success); }
    .sw-booked    { background: var(--info); }
    .sw-conducted { background: var(--neutral); }
    .sw-today     { background: var(--accent); }

    /* =====================  GRID  ===================== */
    .sched-scroll {
        overflow: auto;
        max-height: 72vh;
    }
    .sched-grid {
        display: grid;
        grid-template-columns: 78px repeat(7, minmax(132px, 1fr));
        min-width: 990px;
        background: var(--surface);
    }
    .sched-cell {
        border-bottom: 1px solid var(--border);
        border-right: 1px solid var(--border);
        box-sizing: border-box;
    }
    .sched-grid > .sched-cell:nth-child(8n) { border-right: none; }

    .sched-head {
        position: sticky;
        top: 0;
        z-index: 3;
        background: var(--surface);
        text-align: center;
        padding: .6rem .3rem;
    }
    .sched-head .day-name {
        font-size: .66rem;
        font-weight: 700;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--text-faint);
    }
    .sched-head .day-date {
        font-size: .92rem;
        font-weight: 800;
        color: var(--text-strong);
        margin-top: .1rem;
    }
    .sched-head.is-today .day-name,
    .sched-head.is-today .day-date { color: var(--accent); }
    .sched-head.is-today { background: var(--accent-soft); }

    .sched-corner {
        position: sticky;
        left: 0;
        top: 0;
        z-index: 4;
        background: var(--surface);
    }

    .sched-time {
        position: sticky;
        left: 0;
        z-index: 2;
        background: var(--surface);
        color: var(--text-faint);
        font-size: .68rem;
        font-weight: 600;
        text-align: right;
        padding: .3rem .5rem 0 0;
        white-space: nowrap;
    }
    .sched-time.hour-mark { border-top: 1px solid var(--border-soft); }

    .sched-slot {
        min-height: 56px;
        cursor: pointer;
        position: relative;
        padding: .28rem;
        display: flex;
        align-items: stretch;
    }
    .sched-slot.hour-mark { border-top: 1px solid var(--border-soft); }
    .sched-slot.is-past { cursor: not-allowed; }
    .sched-slot.is-selected .slot-chip { outline: 2px solid var(--accent); outline-offset: -2px; }

    /* ---- chip shared ---- */
    .slot-chip {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: .15rem;
        border-radius: var(--radius-sm);
        border: 1px solid transparent;
        text-align: center;
        padding: .3rem .2rem;
        transition: background .12s ease, border-color .12s ease, transform .12s ease;
    }
    .slot-chip i { font-size: .82rem; line-height: 1; }
    .slot-chip .chip-label {
        font-size: .66rem;
        font-weight: 700;
        letter-spacing: .01em;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }

    /* ---- available ---- */
    .sched-slot.is-available .slot-chip {
        background: var(--success-bg);
        color: var(--success);
    }
    .sched-slot.is-available:hover .slot-chip {
        border-color: var(--success);
        transform: translateY(-1px);
    }

    /* ---- booked ---- */
    .sched-slot.is-booked .slot-chip {
        background: var(--info-bg);
        color: var(--info);
    }
    .sched-slot.is-booked .slot-chip .chip-label { color: var(--text-strong); }
    .sched-slot.is-booked:hover .slot-chip { border-color: var(--info); }

    /* ---- conducted (past + booked) ---- */
    .sched-slot.is-conducted .slot-chip {
        background: var(--neutral-bg);
        color: var(--text-faint);
    }
    .sched-slot.is-conducted .slot-chip .chip-label { color: var(--text-muted); }

    /* ---- unavailable (past + empty) ---- */
    .sched-slot.is-unavailable .slot-chip {
        background: transparent;
        border: 1px dashed var(--border-soft);
        color: var(--text-faint);
    }

    .slot-member { display: none; } /* legacy hook, superseded by .chip-label */

    #bookingModal .modal-content,
    #detailsModal .modal-content {
        background: var(--surface);
        color: var(--text);
        border: 1px solid var(--border);
        border-radius: var(--radius);
    }
    #bookingModal .modal-header,
    #detailsModal .modal-header,
    #bookingModal .modal-footer,
    #detailsModal .modal-footer { border-color: var(--border); }
    #bookingModal .slot-summary,
    #detailsModal .slot-summary {
        background: var(--surface-2);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: .65rem .85rem;
        font-size: .85rem;
        margin-bottom: 1rem;
    }

    @media (max-width: 767px) {
        .sched-toolbar { flex-direction: column; align-items: stretch; }
        .sched-toolbar-left, .sched-toolbar-right { justify-content: space-between; }
        .sched-legend { gap: .6rem; }
        .sched-scroll { max-height: 65vh; }
    }
</style>
@endpush

@section('content')

<div class="sched-eyebrow">
    <span class="sched-eyebrow-icon"><i class="bi bi-calendar2-week-fill"></i></span>
    All Bookings
</div>

<div class="card">
    <div class="sched-toolbar">
        <div class="sched-toolbar-left">
            <div class="sched-nav-group">
                <a class="btn btn-sm" href="{{ route('scheduling.index', ['week' => $prevWeek, 'trainer' => $trainerId]) }}" title="Previous week">
                    <i class="bi bi-chevron-left"></i>
                </a>
                <span class="sched-date-badge"><i class="bi bi-calendar3"></i> {{ $weekStart->format('M j') }} &ndash; {{ $weekEnd->format('M j, Y') }}</span>
                <a class="btn btn-sm" href="{{ route('scheduling.index', ['week' => $nextWeek, 'trainer' => $trainerId]) }}" title="Next week">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
            <a class="sched-today-link" href="{{ route('scheduling.index', ['week' => $todayWeek, 'trainer' => $trainerId]) }}">Today</a>
        </div>

        <div class="sched-toolbar-right">
            <div class="sched-trainer-group">
                <span class="sched-trainer-label">Trainer</span>
                <select id="trainerSelect" class="form-select form-select-sm">
                    @forelse($trainers as $trainer)
                        <option value="{{ $trainer->id }}" @selected($trainer->id === $trainerId)>{{ $trainer->name }}</option>
                    @empty
                        <option value="">No trainers yet</option>
                    @endforelse
                </select>
            </div>

            <div class="sched-legend">
                <span class="sched-legend-item"><span class="sched-swatch sw-available"></span> Available</span>
                <span class="sched-legend-item"><span class="sched-swatch sw-booked"></span> Booked</span>
                <span class="sched-legend-item"><span class="sched-swatch sw-conducted"></span> Conducted</span>
                <span class="sched-legend-item"><span class="sched-swatch sw-today"></span> Today</span>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="sched-scroll">
            <div class="sched-grid" id="schedGrid">
                {{-- Header row --}}
                <div class="sched-cell sched-head sched-corner">Time</div>
                @foreach($days as $day)
                    <div class="sched-cell sched-head {{ $day['isToday'] ? 'is-today' : '' }}">
                        <div class="day-name">{{ $day['label'] }}</div>
                        <div class="day-date">{{ $day['dateLabel'] }}</div>
                    </div>
                @endforeach

                {{-- Time rows --}}
                @foreach($slots as $slot)
                    <div class="sched-cell sched-time {{ $slot['isHour'] ? 'hour-mark' : '' }}">
                        {{ $slot['isHour'] ? $slot['label'] : '' }}
                    </div>

                    @foreach($days as $day)
                        @php
                            $booking = $bookings[$day['date']][$slot['time']] ?? null;
                            if ($day['isPast']) {
                                $stateClass = $booking ? 'is-conducted is-past' : 'is-unavailable is-past';
                            } else {
                                $stateClass = $booking ? 'is-booked' : 'is-available';
                            }
                            $memberName = $booking->member->full_name ?? 'Blocked';
                        @endphp
                        <div
                            class="sched-cell sched-slot {{ $stateClass }} {{ $slot['isHour'] ? 'hour-mark' : '' }}"
                            data-date="{{ $day['date'] }}"
                            data-time="{{ $slot['time'] }}"
                            data-label="{{ $slot['label'] }}"
                            data-day-label="{{ $day['label'] }}, {{ $day['dateLabel'] }}"
                            @if($booking)
                                data-session-id="{{ $booking->id }}"
                                data-member-name="{{ $memberName }}"
                                data-notes="{{ $booking->notes }}"
                                data-start-label="{{ $booking->start_label }}"
                                data-end-label="{{ $booking->end_label }}"
                            @endif
                        >
                            <div class="slot-chip">
                                @if($booking)
                                    <i class="bi {{ $day['isPast'] ? 'bi-check2-circle' : 'bi-person-check-fill' }}"></i>
                                    <span class="chip-label">{{ $memberName }}</span>
                                @elseif($day['isPast'])
                                    <i class="bi bi-slash-circle"></i>
                                    <span class="chip-label">Unavailable</span>
                                @else
                                    <i class="bi bi-plus-lg"></i>
                                    <span class="chip-label">Available</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- =====================  BOOK SESSION MODAL  ===================== --}}
<div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-calendar-plus" style="color:var(--accent)"></i> Book Session</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bookingForm">
                <div class="modal-body">
                    <div id="bookingAlert" class="alert alert-danger d-none" role="alert"></div>

                    <div class="slot-summary" id="bookingSlotSummary"></div>

                    <div class="mb-3">
                        <label class="form-label" for="member_id">Member</label>
                        <select class="form-select" id="member_id" name="member_id" required>
                            <option value="" disabled selected>Select a member&hellip;</option>
                            @foreach($members as $member)
                                <option value="{{ $member->id }}">{{ $member->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-1">
                        <label class="form-label" for="notes">Notes <span style="text-transform:none;font-weight:400;">(optional)</span></label>
                        <textarea class="form-control" id="notes" name="notes" rows="2" maxlength="500" placeholder="e.g. Focus on upper body strength"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-orange" id="bookingSubmitBtn">
                        <i class="bi bi-check-lg"></i> Confirm Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- =====================  SESSION DETAILS MODAL  ===================== --}}
<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-calendar-check" style="color:var(--accent)"></i> Session Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="slot-summary" id="detailsSlotSummary"></div>
                <div class="mb-2">
                    <div class="info-label">Member</div>
                    <div class="info-value" id="detailsMember"></div>
                </div>
                <div class="mb-0" id="detailsNotesWrap">
                    <div class="info-label">Notes</div>
                    <div class="info-value" id="detailsNotes"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-outline-danger" id="cancelSessionBtn">
                    <i class="bi bi-x-circle"></i> Cancel Session
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    const csrfToken   = document.querySelector('meta[name="csrf-token"]').content;
    const trainerId   = {{ $trainerId ?: 'null' }};
    const storeUrl    = '{{ route('scheduling.store') }}';
    const trainerSelect = document.getElementById('trainerSelect');
    const grid        = document.getElementById('schedGrid');

    // ---- Trainer switch: keep the current week, swap trainer ----
    trainerSelect?.addEventListener('change', function () {
        const url = new URL(window.location.href);
        url.searchParams.set('trainer', this.value);
        window.location.href = url.toString();
    });

    // ---- Booking modal ----
    const bookingModalEl = document.getElementById('bookingModal');
    const bookingModal   = new bootstrap.Modal(bookingModalEl);
    const bookingForm    = document.getElementById('bookingForm');
    const bookingAlert   = document.getElementById('bookingAlert');
    const bookingSummary = document.getElementById('bookingSlotSummary');
    const memberSelect   = document.getElementById('member_id');
    const notesInput     = document.getElementById('notes');
    const submitBtn      = document.getElementById('bookingSubmitBtn');

    // ---- Details modal ----
    const detailsModalEl = document.getElementById('detailsModal');
    const detailsModal   = new bootstrap.Modal(detailsModalEl);
    const detailsSummary = document.getElementById('detailsSlotSummary');
    const detailsMember  = document.getElementById('detailsMember');
    const detailsNotes   = document.getElementById('detailsNotes');
    const detailsNotesWrap = document.getElementById('detailsNotesWrap');
    const cancelBtn       = document.getElementById('cancelSessionBtn');

    let activeCell = null;

    grid.addEventListener('click', function (e) {
        const cell = e.target.closest('.sched-slot');
        if (!cell) return;

        if (cell.classList.contains('is-past')) return;

        if (cell.classList.contains('is-available')) {
            openBookingModal(cell);
        } else if (cell.classList.contains('is-booked')) {
            openDetailsModal(cell);
        }
    });

    function openBookingModal(cell) {
        if (!trainerId) {
            alert('Add a trainer before booking sessions.');
            return;
        }
        activeCell = cell;
        bookingForm.reset();
        bookingAlert.classList.add('d-none');
        bookingSummary.innerHTML =
            '<strong>' + cell.dataset.dayLabel + '</strong> &middot; ' + cell.dataset.label;
        cell.classList.add('is-selected');
        bookingModal.show();
    }

    bookingModalEl.addEventListener('hidden.bs.modal', function () {
        activeCell?.classList.remove('is-selected');
    });

    bookingForm.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!activeCell) return;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Booking&hellip;';

        fetch(storeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                trainer_id: trainerId,
                member_id: memberSelect.value,
                session_date: activeCell.dataset.date,
                start_time: activeCell.dataset.time,
                notes: notesInput.value,
            }),
        })
            .then(async (res) => {
                const data = await res.json();
                if (!res.ok) throw data;
                return data;
            })
            .then((data) => {
                markCellBooked(activeCell, data.session);
                bookingModal.hide();
            })
            .catch((err) => {
                const msg = err?.message || (err?.errors && Object.values(err.errors)[0][0]) || 'Could not book that session. Please try again.';
                bookingAlert.textContent = msg;
                bookingAlert.classList.remove('d-none');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-check-lg"></i> Confirm Booking';
            });
    });

    function markCellBooked(cell, session) {
        cell.classList.remove('is-available', 'is-selected');
        cell.classList.add('is-booked');
        cell.dataset.sessionId   = session.id;
        cell.dataset.memberName  = session.member_name;
        cell.dataset.notes       = session.notes || '';
        cell.dataset.startLabel  = session.start_label;
        cell.dataset.endLabel    = session.end_label;
        cell.innerHTML = '<div class="slot-chip"><i class="bi bi-person-check-fill"></i><span class="chip-label">' +
            escapeHtml(session.member_name) + '</span></div>';
    }

    function openDetailsModal(cell) {
        activeCell = cell;
        detailsSummary.innerHTML =
            '<strong>' + cell.dataset.dayLabel + '</strong> &middot; ' +
            cell.dataset.startLabel + ' &ndash; ' + cell.dataset.endLabel;
        detailsMember.textContent = cell.dataset.memberName || '—';

        if (cell.dataset.notes) {
            detailsNotes.textContent = cell.dataset.notes;
            detailsNotesWrap.classList.remove('d-none');
        } else {
            detailsNotesWrap.classList.add('d-none');
        }

        detailsModal.show();
    }

    cancelBtn.addEventListener('click', function () {
        if (!activeCell || !activeCell.dataset.sessionId) return;

        cancelBtn.disabled = true;
        cancelBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Cancelling&hellip;';

        fetch('{{ url('scheduling') }}/' + activeCell.dataset.sessionId, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        })
            .then((res) => {
                if (!res.ok) throw new Error('Could not cancel this session.');
                return res.json();
            })
            .then(() => {
                markCellAvailable(activeCell);
                detailsModal.hide();
            })
            .catch((err) => {
                alert(err.message || 'Something went wrong.');
            })
            .finally(() => {
                cancelBtn.disabled = false;
                cancelBtn.innerHTML = '<i class="bi bi-x-circle"></i> Cancel Session';
            });
    });

    function markCellAvailable(cell) {
        cell.classList.remove('is-booked');
        cell.classList.add('is-available');
        delete cell.dataset.sessionId;
        delete cell.dataset.memberName;
        delete cell.dataset.notes;
        delete cell.dataset.startLabel;
        delete cell.dataset.endLabel;
        cell.innerHTML = '<div class="slot-chip"><i class="bi bi-plus-lg"></i><span class="chip-label">Available</span></div>';
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
})();
</script>
@endpush
