@extends('layouts.staff')

@section('title', 'Session Credits')
@section('page-title', 'Session Credits')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/nexfit.css') }}">
@endpush

@section('content')
<div class="pt-3">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="nf-page-title mb-0">Session Credit Inventory</h1>
            <p class="nf-page-sub mb-0">Track credits purchased, conducted, remaining, and forfeited per member</p>
        </div>
        <a href="{{ route('session-management.index') }}" class="btn nf-btn-ghost">
            <i class="bi bi-arrow-left me-1"></i> Back to Bookings
        </a>
    </div>

    {{-- Search --}}
    <div class="nf-card mb-3">
        <form method="GET" action="{{ route('session-credit-inventory.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <div class="nf-search-wrap">
                    <i class="bi bi-search nf-search-icon"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control nf-input nf-search-input"
                           placeholder="Search member name...">
                </div>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn nf-btn-secondary">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                @if(request('search'))
                    <a href="{{ route('session-credit-inventory.index') }}" class="btn nf-btn-ghost">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Booking calendar — redeem a member's credit for a session directly --}}
    <div class="mb-3">
        <h2 class="nf-section-title mb-2">
            <i class="bi bi-calendar-week me-2 text-accent"></i>Redeem a Session
        </h2>
        @include('partials.booking-calendar', ['calRouteName' => 'session-credit-inventory.index'])
    </div>

    <div class="nf-card p-0">
        <div class="table-responsive">
            <table class="table nf-table mb-0">
                <thead>
                    <tr>
                        <th>MEMBER</th>
                        <th class="text-center">PURCHASED</th>
                        <th class="text-center">CONDUCTED</th>
                        <th class="text-center">REMAINING</th>
                        <th class="text-center">FORFEITED</th>
                        <th>CREDIT BAR</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($balances as $balance)
                    <tr class="{{ $balance->credits_remaining === 0 ? 'nf-row-alert' : '' }}">
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="nf-member-avatar">
                                    {{ \App\Support\Initials::of($balance->member->full_name ?? 'Member') }}
                                </div>
                                <div>
                                    <div class="nf-cell-name">{{ $balance->member->full_name ?? '—' }}</div>
                                    <div class="nf-cell-sub">MEM-{{ str_pad($balance->member_id, 3, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="nf-credit-num">{{ $balance->credits_purchased }}</span>
                        </td>
                        <td class="text-center">
                            <span class="nf-credit-num text-accent">{{ $balance->credits_conducted }}</span>
                        </td>
                        <td class="text-center">
                            @if($balance->credits_remaining === 0)
                                <span class="nf-badge nf-badge-cancelled">0</span>
                            @else
                                <span class="nf-credit-num text-success">{{ $balance->credits_remaining }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="nf-credit-num text-muted">{{ $balance->credits_forfeited }}</span>
                        </td>
                        <td style="min-width: 140px;">
                            @php
                                $pct = $balance->credits_purchased > 0
                                    ? round(($balance->credits_remaining / $balance->credits_purchased) * 100)
                                    : 0;
                                $barClass = $pct === 0 ? 'nf-bar-empty'
                                    : ($pct <= 25 ? 'nf-bar-low'
                                    : ($pct <= 60 ? 'nf-bar-mid' : 'nf-bar-high'));
                            @endphp
                            <div class="nf-credit-bar">
                                <div class="nf-credit-bar-fill {{ $barClass }}" style="width: {{ $pct }}%"></div>
                            </div>
                            <small class="text-muted">{{ $pct }}% remaining</small>
                        </td>
                        <td>
                            @if(auth('staff')->user()?->isAdmin())
                            <button type="button" class="btn nf-btn-action nf-btn-action-outline"
                                    data-bs-toggle="modal"
                                    data-bs-target="#adjustModal{{ $balance->member_id }}">
                                <i class="bi bi-pencil"></i> Adjust
                            </button>
                            @endif
                            <a href="{{ route('package-sales.create') }}?member_id={{ $balance->member_id }}"
                               class="btn nf-btn-action nf-btn-action-green ms-1">
                                <i class="bi bi-bag-plus"></i> Buy
                            </a>
                        </td>
                    </tr>

                    {{-- Adjust Credit Modal (admin only) --}}
                    @if(auth('staff')->user()?->isAdmin())
                    <div class="modal fade" id="adjustModal{{ $balance->member_id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content nf-modal">
                                <div class="modal-header nf-modal-header">
                                    <h5 class="modal-title">Adjust Credits — {{ $balance->member->full_name ?? 'Member' }}</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('session-credit-inventory.adjustCredit', $balance->member_id) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="nf-current-balance mb-3">
                                            Current balance: <strong class="text-accent">{{ $balance->credits_remaining }} credits</strong>
                                        </div>
                                        <div class="mb-3">
                                            <label class="nf-label">Adjustment Amount <span class="text-accent">*</span></label>
                                            <input type="number" name="adjustment_amount"
                                                   class="form-control nf-input"
                                                   placeholder="Use negative numbers to deduct (e.g. -2)"
                                                   required>
                                            <small class="text-muted">Positive = add credits &nbsp;|&nbsp; Negative = deduct credits</small>
                                        </div>
                                        <div class="mb-3">
                                            <label class="nf-label">Reason <span class="text-accent">*</span></label>
                                            <input type="text" name="reason"
                                                   class="form-control nf-input"
                                                   placeholder="Required — e.g. Manual correction, Expired credits"
                                                   required minlength="5">
                                        </div>
                                    </div>
                                    <div class="modal-footer nf-modal-footer">
                                        <button type="button" class="btn nf-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn nf-btn-primary">Save Adjustment</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif

                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-credit-card nf-empty-icon"></i>
                            <p class="nf-empty-text mt-2">No credit records found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($balances->hasPages())
        <div class="nf-pagination d-flex align-items-center justify-content-between px-4 py-3">
            <span class="nf-pagination-info">
                Showing {{ $balances->firstItem() }}–{{ $balances->lastItem() }} of {{ $balances->total() }} members
            </span>
            <div class="d-flex gap-1">
                @if($balances->onFirstPage())
                    <span class="nf-page-btn disabled"><i class="bi bi-chevron-left"></i></span>
                @else
                    <a href="{{ $balances->previousPageUrl() }}" class="nf-page-btn"><i class="bi bi-chevron-left"></i></a>
                @endif
                @foreach($balances->getUrlRange(1, $balances->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="nf-page-btn {{ $balances->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
                @endforeach
                @if($balances->hasMorePages())
                    <a href="{{ $balances->nextPageUrl() }}" class="nf-page-btn"><i class="bi bi-chevron-right"></i></a>
                @else
                    <span class="nf-page-btn disabled"><i class="bi bi-chevron-right"></i></span>
                @endif
            </div>
        </div>
        @endif
    </div>

</div>
@endsection
