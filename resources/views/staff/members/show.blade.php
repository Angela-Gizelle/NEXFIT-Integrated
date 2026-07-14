@extends('layouts.staff')

@section('title', $member->full_name)
@section('page-title', $member->full_name)
@section('page-subtitle')
    <a href="{{ route('members.index') }}">
        <i class="bi bi-arrow-left"></i> Back to Members
    </a>
@endsection

@section('topbar-actions')
    @if($member->isNearExpiry())
        <a href="{{ route('members.renew', $member) }}" class="btn btn-orange">
            <i class="bi bi-arrow-repeat"></i> Renew
        </a>
    @endif
    <a href="{{ route('members.edit', $member) }}" class="btn btn-outline-secondary">
        <i class="bi bi-pencil"></i> Edit
    </a>
    <button type="button"
            class="btn btn-outline-danger js-delete-btn"
            data-url="{{ route('members.destroy', $member) }}"
            data-name="{{ $member->full_name }}">
        <i class="bi bi-trash"></i> Delete
    </button>
@endsection

@push('styles')
<style>
    /* Wide container that still breathes */
    .show-page { max-width: 1440px; margin-inline: auto; }

    /* Compact profile banner */
    .show-page .profile-header {
        padding: 16px 20px;
        margin-bottom: 20px !important;
        border-radius: 14px;
        gap: 16px;
    }
    .show-page .profile-header .avatar { width: 56px; height: 56px; font-size: 1.25rem; }

    /* Two-column with right column gets more real estate on XL */
    .show-grid { --bs-gutter-x: 24px; --bs-gutter-y: 24px; }
    @media (min-width: 1200px) {
        .show-grid > .show-col-profile { flex: 0 0 360px; max-width: 360px; }
        .show-grid > .show-col-main    { flex: 1 1 0;    max-width: none; }
    }

    /* Card chrome — tighter, consistent */
    .show-page .card { border-radius: 14px; }
    .show-page .card-header { padding: 14px 18px; }
    .show-page .card-body   { padding: 18px 20px; }
    .show-page .card-title  { font-size: 1rem; font-weight: 700; margin: 0; }

    /* Section labels — stronger hierarchy */
    .show-page .section-label {
        font-size: .82rem;
        font-weight: 700;
        letter-spacing: .02em;
        color: var(--text-strong);
        margin: 0 0 12px;
        padding-bottom: 6px;
        border-bottom: 1px solid var(--border, rgba(255,255,255,.08));
        display: flex; align-items: center; gap: 8px;
    }
    .show-page .section-label::before {
        content: ""; width: 3px; height: 12px; border-radius: 2px;
        background: var(--orange, #f97316);
    }

    /* Profile card info rows: 2-up on wider screens to fit more */
    .profile-info-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
    }
    @media (min-width: 480px) {
        .profile-info-grid { grid-template-columns: 1fr 1fr; }
        .profile-info-grid .full-row { grid-column: 1 / -1; }
    }
    .profile-info-grid .info-label { font-size: .72rem; }
    .profile-info-grid .info-value { font-size: .9rem; }

    /* Tables — slightly tighter rows */
    .show-page .table > :not(caption) > * > * { padding-top: .7rem; padding-bottom: .7rem; }

    /* Reduce gap between stacked cards in main column */
    .show-col-main .card + .card { margin-top: 24px !important; }
</style>
@endpush

@section('content')

