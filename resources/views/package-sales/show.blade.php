@extends('layouts.staff')

@section('title', 'Sale Receipt')
@section('page-title', 'Sale Receipt')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/nexfit.css') }}">
@endpush

@section('content')
<div class="pt-3">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="nf-page-title mb-0">Sale Receipt</h1>
            <p class="nf-page-sub mb-0">Transaction #{{ str_pad($packageSale->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn nf-btn-secondary">
                <i class="bi bi-printer me-1"></i> Print Receipt
            </button>
            <a href="{{ route('package-sales.index') }}" class="btn nf-btn-ghost">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="nf-card nf-receipt">

                {{-- Receipt header --}}
                <div class="nf-receipt-header text-center">
                    <div class="nf-receipt-logo">
                        <i class="bi bi-lightning-charge-fill text-accent"></i>
                        <span class="nf-logo-brand">NEXFIT</span>
                    </div>
                    <div class="nf-receipt-studio">Fit Urban Fitness Studio</div>
                    <div class="nf-receipt-sub">Session Package Receipt</div>
                </div>

                <hr class="nf-receipt-divider">

                {{-- Transaction info --}}
                <div class="nf-receipt-row">
                    <span>Transaction No.</span>
                    <strong>TXN-{{ str_pad($packageSale->id, 6, '0', STR_PAD_LEFT) }}</strong>
                </div>
                <div class="nf-receipt-row">
                    <span>Date</span>
                    <strong>{{ $packageSale->sale_date->format('F d, Y') }}</strong>
                </div>
                <div class="nf-receipt-row">
                    <span>Time</span>
                    <strong>{{ date('h:i A', strtotime($packageSale->sale_time)) }}</strong>
                </div>

                <hr class="nf-receipt-divider">

                {{-- Client info --}}
                <div class="nf-receipt-row">
                    <span>Client</span>
                    <strong>{{ $packageSale->client_name }}</strong>
                </div>
                <div class="nf-receipt-row">
                    <span>Sale Type</span>
                    <strong>{{ ucwords(str_replace('_', ' ', $packageSale->sale_type)) }}</strong>
                </div>

                <hr class="nf-receipt-divider">

                {{-- Package info --}}
                <div class="nf-receipt-row">
                    <span>Package</span>
                    <strong>{{ $packageSale->sessionPackage->name ?? '—' }}</strong>
                </div>
                <div class="nf-receipt-row">
                    <span>Program</span>
                    <strong>{{ ucwords(str_replace('_', ' ', $packageSale->sessionPackage->program ?? '')) }}</strong>
                </div>
                <div class="nf-receipt-row">
                    <span>Sessions Included</span>
                    <strong>{{ $packageSale->sessionPackage->session_credits ?? '—' }}</strong>
                </div>
                <div class="nf-receipt-row">
                    <span>Validity</span>
                    <strong>{{ $packageSale->sessionPackage->validity_days ?? '—' }} days</strong>
                </div>

                <hr class="nf-receipt-divider">

                {{-- Payment --}}
                <div class="nf-receipt-row">
                    <span>Pricing Type</span>
                    <strong>{{ ucfirst($packageSale->pricing_type) }}</strong>
                </div>
                <div class="nf-receipt-row">
                    <span>Payment Mode</span>
                    <strong>{{ ucwords(str_replace('_', ' ', $packageSale->payment_mode)) }}</strong>
                </div>
                @if($packageSale->reference_number)
                <div class="nf-receipt-row">
                    <span>Reference No.</span>
                    <strong>{{ $packageSale->reference_number }}</strong>
                </div>
                @endif

                <hr class="nf-receipt-divider">

                <div class="nf-receipt-total">
                    <span>TOTAL AMOUNT PAID</span>
                    <span class="nf-receipt-amount">&#8369;{{ number_format($packageSale->amount_paid, 2) }}</span>
                </div>

                <hr class="nf-receipt-divider">

                <div class="nf-receipt-row">
                    <span>Processed by</span>
                    <strong>{{ $packageSale->processedBy->full_name ?? '—' }}</strong>
                </div>

                @if($packageSale->notes)
                <div class="nf-receipt-note mt-3">
                    <i class="bi bi-info-circle me-1"></i> {{ $packageSale->notes }}
                </div>
                @endif

                <div class="nf-receipt-footer text-center mt-4">
                    <p>Thank you for choosing Fit Urban!</p>
                    <small>This is an official digital receipt. For concerns, please contact studio management.</small>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
@media print {
    .nf-sidebar, .nf-topbar, .btn, .nf-breadcrumb { display: none !important; }
    .nf-main { margin: 0 !important; }
    .nf-receipt { box-shadow: none; border: 1px solid #ccc; }
}
</style>
@endpush
