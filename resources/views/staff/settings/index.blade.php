@extends('layouts.staff')

@section('title', 'Settings')
@section('page-title', 'Settings')
@section('page-subtitle')
    <span>Manage your account and studio preferences</span>
@endsection

@push('styles')
<style>
    .settings-layout { display: flex; gap: 1.5rem; align-items: flex-start; }
    .settings-tabs {
        width: 220px; flex-shrink: 0;
        background: var(--surface); border: 1px solid var(--border);
        border-radius: var(--radius); overflow: hidden;
    }
    .settings-tabs .tab-link {
        display: flex; align-items: center; gap: .6rem;
        padding: .85rem 1rem; font-size: .9rem; font-weight: 500;
        color: var(--text-muted); text-decoration: none;
        border-bottom: 1px solid var(--border); cursor: pointer;
    }
    .settings-tabs .tab-link:last-child { border-bottom: none; }
    .settings-tabs .tab-link.active { background: var(--accent-soft); color: var(--accent-hover); font-weight: 600; }
    .settings-tabs .tab-link.disabled { opacity: .5; cursor: not-allowed; }
    .settings-tabs .tab-link i { font-size: 1rem; }

    .settings-panel {
        flex: 1; background: var(--surface); border: 1px solid var(--border);
        border-radius: var(--radius); padding: 1.75rem;
    }
    .settings-panel h5 { font-weight: 700; margin-bottom: .25rem; }
    .settings-panel .panel-sub { color: var(--text-faint); font-size: .85rem; margin-bottom: 1.5rem; }
    .settings-panel .panel-hidden { display: none; }

    .settings-form label { font-weight: 600; font-size: .85rem; color: var(--text-strong); }
    .settings-form .form-control { border-color: var(--border); }
    .settings-form .row + .row { margin-top: 1rem; }

    .soon-badge {
        display: inline-block; font-size: .68rem; font-weight: 700;
        padding: .1rem .5rem; border-radius: 999px;
        background: var(--neutral-bg); color: var(--neutral); margin-left: .4rem;
    }
    .empty-state { text-align: center; padding: 2.5rem 1rem; color: var(--text-faint); }
    .empty-state i { font-size: 2rem; display: block; margin-bottom: .5rem; }
</style>
@endpush

@section('content')

@if (session('status') === 'profile-updated')
    <div class="alert alert-success">Profile updated.</div>
@elseif (session('status') === 'password-updated')
    <div class="alert alert-success">Password updated.</div>
@endif

<div class="settings-layout">

    {{-- Tabs --}}
    <div class="settings-tabs">
        <a class="tab-link active" data-tab="account"><i class="bi bi-person-circle"></i> Account</a>
        <a class="tab-link {{ $staff->isAdmin() ? '' : 'disabled' }}" data-tab="studio">
            <i class="bi bi-building"></i> Studio Config
        </a>
        <a class="tab-link {{ $staff->isAdmin() ? '' : 'disabled' }}" data-tab="users">
            <i class="bi bi-people"></i> User Management
        </a>
        <a class="tab-link {{ $staff->isAdmin() ? '' : 'disabled' }}" data-tab="packages">
            <i class="bi bi-box-seam"></i> Packages &amp; Credits
        </a>
    </div>

    <div class="settings-panel">

        {{-- ACCOUNT TAB --}}
        <div class="tab-pane" id="tab-account">
            <h5>Profile Information</h5>
            <div class="panel-sub">Update your name, email, and contact number.</div>

            <form method="POST" action="{{ route('staff.settings.profile.update') }}" class="settings-form">
                @csrf
                @method('PATCH')

                <div class="row">
                    <div class="col-md-6">
                        <label>Full Name</label>
                        <input type="text" name="full_name" class="form-control"
                               value="{{ old('full_name', $staff->full_name) }}">
                        @error('full_name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $staff->email) }}">
                        @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label>Contact Number</label>
                        <input type="text" name="contact_info" class="form-control"
                               value="{{ old('contact_info', $staff->contact_info) }}">
                        @error('contact_info') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6">
                        <label>Role</label>
                        <input type="text" class="form-control" value="{{ $staff->role }}" disabled>
                    </div>
                </div>

                <button type="submit" class="btn mt-3" style="background:var(--accent); color:#fff;">
                    Save Changes
                </button>
            </form>

            <hr class="my-4">

            <h5>Change Password</h5>
            <div class="panel-sub">Use a strong password you don't use elsewhere.</div>

            <form method="POST" action="{{ route('staff.settings.password.update') }}" class="settings-form">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4">
                        <label>Current Password</label>
                        <input type="password" name="current_password" class="form-control">
                        @error('current_password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label>New Password</label>
                        <input type="password" name="password" class="form-control">
                        @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label>Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>

                <button type="submit" class="btn mt-3" style="background:var(--accent); color:#fff;">
                    Update Password
                </button>
            </form>
        </div>

        {{-- STUDIO CONFIG TAB (placeholder) --}}
        <div class="tab-pane panel-hidden" id="tab-studio">
            <h5>Studio Configuration <span class="soon-badge">Coming soon</span></h5>
            <div class="panel-sub">Business hours, slot capacity, and booking rules.</div>
            <div class="empty-state">
                <i class="bi bi-building"></i>
                Not wired up yet — will control business hours, session capacity,
                and cancellation/lead-time rules once the studio-config table is added.
            </div>
        </div>

        {{-- USER MANAGEMENT TAB (placeholder) --}}
        <div class="tab-pane panel-hidden" id="tab-users">
            <h5>User Management <span class="soon-badge">Coming soon</span></h5>
            <div class="panel-sub">Add, deactivate, or reassign roles for staff and trainers.</div>
            <div class="empty-state">
                <i class="bi bi-people"></i>
                Not wired up yet — will let Admins manage Staff/Trainer accounts here.
            </div>
        </div>

        {{-- PACKAGES TAB (placeholder) --}}
        <div class="tab-pane panel-hidden" id="tab-packages">
            <h5>Packages &amp; Credits <span class="soon-badge">Coming soon</span></h5>
            <div class="panel-sub">Manage membership package types, pricing, and credit values.</div>
            <div class="empty-state">
                <i class="bi bi-box-seam"></i>
                Not wired up yet — will manage package pricing shown in the booking flow.
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    document.querySelectorAll('.settings-tabs .tab-link').forEach(function (link) {
        link.addEventListener('click', function () {
            if (this.classList.contains('disabled')) return;

            document.querySelectorAll('.settings-tabs .tab-link').forEach(l => l.classList.remove('active'));
            document.querySelectorAll('.settings-panel .tab-pane').forEach(p => p.classList.add('panel-hidden'));

            this.classList.add('active');
            document.getElementById('tab-' + this.dataset.tab).classList.remove('panel-hidden');
        });
    });
</script>
@endpush
