@extends('layouts.staff')

@section('title', 'Session Detail')
@section('page-title', 'Session Detail')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/nexfit.css') }}">
@endpush

@section('content')
<div class="pt-3">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="nf-page-title mb-0">Session Detail</h1>
            <p class="nf-page-sub mb-0">
                Session #{{ $session->id }}
                &nbsp;&mdash;&nbsp;
                <span class="nf-badge nf-badge-{{ $session->status }}">
                    <i class="bi bi-circle-fill nf-badge-dot"></i>
                    {{ ucfirst($session->status) }}
                </span>
            </p>
        </div>
        <a href="{{ route('session-management.index') }}" class="btn nf-btn-ghost">
            <i class="bi bi-arrow-left me-1"></i> Back to Bookings
        </a>
    </div>

    <div class="row g-4">

        {{-- Left: session info --}}
        <div class="col-lg-8">
            <div class="nf-card">
                <h2 class="nf-section-title mb-4">Session Information</h2>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="nf-detail-group">
                            <span class="nf-detail-label">Member</span>
                            <span class="nf-detail-value">{{ $session->member->full_name ?? '—' }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="nf-detail-group">
                            <span class="nf-detail-label">Member ID</span>
                            <span class="nf-detail-value">MEM-{{ str_pad($session->member_id, 3, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="nf-detail-group">
                            <span class="nf-detail-label">Program</span>
                            <span class="nf-detail-value">
                                @php
                                    $programMap = [
                                        'pilates'           => 'Pilates',
                                        'personal_training' => 'Personal Training',
                                        'general_gym'       => 'General Gym',
                                    ];
                                @endphp
                                {{ $programMap[$session->program] ?? $session->program }}
                                @if($session->level)
                                    &middot; {{ ucfirst(str_replace('_', ' ', $session->level)) }}
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="nf-detail-group">
                            <span class="nf-detail-label">Trainer</span>
                            <span class="nf-detail-value">{{ $session->trainer->name ?? '—' }}</span>
                        </div>
                    </div>
                    @if($session->backupTrainer)
                    <div class="col-sm-6">
                        <div class="nf-detail-group">
                            <span class="nf-detail-label">Backup Trainer</span>
                            <span class="nf-detail-value">{{ $session->backupTrainer->name }}</span>
                        </div>
                    </div>
                    @endif
                    <div class="col-sm-6">
                        <div class="nf-detail-group">
                            <span class="nf-detail-label">Date</span>
                            <span class="nf-detail-value">{{ $session->session_date->format('F d, Y') }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="nf-detail-group">
                            <span class="nf-detail-label">Time</span>
                            <span class="nf-detail-value">{{ date('h:i A', strtotime($session->session_time)) }}</span>
                        </div>
                    </div>
                    @if($session->remarks)
                    <div class="col-12">
                        <div class="nf-detail-group">
                            <span class="nf-detail-label">Remarks</span>
                            <span class="nf-detail-value">{{ $session->remarks }}</span>
                        </div>
                    </div>
                    @endif
                </div>

                @if($session->status === 'conducted')
                <hr class="nf-divider mt-4">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="nf-detail-group">
                            <span class="nf-detail-label">Conducted At</span>
                            <span class="nf-detail-value">{{ $session->conducted_at?->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="nf-detail-group">
                            <span class="nf-detail-label">Marked By</span>
                            <span class="nf-detail-value">{{ $session->conductedBy->full_name ?? '—' }}</span>
                        </div>
                    </div>
                </div>
                @endif

                @if($session->status === 'cancelled')
                <hr class="nf-divider mt-4">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="nf-detail-group">
                            <span class="nf-detail-label">Cancelled At</span>
                            <span class="nf-detail-value">{{ $session->cancelled_at?->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="nf-detail-group">
                            <span class="nf-detail-label">Cancelled By</span>
                            <span class="nf-detail-value">{{ $session->cancelledBy->full_name ?? '—' }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Package info --}}
            @if($session->packageSale)
            <div class="nf-card mt-4">
                <h2 class="nf-section-title mb-3">Linked Package</h2>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="nf-detail-group">
                            <span class="nf-detail-label">Package</span>
                            <span class="nf-detail-value">{{ $session->packageSale->sessionPackage->name ?? '—' }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="nf-detail-group">
                            <span class="nf-detail-label">Sale Date</span>
                            <span class="nf-detail-value">{{ $session->packageSale->sale_date->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Right: actions --}}
        <div class="col-lg-4">
            <div class="nf-card">
                <h3 class="nf-section-title mb-3">Actions</h3>

                @if(in_array($session->status, ['confirmed', 'pending']))
                    <form action="{{ route('session-management.markConducted', $session) }}" method="POST" class="mb-2">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn nf-btn-primary w-100"
                                onclick="return confirm('Mark this session as conducted and deduct one credit?')">
                            <i class="bi bi-check2-circle me-1"></i> Mark as Conducted
                        </button>
                    </form>
                @endif

                @if($session->status === 'cancelled')
                    <form action="{{ route('session-management.restore', $session) }}" method="POST" class="mb-2">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn nf-btn-secondary w-100"
                                onclick="return confirm('Restore this session?')">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Restore Session
                        </button>
                    </form>
                @endif

                @if(!in_array($session->status, ['cancelled', 'conducted']))
                    <button type="button" class="btn nf-btn-danger w-100 mt-2"
                            data-bs-toggle="modal" data-bs-target="#cancelModalDetail">
                        <i class="bi bi-x-circle me-1"></i> Cancel Session
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Cancel Modal --}}
<div class="modal fade" id="cancelModalDetail" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content nf-modal">
            <div class="modal-header nf-modal-header">
                <h5 class="modal-title">Cancel Session</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('session-management.cancel', $session) }}" method="POST">
                @csrf @method('PATCH')
                <div class="modal-body">
                    <p class="text-secondary mb-3">
                        This will cancel the session for
                        <strong>{{ $session->member->full_name ?? '—' }}</strong>.
                    </p>
                    <label class="nf-label">Reason (optional)</label>
                    <input type="text" name="reason" class="form-control nf-input"
                           placeholder="e.g. Member request, Trainer unavailable">
                </div>
                <div class="modal-footer nf-modal-footer">
                    <button type="button" class="btn nf-btn-ghost" data-bs-dismiss="modal">Dismiss</button>
                    <button type="submit" class="btn nf-btn-danger">Confirm Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
