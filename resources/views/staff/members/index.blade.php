@extends('layouts.staff')

@section('title', 'Members')
@section('page-title', 'Members')
@section('page-subtitle')
    <span>{{ $members->count() }} total</span>
    <span style="opacity:.45">·</span>
    <span style="color:var(--success)">{{ $activeCount }} active</span>
    <span style="opacity:.45">·</span>
    <span>{{ $inactiveCount }} inactive</span>
@endsection

@section('topbar-actions')
    <form action="{{ route('members.expire') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-outline-secondary" title="Manually mark overdue packages as Expired">
            <i class="bi bi-arrow-repeat"></i> Check Expirations
        </button>
    </form>
    <a href="{{ route('members.create') }}" class="btn btn-orange">
        <i class="bi bi-plus-lg"></i> Enroll Member
    </a>
@endsection

@section('content')

    {{-- Renewal alert mini cards (REQ-MM-04) --}}
    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="info-label mb-0">Renewal Alerts</span>
        <span class="info-label small" style="text-transform:none;letter-spacing:0;color:var(--text-faint);">Click a card to filter</span>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="metric-card js-renewal-filter" data-filter="expired">
                <div class="metric-label"><span class="metric-dot" style="background:var(--danger);"></span>Expired Memberships</div>
                <div class="metric-value" style="color:var(--danger);">{{ $expiredCount }}</div>
                <div class="metric-trend">Click to filter inactive members</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="metric-card js-renewal-filter" data-filter="today">
                <div class="metric-label"><span class="metric-dot" style="background:var(--warn);"></span>Expiring Today</div>
                <div class="metric-value" style="color:var(--warn);">{{ $expiringTodayCount }}</div>
                <div class="metric-trend">Needs immediate follow-up</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="metric-card js-renewal-filter" data-filter="week">
                <div class="metric-label"><span class="metric-dot" style="background:var(--info);"></span>Expiring Within 7 Days</div>
                <div class="metric-value" style="color:var(--info);">{{ $expiringWeekCount }}</div>
                <div class="metric-trend">Plan renewal outreach</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-people"></i> All Members</div>
            <div class="d-flex flex-wrap gap-2">
                <select id="filterStatus" class="form-select form-select-sm" style="width:auto;">
                    <option value="">All Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                    <option value="Churned">Churned</option>
                </select>
                <select id="filterPopulation" class="form-select form-select-sm" style="width:auto;">
                    <option value="">All Population</option>
                    <option value="General">General</option>
                    <option value="Special">Special</option>
                </select>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive members-table-wrap">
                <table id="membersTable" class="table table-borderless align-middle w-100 members-table" style="padding: 0 .5rem;">
                    <thead>
                        <tr>
                            <th class="ps-3 col-member">Member</th>
                            <th class="col-package">Package</th>
                            <th class="col-credits">Credits</th>
                            <th class="col-trainer">Trainer</th>
                            <th class="col-expires">Expires</th>
                            <th class="col-flags">Flags</th>
                            <th class="col-status">Status</th>
                            <th class="text-end pe-3 col-action">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($members as $member)
                        <tr data-status="{{ $member->status }}" data-population="{{ $member->population_class }}">
                            <td class="ps-3 col-member">
                                <div class="d-flex align-items-center gap-2 member-cell">
                                    <div class="avatar avatar-sm flex-shrink-0">
                                        {{ strtoupper(substr($member->full_name, 0, 1)) }}{{ strtoupper(substr(strrchr($member->full_name, ' ') ?: '', 1, 1)) }}
                                    </div>
                                    <div class="member-cell-text">
                                        <div class="fw-semibold text-truncate" style="color:var(--text-strong);">{{ $member->full_name }}</div>
                                        <div class="small text-truncate" style="color:var(--text-faint);">#{{ $member->id }} · {{ $member->fitness_level }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="col-package">{{ $member->activePackage?->package_type ?? '—' }}</td>
                            <td class="col-credits">
                                @if($member->activePackage)
                                    <span style="color:var(--success); font-weight:600;">{{ $member->activePackage->credits_remaining }}</span>
                                @else
                                    <span style="color:var(--text-faint);">—</span>
                                @endif
                            </td>
                            <td class="col-trainer">{{ $member->trainer?->name ?? '—' }}</td>
                            <td class="col-expires" data-order="{{ $member->activePackage?->coverage_end?->format('Y-m-d') ?? '9999-99-99' }}">
                                @if($member->activePackage)
                                    @if($member->isNearExpiry())
                                        <span style="color:var(--warn); font-weight:600;">{{ $member->activePackage->coverage_end->format('M d, Y') }}</span>
                                    @else
                                        {{ $member->activePackage->coverage_end->format('M d, Y') }}
                                    @endif
                                @else
                                    <span style="color:var(--text-faint);">No package</span>
                                @endif
                            </td>
                            <td class="col-flags">
                                @if($member->population_class === 'Special')
                                    <span class="badge badge-special">Special</span>
                                @else
                                    <span style="color:var(--text-faint);">—</span>
                                @endif
                            </td>
                            <td class="col-status">
                                <span class="badge badge-{{ strtolower($member->status) }}">{{ $member->status }}</span>
                            </td>
                            <td class="text-end pe-3 col-action">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-outline-secondary" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger js-delete-btn"
                                            data-url="{{ route('members.destroy', $member) }}"
                                            data-name="{{ $member->full_name }}"
                                            title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<style>
    /* Members table — consistent column widths and graceful collapse */
    .members-table { table-layout: auto; }
    .members-table th, .members-table td { white-space: nowrap; vertical-align: middle; }
    .members-table .col-member { min-width: 220px; }
    .member-cell { min-width: 0; }
    .member-cell-text { min-width: 0; flex: 1 1 auto; overflow: hidden; }
    .member-cell-text .text-truncate { max-width: 240px; }
    .members-table .col-package,
    .members-table .col-trainer { max-width: 180px; overflow: hidden; text-overflow: ellipsis; }
    .members-table .col-credits,
    .members-table .col-flags,
    .members-table .col-status { white-space: nowrap; }
    .members-table .col-action { width: 1%; }

    /* DataTables Responsive child row — match dark theme */
    table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control::before {
        background-color: var(--orange) !important;
        border-color: var(--orange) !important;
        box-shadow: 0 0 0 2px rgba(255,255,255,.04);
        top: 50%;
        transform: translateY(-50%);
        margin-top: 0;
    }
    table.dataTable > tbody > tr.child ul.dtr-details {
        display: block;
        width: 100%;
    }
    table.dataTable > tbody > tr.child ul.dtr-details > li {
        display: grid;
        grid-template-columns: 110px 1fr;
        align-items: center;
        gap: .75rem;
        padding: .55rem 0;
        border-bottom: 1px solid var(--border-subtle, rgba(255,255,255,.06));
    }
    table.dataTable > tbody > tr.child ul.dtr-details > li:last-child { border-bottom: 0; }
    table.dataTable > tbody > tr.child span.dtr-title {
        min-width: 0;
        font-size: .72rem;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: var(--text-faint);
        font-weight: 600;
    }
    table.dataTable > tbody > tr.child span.dtr-data {
        min-width: 0;
        color: var(--text-strong);
    }

    @media (max-width: 575.98px) {
        .members-table .col-member { min-width: 160px; }
        .member-cell-text .text-truncate { max-width: 150px; }
    }
