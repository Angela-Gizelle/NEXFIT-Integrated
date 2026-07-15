@extends('layouts.staff')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle')
    <span>Welcome back, {{ auth('staff')->user()->full_name }}</span>
    @if(auth('staff')->user()->isAdmin())
        <span style="opacity:.45">·</span>
        <span style="color:var(--accent)">Administrator</span>
    @endif
@endsection

@section('content')

    <div class="card">
        <div class="card-body" style="padding: 2rem;">
            <p style="color: var(--text); margin: 0;">
                This is your {{ auth('staff')->user()->isAdmin() ? 'admin' : 'staff' }} dashboard.
                Use the sidebar to manage members and the rest of the studio's day-to-day operations.
            </p>
        </div>
    </div>

@endsection