<div class="show-page">

    @if($member->isNearExpiry())
        <div class="alert alert-expiry mb-3">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                @if($member->activePackage)
                    <strong>Membership expiring soon</strong> &mdash; coverage ends on
                    {{ $member->activePackage->coverage_end->format('M d, Y') }}. Please follow up for renewal.
                @else
                    <strong>No active membership package</strong> &mdash; this member's membership has expired.
                @endif
            </div>
        </div>
    @endif

    {{-- Profile banner --}}
    <div class="profile-header">
        <div class="avatar">{{ strtoupper(substr($member->full_name, 0, 1)) }}</div>
        <div class="flex-grow-1 min-w-0">
            <div class="fw-bold fs-5" style="color:var(--text-strong);">{{ $member->full_name }}</div>
            <div class="meta-pills">
                <span class="badge badge-{{ strtolower($member->status) }}">{{ $member->status }}</span>
                @if($member->population_class === 'Special')
                    <span class="badge badge-special">Special Population</span>
                @else
                    <span class="badge badge-inactive no-dot">General Population</span>
                @endif
                <span class="badge badge-info">{{ $member->fitness_level }}</span>
                @if($member->activePackage)
                    <span class="badge badge-active">{{ $member->activePackage->credits_remaining }} credits left</span>
                @endif
            </div>
        </div>
        <div class="d-none d-sm-flex flex-column align-items-end gap-1" style="font-size:.78rem; color:var(--text-faint);">
            <div>Enrolled {{ $member->enrollment_date->format('M d, Y') }}</div>
            @if($member->activePackage)
                <div>Expires {{ $member->activePackage->coverage_end->format('M d, Y') }}</div>
            @endif
        </div>
    </div>

    <div class="row show-grid">
        {{-- Profile card --}}
        <div class="col-12 col-lg-4 show-col-profile">
            <div class="card h-100">
                <div class="card-body">
                    <p class="section-label">Contact &amp; Profile</p>

                    <div class="profile-info-grid">
                        <div class="full-row">
                            <div class="info-label">Email</div>
                            <div class="info-value">{{ $member->email }}</div>
                        </div>
                        <div>
                            <div class="info-label">Phone</div>
                            <div class="info-value">{{ $member->phone ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="info-label">Enrolled</div>
                            <div class="info-value">{{ $member->enrollment_date->format('M d, Y') }}</div>
                        </div>
                        <div class="full-row">
                            <div class="info-label">Address</div>
                            <div class="info-value">{{ $member->address ?? '—' }}</div>
                        </div>
                        <div>
                            <div class="info-label">Fitness Level</div>
                            <div class="info-value">{{ $member->fitness_level }}</div>
                        </div>
                        <div>
                            <div class="info-label">Population</div>
                            <div class="info-value">
                                @if($member->population_class === 'Special')
                                    <span class="badge badge-special">Special</span>
                                @else
                                    General
                                @endif
                            </div>
                        </div>
                        <div class="full-row">
                            <div class="info-label">Assigned Trainer</div>
                            <div class="info-value">{{ $member->trainer?->name ?? '—' }}</div>
                        </div>
                        @if($member->emergency_contact_name)
                        <div class="full-row">
                            <div class="info-label">Emergency Contact</div>
                            <div class="info-value">{{ $member->emergency_contact_name }} &mdash; {{ $member->emergency_contact_phone ?? '—' }}</div>
                        </div>
                        @endif
                    </div>

                    @if($member->healthFlags->count())
                        <p class="section-label" style="margin-top: 20px;">Health Flags</p>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($member->healthFlags as $flag)
                                <span class="badge badge-special" title="{{ $flag->notes }}">{{ $flag->condition }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8 show-col-main">
            {{-- Package history REQ-MM-02 --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="bi bi-box-seam"></i> Package History</div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0" id="packageHistoryTable" data-page-size="5">
                            <thead>
                                <tr>
                                    <th class="ps-3">Package</th>
                                    <th>Credits</th>
                                    <th>Coverage</th>
                                    <th>Amount</th>
                                    <th>Mode</th>
                                    <th class="pe-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($member->packages->sortByDesc('coverage_start') as $pkg)
                                <tr>
                                    <td class="ps-3 fw-semibold" style="color:var(--text-strong);">{{ $pkg->package_type }}</td>
                                    <td>{{ $pkg->credits_remaining }} <span style="color:var(--text-faint);">/ {{ $pkg->session_credits }}</span></td>
                                    <td>{{ $pkg->coverage_start->format('M d') }} &ndash; {{ $pkg->coverage_end->format('M d, Y') }}</td>
                                    <td>&#8369;{{ number_format($pkg->amount_paid, 2) }}</td>
                                    <td>{{ $pkg->payment_mode }}</td>
                                    <td class="pe-3">
                                        @if($pkg->status === 'Active')
                                            <span class="badge badge-active">Active</span>
                                        @else
                                            <span class="badge badge-expired">Expired</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center py-4" style="color:var(--text-faint);">No packages recorded.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($member->packages->count() > 5)
                <div class="card-footer d-flex flex-wrap align-items-center justify-content-between gap-2" id="packageHistoryPager"
                     style="padding:.7rem 1rem;border-top:1px solid var(--border);background:transparent;">
                    <div class="info-label mb-0" id="packageHistoryInfo">&nbsp;</div>
                    <div class="d-flex align-items-center gap-1">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-page-action="prev">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <div class="d-flex align-items-center gap-1" id="packageHistoryPages"></div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-page-action="next">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
                @endif
            </div>

            {{-- ParQ REQ-MM-05 --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="bi bi-clipboard2-pulse"></i> ParQ / Health Assessment</div>
                    @if($member->latestParq)
                        <span class="info-label mb-0">Assessed {{ $member->latestParq->assessment_date->format('M d, Y') }}</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($member->latestParq)
                        @php $parq = $member->latestParq; @endphp
                        <div class="row g-3">
                            @php
                                $fields = [
                                    'Heart condition'     => $parq->heart_condition,
                                    'Chest pain (activity)' => $parq->chest_pain_activity,
                                    'Chest pain (rest)'   => $parq->chest_pain_rest,
                                    'Dizziness/balance'   => $parq->dizziness_balance,
                                    'Bone/joint problem'  => $parq->bone_joint_condition,
                                    'On BP medication'    => $parq->blood_pressure_medication,
                                    'Other condition'     => $parq->other_medical_reason,
                                ];
                            @endphp
                            @foreach($fields as $label => $val)
                                <div class="col-6 col-md-4 col-xl-2">
                                    <div class="info-label">{{ $label }}</div>
                                    <div class="info-value">
                                        @if($val)
                                            <span class="badge badge-special">Yes</span>
                                        @else
                                            <span class="badge badge-active">No</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            @if($parq->additional_notes)
                            <div class="col-12">
                                <div class="info-label">Notes</div>
                                <div class="info-value" style="white-space:pre-line;">{{ $parq->additional_notes }}</div>
                            </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-3" style="color:var(--text-faint);">
                            <i class="bi bi-clipboard-x fs-3 d-block mb-2"></i>
                            No ParQ on file yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
@if(session('success'))
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        iconColor: '#16a34a',
        title: @json(session('success')),
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        customClass: { popup: 'nexfit-swal' },
    });
</script>
@endif
<script>
(function(){
    const table = document.getElementById('packageHistoryTable');
    if(!table) return;
    const pager = document.getElementById('packageHistoryPager');
    if(!pager) return;
    const pageSize = parseInt(table.dataset.pageSize, 10) || 5;
    const rows = Array.from(table.tBodies[0].rows).filter(r => r.cells.length > 1);
    if(rows.length <= pageSize){ pager.style.display = 'none'; return; }
    const totalPages = Math.ceil(rows.length / pageSize);
    const info  = document.getElementById('packageHistoryInfo');
    const pages = document.getElementById('packageHistoryPages');
    const prev  = pager.querySelector('[data-page-action="prev"]');
    const next  = pager.querySelector('[data-page-action="next"]');
    let current = 1;

    function render(){
        const start = (current - 1) * pageSize;
        const end   = start + pageSize;
        rows.forEach((r, i) => { r.style.display = (i >= start && i < end) ? '' : 'none'; });
        info.textContent = `Showing ${start + 1}–${Math.min(end, rows.length)} of ${rows.length} packages`;
        prev.disabled = current === 1;
        next.disabled = current === totalPages;
        pages.innerHTML = '';
        for(let p = 1; p <= totalPages; p++){
            const b = document.createElement('button');
            b.type = 'button';
            b.className = 'btn btn-sm ' + (p === current ? 'btn-primary' : 'btn-outline-secondary');
            b.textContent = p;
            b.addEventListener('click', () => { current = p; render(); });
            pages.appendChild(b);
        }
    }
    prev.addEventListener('click', () => { if(current > 1){ current--; render(); } });
    next.addEventListener('click', () => { if(current < totalPages){ current++; render(); } });
    render();
})();
</script>
@endpush