</style>
@endpush

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
    $(function () {
        const table = $('#membersTable').DataTable({
            responsive: true,
            order: [[4, 'asc']],
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            autoWidth: false,
            pagingType: 'full_numbers',
            columnDefs: [
                { targets: 0, responsivePriority: 1 }, // Member
                { targets: 7, responsivePriority: 2, orderable: false, className: 'text-end pe-3' }, // Action
                { targets: 6, responsivePriority: 3 }, // Status
                { targets: 4, responsivePriority: 4 }, // Expires
                { targets: 1, responsivePriority: 5 }, // Package
                { targets: 2, responsivePriority: 6 }, // Credits
                { targets: 3, responsivePriority: 7 }, // Trainer
                { targets: 5, responsivePriority: 8 }, // Flags
            ],
            language: {
                search: '',
                searchPlaceholder: 'Search name, package...',
                emptyTable: 'No members found.',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                infoEmpty: 'Showing 0 to 0 of 0 entries',
                lengthMenu: 'Show _MENU_ entries',
                paginate: { first: '\u00ab', previous: '\u2039', next: '\u203a', last: '\u00bb' },
            },
        });

        // Status / population dropdown filters (custom search)
        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            const row = table.row(dataIndex).node();
            const status = $('#filterStatus').val();
            const population = $('#filterPopulation').val();
            const rowStatus = $(row).data('status');
            const rowPopulation = $(row).data('population');

            if (status && rowStatus !== status) return false;
            if (population && rowPopulation !== population) return false;
            return true;
        });

        $('#filterStatus, #filterPopulation').on('change', function () {
            table.draw();
        });

        let activeRenewalFilter = null;

        $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
            if (!activeRenewalFilter) return true;

            const row = table.row(dataIndex).node();
            const rowStatus = $(row).data('status');
            const expiryRaw = $(row).find('td').eq(4).data('order');

            if (activeRenewalFilter === 'expired') return rowStatus === 'Inactive';

            if (expiryRaw === '9999-99-99') return false;
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const expiry = new Date(expiryRaw);
            const diffDays = Math.round((expiry - today) / 86400000);

            if (activeRenewalFilter === 'today') return diffDays === 0;
            if (activeRenewalFilter === 'week') return diffDays >= 0 && diffDays <= 7;
            return true;
        });

        $('.js-renewal-filter').on('click', function () {
            const filter = $(this).data('filter');
            const wasActive = $(this).hasClass('is-active');
            $('.js-renewal-filter').removeClass('is-active');

            if (wasActive) {
                activeRenewalFilter = null;
            } else {
                $(this).addClass('is-active');
                activeRenewalFilter = filter;
            }
            table.draw();
        });
    });
</script>
@endpush
