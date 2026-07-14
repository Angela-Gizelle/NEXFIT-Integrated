@extends('layouts.trainer')

@section('title', 'Home')
@section('page-title', 'Home')

@section('content')

    <div class="card">
        <div class="card-body" style="padding: 2.5rem;">
            <p style="font-size: 1.05rem; color: var(--text); margin: 0;">
                Welcome back, {{ auth('trainer')->user()->name }}! This is your NexFit trainer home.
                Use the sidebar to check your dashboard, view your assigned members, manage your
                schedule, or work on training plans.
            </p>
        </div>
    </div>

@endsection
