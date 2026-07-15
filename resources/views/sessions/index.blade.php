@extends('layouts.staff')

@section('title', 'Manage Bookings')
@section('page-title', 'Manage Bookings')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/nexfit.css') }}">
@endpush

@section('content')
<div class="pt-3">

    {{-- Page header row --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="nf-page-title mb-0">Manage Bookings</h1>
            <p class="nf-page-sub mb-0">
                {{ $totalCount }} total bookings &mdash;
                <span class="text-accent">
                    <i class="bi bi-dot"></i> Real-time
                </span>
                &nbsp;|&nbsp;
                <span class="nf-live-badge"><i class="bi bi-circle-fill nf-pulse"></i> Live updates</span>
            </p>
        </div>
        <a href="{{ route('session-management.create') }}" class="btn nf-btn-primary">
            <i class="bi bi-plus-lg me-1"></i> New Booking
        </a>
    </div>

    {{-- Filter bar --}}
    <div class="nf-card mb-3">
        <form method="GET" action="{{ route('session-management.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <div class="nf-search-wrap">
                    <i class="bi bi-search nf-search-icon"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="form-control nf-input nf-search-input"
                           placeholder="Search member or trainer...">
                </div>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select nf-input">
                    <option value="">All statuses</option>
                    <option value="confirmed"   {{ request('status') === 'confirmed'   ? 'selected' : '' }}>Confirmed</option>
                    <option value="conducted"   {{ request('status') === 'conducted'   ? 'selected' : '' }}>Conducted</option>
                    <option value="pending"     {{ request('status') === 'pending'     ? 'selected' : '' }}>Pending</option>
                    <option value="cancelled"   {{ request('status') === 'cancelled'   ? 'selected' : '' }}>Cancelled</option>
                    <option value="no_show"     {{ request('status') === 'no_show'     ? 'selected' : '' }}>No Show</option>
                    <option value="rescheduled" {{ request('status') === 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="trainer_id" class="form-select nf-input">
                    <option value="">All trainers</option>
                    @foreach($trainers as $trainer)
                        <option value="{{ $trainer->id }}" {{ request('trainer_id') == $trainer->id ? 'selected' : '' }}>
                            {{ $trainer->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date" value="{{ request('date') }}"
                       class="form-control nf-input">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn nf-btn-secondary flex-grow-1">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'trainer_id', 'date']))
                    <a href="{{ route('session-management.index') }}" class="btn nf-btn-ghost">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Sessions table --}}
    <div class="nf-card p-0">
        <div class="table-responsive">
            <table class="table nf-table mb-0">
                <thead>
                    <tr>
                        <th>MEMBER NAME</th>
                        <th>TRAINER</th>
                        <th>DATE</th>
                        <th>TIME</th>
                        <th>PROGRAM</th>
                        <th>STATUS</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $session)
                    <tr>
                        {{-- Member --}}
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="nf-member-avatar">
                                    {{ \App\Support\Initials::of($session->member->full_name ?? 'Member') }}
                                </div>
                                <div>
                                    <div class="nf-cell-name">
                                        {{ $session->member->full_name ?? '—' }}
                                    </div>
                                    <div class="nf-cell-sub">MEM-{{ str_pad($session->member_id, 3, '0', STR_PAD_LEFT) }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Trainer --}}
                        <td class="nf-cell-name">
                            {{ $session->trainer->name ?? '—' }}
                        </td>

                        {{-- Date --}}
                        <td class="nf-cell-meta">
                            {{ $session->session_date->format('M d, Y') }}
                        </td>

                        {{-- Time --}}
                        <td class="nf-cell-meta">
                            {{ date('h:i A', strtotime($session->session_time)) }}
                        </td>

                        {{-- Program --}}
                        <td class="nf-cell-meta">
                            @php
                                $programMap = [
                                    'pilates'           => 'Pilates',
                                    'personal_training' => 'Personal Training',
                                    'general_gym'       => 'General Gym',
                                ];
                                $levelMap = [
                                    'fundamentals' => 'Fundamentals',
                                    'beginner'     => 'Beginner',
                                    'mid_level'    => 'Mid-level',
                                    'advanced'     => 'Advanced',
                                ];
                            @endphp
                            {{ $programMap[$session->program] ?? $session->program }}
                            @if($session->level)
                                &middot; {{ $levelMap[$session->level] ?? $session->level }}
                            @endif
                        </td>

                        {{-- Status badge --}}
                        <td>
                            <span class="nf-badge nf-badge-{{ $session->status }}">
                                <i class="bi bi-circle-fill nf-badge-dot"></i>
                                {{ ucfirst($session->status) }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td>
                            <div class="d-flex gap-1 flex-wrap">

                                @if(in_array($session->status, ['confirmed', 'pending']))
                                    <form action="{{ route('session-management.markConducted', $session) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn nf-btn-action nf-btn-action-green"
                                                onclick="return confirm('Mark this session as conducted?')">
                                            Mark Conducted
                                        </button>
                                    </form>
                                @endif

                                @if($session->status !== 'cancelled')
                                    <a href="{{ route('session-management.show', $session) }}" class="btn nf-btn-action nf-btn-action-outline">
                                        View
                                    </a>
                                @endif

                                @if($session->status === 'cancelled')
                                    <form action="{{ route('session-management.restore', $session) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn nf-btn-action nf-btn-action-outline"
                                                onclick="return confirm('Restore this session?')">
                                            Restore
                                        </button>
                                    </form>
                                @endif

                                @if(!in_array($session->status, ['cancelled', 'conducted']))
                                    <button type="button" class="btn nf-btn-action nf-btn-action-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#cancelModal{{ $session->id }}">
                                        Delete
                                    </button>
                                @endif

                            </div>
                        </td>
                    </tr>

                    {{-- Cancel Modal --}}
                    <div class="modal fade" id="cancelModal{{ $session->id }}" tabindex="-1">
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
                                            Cancel session for <strong>{{ $session->member->full_name ?? '—' }}</strong>
                                            on {{ $session->session_date->format('M d, Y') }}?
                                        </p>
                                        <label class="form-label nf-label">Reason (optional)</label>
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

                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-calendar-x nf-empty-icon"></i>
                            <p class="nf-empty-text mt-2">No sessions found.</p>
                            <a href="{{ route('session-management.create') }}" class="btn nf-btn-primary mt-1">
                                <i class="bi bi-plus-lg me-1"></i> New Booking
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($sessions->hasPages())
        <div class="nf-pagination d-flex align-items-center justify-content-between px-4 py-3">
            <span class="nf-pagination-info">
                Showing {{ $sessions->firstItem() }}&ndash;{{ $sessions->lastItem() }} of {{ $sessions->total() }} bookings
            </span>
            <div class="d-flex gap-1">
                @if($sessions->onFirstPage())
                    <span class="nf-page-btn disabled"><i class="bi bi-chevron-left"></i></span>
                @else
                    <a href="{{ $sessions->previousPageUrl() }}" class="nf-page-btn"><i class="bi bi-chevron-left"></i></a>
                @endif

                @foreach($sessions->getUrlRange(1, $sessions->lastPage()) as $page => $url)
                    <a href="{{ $url }}"
                       class="nf-page-btn {{ $sessions->currentPage() === $page ? 'active' : '' }}">
                        {{ $page }}
                    </a>
                @endforeach

                @if($sessions->hasMorePages())
                    <a href="{{ $sessions->nextPageUrl() }}" class="nf-page-btn"><i class="bi bi-chevron-right"></i></a>
                @else
                    <span class="nf-page-btn disabled"><i class="bi bi-chevron-right"></i></span>
                @endif
            </div>
        </div>
        @endif
    </div>

</div>
@endsection
