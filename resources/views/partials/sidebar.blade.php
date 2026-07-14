{{--
    Reusable sidebar partial. Pass in whatever variables are available
    at each step — undefined ones will just show a muted dash, matching
    how the reference design shows "—" before fields are filled in.
--}}

<div class="summary-card">
    <div class="summary-head"><i class="bi bi-clipboard2-check-fill" style="color:var(--orange);"></i> Your Booking</div>
    <div class="summary-body">

        <div class="summary-row">
            <span class="k">Name</span>
            <span class="v">{{ $name ?? '—' }}</span>
        </div>

        <div class="summary-row">
            <span class="k">Program</span>
            <span class="v accent">{{ $programName ?? '—' }}</span>
        </div>

        <div class="summary-row">
            <span class="k">Date</span>
            <span class="v blue">{{ $bookingDate ?? '—' }}</span>
        </div>

        <div class="summary-row">
            <span class="k">Time Slot</span>
            <span class="v blue">{{ $timeSlot ?? '—' }}</span>
        </div>

        <div class="summary-row">
            <span class="k">Trainer</span>
            <span class="v">{{ $trainerName ?? '—' }}</span>
        </div>

        <div class="summary-row">
            <span class="k">Studio</span>
            <span class="v">Fit Urban, San Jose</span>
        </div>

    </div>
</div>

<div class="next-card">
    <h4>What happens next?</h4>
    <ul>
        <li><span class="ico"><i class="bi bi-check-circle-fill" style="color:#22c55e;"></i></span> Your slot will be reserved</li>
        <li><span class="ico"><i class="bi bi-telephone-fill" style="color:var(--orange);"></i></span> Staff will contact you within 24 hrs</li>
        <li><span class="ico"><i class="bi bi-credit-card-fill" style="color:var(--orange);"></i></span> Package payment done at the studio</li>
        <li><span class="ico"><i class="bi bi-robot" style="color:var(--orange);"></i></span> AI training plan generated after booking</li>
    </ul>
</div>
