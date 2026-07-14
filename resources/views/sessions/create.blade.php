@extends('layouts.staff')

@section('title', 'New Booking')
@section('page-title', 'New Booking')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/nexfit.css') }}">
@endpush

@section('content')
<div class="pt-3">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="nf-page-title mb-0">New Booking</h1>
            <p class="nf-page-sub mb-0">Create a PT or Pilates session for a member</p>
        </div>
        <a href="{{ route('session-management.index') }}" class="btn nf-btn-ghost">
            <i class="bi bi-arrow-left me-1"></i> Back to Bookings
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="nf-card">
                <h2 class="nf-section-title mb-4">Session Details</h2>

                <form action="{{ route('session-management.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">

                        {{-- Member --}}
                        <div class="col-12">
                            <label class="nf-label">Member <span class="text-accent">*</span></label>
                            <select name="member_id" class="form-select nf-input @error('member_id') is-invalid @enderror" required>
                                <option value="">— Select member —</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->full_name }} (MEM-{{ str_pad($member->id, 3, '0', STR_PAD_LEFT) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('member_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Program --}}
                        <div class="col-md-6">
                            <label class="nf-label">Program <span class="text-accent">*</span></label>
                            <select name="program" class="form-select nf-input @error('program') is-invalid @enderror" required>
                                <option value="">— Select program —</option>
                                <option value="Pilates"           {{ old('program') === 'Pilates'           ? 'selected' : '' }}>Pilates</option>
                                <option value="Personal Training" {{ old('program') === 'Personal Training' ? 'selected' : '' }}>Personal Training</option>
                                <option value="Open Gym Access"   {{ old('program') === 'Open Gym Access'   ? 'selected' : '' }}>Open Gym Access</option>
                            </select>
                            @error('program')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Level --}}
                        <div class="col-md-6">
                            <label class="nf-label">Level</label>
                            <select name="level" class="form-select nf-input">
                                <option value="">— Not applicable —</option>
                                <option value="Fundamentals" {{ old('level') === 'Fundamentals' ? 'selected' : '' }}>Fundamentals</option>
                                <option value="Mid-Level"    {{ old('level') === 'Mid-Level'    ? 'selected' : '' }}>Mid-Level</option>
                                <option value="Advanced"     {{ old('level') === 'Advanced'     ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>

                        {{-- Trainer --}}
                        <div class="col-md-6">
                            <label class="nf-label">Primary Trainer <span class="text-accent">*</span></label>
                            <select name="trainer_id" class="form-select nf-input @error('trainer_id') is-invalid @enderror" required>
                                <option value="">— Select trainer —</option>
                                @foreach($trainers as $trainer)
                                    <option value="{{ $trainer->id }}" {{ old('trainer_id') == $trainer->id ? 'selected' : '' }}>
                                        {{ $trainer->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('trainer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Backup Trainer --}}
                        <div class="col-md-6">
                            <label class="nf-label">Backup Trainer</label>
                            <select name="backup_trainer_id" class="form-select nf-input">
                                <option value="">— None —</option>
                                @foreach($trainers as $trainer)
                                    <option value="{{ $trainer->id }}" {{ old('backup_trainer_id') == $trainer->id ? 'selected' : '' }}>
                                        {{ $trainer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Session Date --}}
                        <div class="col-md-6">
                            <label class="nf-label">Session Date <span class="text-accent">*</span></label>
                            <input type="date" name="session_date" value="{{ old('session_date', date('Y-m-d')) }}"
                                   class="form-control nf-input @error('session_date') is-invalid @enderror"
                                   min="{{ date('Y-m-d') }}" required>
                            @error('session_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Session Time --}}
                        <div class="col-md-6">
                            <label class="nf-label">Session Time <span class="text-accent">*</span></label>
                            <select name="session_time" class="form-select nf-input @error('session_time') is-invalid @enderror" required>
                                <option value="">— Select slot —</option>
                                <option value="09:00:00" {{ old('session_time') === '09:00:00' ? 'selected' : '' }}>9:00 AM – 10:00 AM</option>
                                <option value="10:15:00" {{ old('session_time') === '10:15:00' ? 'selected' : '' }}>10:15 AM – 11:15 AM</option>
                                <option value="17:00:00" {{ old('session_time') === '17:00:00' ? 'selected' : '' }}>5:00 PM – 6:00 PM</option>
                            </select>
                            @error('session_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Remarks --}}
                        <div class="col-12">
                            <label class="nf-label">Remarks</label>
                            <textarea name="remarks" rows="3"
                                      class="form-control nf-input"
                                      placeholder="e.g. Special accommodation, injury note...">{{ old('remarks') }}</textarea>
                        </div>

                        {{-- Submit --}}
                        <div class="col-12 d-flex gap-2 justify-content-end pt-2">
                            <a href="{{ route('session-management.index') }}" class="btn nf-btn-ghost">Cancel</a>
                            <button type="submit" class="btn nf-btn-primary">
                                <i class="bi bi-calendar-check me-1"></i> Confirm Booking
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- Sidebar info --}}
        <div class="col-lg-4">
            <div class="nf-card nf-info-card">
                <h3 class="nf-section-title mb-3">
                    <i class="bi bi-info-circle me-2 text-accent"></i>Default Slots
                </h3>
                <ul class="nf-info-list">
                    <li>
                        <span class="nf-info-time">9:00 – 10:00 AM</span>
                        <span class="nf-info-desc">General / Advanced</span>
                    </li>
                    <li>
                        <span class="nf-info-time">10:15 – 11:15 AM</span>
                        <span class="nf-info-desc">Fundamentals (default)</span>
                    </li>
                    <li>
                        <span class="nf-info-time">5:00 – 6:00 PM</span>
                        <span class="nf-info-desc">Advanced (default)</span>
                    </li>
                </ul>
                <hr class="nf-divider">
                <p class="nf-info-note">
                    <i class="bi bi-exclamation-triangle me-1 text-warning"></i>
                    Members must have remaining session credits before booking. Record a package sale first if needed.
                </p>
                <a href="{{ route('package-sales.create') }}" class="btn nf-btn-secondary w-100 mt-2">
                    <i class="bi bi-bag-plus me-1"></i> Record Package Sale
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
