@extends('layouts.staff')

@section('title', 'Edit ' . $member->full_name)
@section('page-title', 'Edit Member')
@section('page-subtitle')
    <a href="{{ route('members.show', $member) }}">
        <i class="bi bi-arrow-left"></i> Back to {{ $member->full_name }}
    </a>
@endsection

@push('styles')
<style>
    .edit-wrap { max-width: 1180px; margin-inline: auto; }
    .edit-wrap .card { border-radius: 14px; }
    .edit-wrap .card-body { padding: 24px 28px; }

    .section-label {
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: .005em;
        color: var(--text, #111827);
        margin: 0 0 14px;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--border, #e5e7eb);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .section-label::before {
        content: "";
        display: inline-block;
        width: 4px; height: 16px;
        border-radius: 2px;
        background: var(--orange, #f97316);
    }

    .edit-wrap label { font-weight: 600; font-size: .85rem; margin-bottom: 4px; }
    .edit-wrap .form-control, .edit-wrap .form-select { padding: .55rem .7rem; }

    .edit-section + .edit-section { margin-top: 24px; }
    .edit-section .row { --bs-gutter-y: 12px; }

    .form-actions {
        margin-top: 20px;
        padding-top: 16px;
        border-top: 1px solid var(--border, #e5e7eb);
    }
</style>
@endpush

@section('content')

    <div class="edit-wrap">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('members.update', $member) }}">
                    @csrf
                    @method('PUT')

                    {{-- Personal Information --}}
                    <div class="edit-section">
                        <p class="section-label">Personal Information</p>
                        <div class="row g-3">
                            <div class="col-md-6 col-xl-4">
                                <label>Full Name *</label>
                                <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name', $member->full_name) }}" required>
                                @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <label>Email *</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $member->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $member->phone) }}">
                            </div>
                            <div class="col-md-6 col-xl-4">
                                <label>Birthdate</label>
                                <input type="date" name="birthdate" class="form-control" value="{{ old('birthdate', $member->birthdate?->format('Y-m-d')) }}">
                            </div>
                            <div class="col-12 col-xl-8">
                                <label>Address</label>
                                <input type="text" name="address" class="form-control" value="{{ old('address', $member->address) }}">
                            </div>
                            <div class="col-md-6 col-xl-6">
                                <label>Emergency Contact Name</label>
                                <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name', $member->emergency_contact_name) }}">
                            </div>
                            <div class="col-md-6 col-xl-6">
                                <label>Emergency Contact Phone</label>
                                <input type="text" name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone', $member->emergency_contact_phone) }}">
                            </div>
                        </div>
                    </div>

                    {{-- Fitness Classification --}}
                    <div class="edit-section">
                        <p class="section-label">Fitness Classification</p>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label>Fitness Level *</label>
                                <select name="fitness_level" class="form-select" required>
                                    @foreach(['Fundamentals' => 'Fundamentals / Beginner', 'Mid-Level' => 'Mid-Level', 'Advanced' => 'Advanced'] as $val => $label)
                                        <option value="{{ $val }}" {{ old('fitness_level', $member->fitness_level) == $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Population Class *</label>
                                <select name="population_class" class="form-select" required>
                                    <option value="General" {{ old('population_class', $member->population_class) == 'General' ? 'selected' : '' }}>General Population</option>
                                    <option value="Special" {{ old('population_class', $member->population_class) == 'Special' ? 'selected' : '' }}>Special Population</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Status *</label>
                                <select name="status" class="form-select" required>
                                    @foreach(['Active', 'Inactive', 'Churned'] as $status)
                                        <option value="{{ $status }}" {{ old('status', $member->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions d-flex flex-wrap gap-2 justify-content-end">
                        <a href="{{ route('members.show', $member) }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-orange px-4">
                            <i class="bi bi-check2"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
