@extends('layouts.staff')

@section('title', 'Package Sales')
@section('page-title', 'Package Sales')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/nexfit.css') }}">
@endpush

@section('content')
<div class="pt-3">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="nf-page-title mb-0">Session Package Sales</h1>
            <p class="nf-page-sub mb-0">Record and track session package purchases</p>
        </div>
        <a href="{{ route('package-sales.create') }}" class="btn nf-btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Record Sale
        </a>
    </div>

    {{-- Summary stats --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="nf-stat-card">
                <div class="nf-stat-label">Today's Sales</div>
                <div class="nf-stat-value">&#8369; {{ number_format($todayTotal, 2) }}</div>
                <div class="nf-stat-icon"><i class="bi bi-sun"></i></div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="nf-stat-card">
                <div class="nf-stat-label">This Week</div>
                <div class="nf-stat-value">&#8369; {{ number_format($weekTotal, 2) }}</div>
                <div class="nf-stat-icon"><i class="bi bi-calendar-week"></i></div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="nf-stat-card">
                <div class="nf-stat-label">This Month</div>
                <div class="nf-stat-value">&#8369; {{ number_format($monthTotal, 2) }}</div>
                <div class="nf-stat-icon"><i class="bi bi-calendar-month"></i></div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="nf-card mb-3">
        <form method="GET" action="{{ route('package-sales.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <div class="nf-search-wrap">
                    <i class="bi bi-search nf-search-icon"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control nf-input nf-search-input"
                           placeholder="Search member or walk-in...">
                </div>
            </div>
            <div class="col-md-2">
                <select name="package_id" class="form-select nf-input">
                    <option value="">All packages</option>
                    @foreach($packages as $pkg)
                        <option value="{{ $pkg->id }}" {{ request('package_id') == $pkg->id ? 'selected' : '' }}>
                            {{ $pkg->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="payment_mode" class="form-select nf-input">
                    <option value="">All payment modes</option>
                    <option value="cash"          {{ request('payment_mode') === 'cash'          ? 'selected' : '' }}>Cash</option>
                    <option value="gcash"         {{ request('payment_mode') === 'gcash'         ? 'selected' : '' }}>GCash</option>
                    <option value="bank_transfer" {{ request('payment_mode') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="other"         {{ request('payment_mode') === 'other'         ? 'selected' : '' }}>Other</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="from" value="{{ request('from') }}" class="form-control nf-input" placeholder="From date">
            </div>
            <div class="col-md-1">
                <input type="date" name="to" value="{{ request('to') }}" class="form-control nf-input" placeholder="To date">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn nf-btn-secondary flex-grow-1">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'package_id', 'payment_mode', 'from', 'to']))
                    <a href="{{ route('package-sales.index') }}" class="btn nf-btn-ghost">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="nf-card p-0">
        <div class="table-responsive">
            <table class="table nf-table mb-0">
                <thead>
                    <tr>
                        <th>CLIENT</th>
                        <th>PACKAGE</th>
                        <th>AMOUNT</th>
                        <th>PAYMENT MODE</th>
                        <th>SALE TYPE</th>
                        <th>DATE / TIME</th>
                        <th>PROCESSED BY</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="nf-member-avatar nf-avatar-sm">
                                    @if($sale->member)
                                        {{ \App\Support\Initials::of($sale->member->full_name ?? 'Walk-in') }}
                                    @else
                                        WI
                                    @endif
                                </div>
                                <div>
                                    <div class="nf-cell-name">{{ $sale->client_name }}</div>
                                    @if(!$sale->member)
                                        <div class="nf-cell-sub">Walk-in</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="nf-cell-name">{{ $sale->sessionPackage->name ?? '—' }}</div>
                            <div class="nf-cell-sub">{{ $sale->sessionPackage->session_credits ?? 0 }} sessions</div>
                        </td>
                        <td>
                            <span class="nf-amount">&#8369;{{ number_format($sale->amount_paid, 2) }}</span>
                            @if($sale->pricing_type !== 'standard')
                                <span class="nf-badge nf-badge-info ms-1">{{ ucfirst($sale->pricing_type) }}</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $modeIcon = [
                                    'cash'          => 'bi-cash',
                                    'gcash'         => 'bi-phone',
                                    'bank_transfer' => 'bi-bank',
                                    'other'         => 'bi-three-dots',
                                ];
                            @endphp
                            <i class="bi {{ $modeIcon[$sale->payment_mode] ?? 'bi-cash' }} me-1 text-accent"></i>
                            {{ ucwords(str_replace('_', ' ', $sale->payment_mode)) }}
                        </td>
                        <td>
                            <span class="nf-badge nf-badge-info">
                                {{ ucwords(str_replace('_', ' ', $sale->sale_type)) }}
                            </span>
                        </td>
                        <td class="nf-cell-meta">
                            {{ $sale->sale_date->format('M d, Y') }}<br>
                            <small>{{ date('h:i A', strtotime($sale->sale_time)) }}</small>
                        </td>
                        <td class="nf-cell-meta">{{ $sale->processedBy->full_name ?? '—' }}</td>
                        <td>
                            <a href="{{ route('package-sales.show', $sale) }}" class="btn nf-btn-action nf-btn-action-outline">
                                <i class="bi bi-receipt"></i> Receipt
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-bag-x nf-empty-icon"></i>
                            <p class="nf-empty-text mt-2">No sales records found.</p>
                            <a href="{{ route('package-sales.create') }}" class="btn nf-btn-primary mt-1">
                                <i class="bi bi-plus-lg me-1"></i> Record Sale
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sales->hasPages())
        <div class="nf-pagination d-flex align-items-center justify-content-between px-4 py-3">
            <span class="nf-pagination-info">
                Showing {{ $sales->firstItem() }}–{{ $sales->lastItem() }} of {{ $sales->total() }} records
            </span>
            <div class="d-flex gap-1">
                @if($sales->onFirstPage())
                    <span class="nf-page-btn disabled"><i class="bi bi-chevron-left"></i></span>
                @else
                    <a href="{{ $sales->previousPageUrl() }}" class="nf-page-btn"><i class="bi bi-chevron-left"></i></a>
                @endif
                @foreach($sales->getUrlRange(1, $sales->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="nf-page-btn {{ $sales->currentPage() === $page ? 'active' : '' }}">{{ $page }}</a>
                @endforeach
                @if($sales->hasMorePages())
                    <a href="{{ $sales->nextPageUrl() }}" class="nf-page-btn"><i class="bi bi-chevron-right"></i></a>
                @else
                    <span class="nf-page-btn disabled"><i class="bi bi-chevron-right"></i></span>
                @endif
            </div>
        </div>
        @endif
    </div>

</div>
@endsection
