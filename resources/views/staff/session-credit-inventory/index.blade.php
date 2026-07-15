@extends('layouts.staff')

@section('title', 'Session Credits')
@section('page-title', 'Session Credits')
@section('page-subtitle')
    <span>{{ $members->count() }} members</span>
    <span style="opacity:.45">&middot;</span>
    <span style="color:var(--success)">{{ $totals['remaining'] }} credits remaining</span>
    <span style="opacity:.45">&middot;</span>
    <span>{{ $totals['purchased'] }} purchased total</span>
@endsection

@push('styles')
<style>
    .sci-search-bar {
        display: flex;
        align-items: center;
        gap: .6rem;
        padding: .9rem 1.1rem;
        margin-bottom: 1.25rem;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow-1);
    }
    .sci-search-bar .form-control { flex: 1; }

    .sci-table th, .sci-table td { vertical-align: middle; white-space: nowrap; }
    .sci-table .col-member { min-width: 220px; white-space: normal; }
    .sci-table .col-bar { min-width: 160px; }
    .sci-table .col-action { width: 1%; }

    .sci-member-cell { display: flex; align-items: center; gap: .6rem; min-width: 0; }
    .sci-member-text { min-width: 0; overflow: hidden; }
    .sci-member-text .name { font-weight: 600; color: var(--text-strong); overflow: hidden; text-overflow: ellipsis; }
    .sci-member-text .sub { font-size: .74rem; color: var(--text-faint); }

    .sci-remaining { font-weight: 700; }
    .sci-remaining.is-zero { color: var(--danger); }
    .sci-remaining.is-low  { color: var(--warn); }
    .sci-remaining.is-ok   { color: var(--success); }

    .credit-bar-wrap { display: flex; align-items: center; gap: .5rem; }
    .credit-bar-track {
        flex: 1;
        height: 8px;
        border-radius: 999px;
        background: var(--surface-3);
        overflow: hidden;
    }
    .credit-bar-fill {
        height: 100%;
        border-radius: 999px;
        background: var(--success);
    }
    .credit-bar-fill.is-low  { background: var(--warn); }
    .credit-bar-fill.is-zero { background: var(--danger); }
    .credit-bar-pct {
        font-size: .7rem;
        font-weight: 700;
        color: var(--text-faint);
        min-width: 32px;
        text-align: right;
    }

    .sci-empty { padding: 2.5rem 1rem; text-align: center; color: var(--text-faint); }
    .sci-empty i { font-size: 1.8rem; display: block; margin-bottom: .5rem; }
</style>
@endpush

@section('content')

<form method="GET" action="{{ route('session-credit-inventory.index') }}" class="sci-search-bar">
    <i class="bi bi-search" style="color:var(--text-faint);"></i>
    <input
        type="text"
        name="search"
        class="form-control"
        placeholder="Search member name..."
        value="{{ $search }}"
    >
    <button type="submit" class="btn btn-outline-secondary">
        <i class="bi bi-funnel"></i> Filter
    </button>
    @if($search !== '')
        <a href="{{ route('session-credit-inventory.index') }}" class="btn btn-outline-secondary" title="Clear search">
            <i class="bi bi-x-lg"></i>
        </a>
    @endif
</form>

<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="bi bi-box-seam"></i> Session Credit Inventory</div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-borderless align-middle w-100 sci-table" style="padding: 0 .5rem;">
                <thead>
                    <tr>
                        <th class="ps-3 col-member">Member</th>
                        <th>Purchased</th>
                        <th>Conducted</th>
                        <th>Remaining</th>
                        <th>Forfeited</th>
                        <th class="col-bar">Credit Bar</th>
                        <th class="text-end pe-3 col-action">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($members as $member)
                        @php
                            $balance   = $member->creditBalance;
                            $purchased = $balance?->credits_purchased ?? 0;
                            $conducted = $balance?->credits_conducted ?? 0;
                            $remaining = $balance?->credits_remaining ?? 0;
                            $forfeited = $balance?->credits_forfeited ?? 0;
                            $pct = $purchased > 0 ? min(100, round(($remaining / $purchased) * 100)) : 0;
                            $level = $remaining <= 0 ? 'zero' : ($pct <= 25 ? 'low' : 'ok');
                        @endphp
                        <tr>
                            <td class="ps-3 col-member">
                                <div class="sci-member-cell">
                                    <div class="avatar avatar-sm flex-shrink-0">
                                        {{ strtoupper(substr($member->full_name, 0, 1)) }}{{ strtoupper(substr(strrchr($member->full_name, ' ') ?: '', 1, 1)) }}
                                    </div>
                                    <div class="sci-member-text">
                                        <div class="name text-truncate">{{ $member->full_name }}</div>
                                        <div class="sub">#{{ $member->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $purchased }}</td>
                            <td>{{ $conducted }}</td>
                            <td>
                                <span class="sci-remaining is-{{ $level }}">{{ $remaining }}</span>
                            </td>
                            <td>
                                @if($forfeited > 0)
                                    <span style="color:var(--danger);">{{ $forfeited }}</span>
                                @else
                                    <span style="color:var(--text-faint);">0</span>
                                @endif
                            </td>
                            <td class="col-bar">
                                <div class="credit-bar-wrap">
                                    <div class="credit-bar-track">
                                        <div class="credit-bar-fill is-{{ $level }}" style="width: {{ $pct }}%;"></div>
                                    </div>
                                    <span class="credit-bar-pct">{{ $pct }}%</span>
                                </div>
                            </td>
                            <td class="text-end pe-3 col-action">
                                <a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-outline-secondary" title="View member">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="sci-empty">
                                    <i class="bi bi-box-seam"></i>
                                    No members match your search.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
