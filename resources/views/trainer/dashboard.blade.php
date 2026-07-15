@extends('layouts.trainer')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle')
    <span>Overview of your day</span>
@endsection

@section('content')

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="metric-card">
                <div class="metric-label"><span class="metric-dot" style="background:var(--info);"></span>Sessions Today</div>
                <div class="metric-value" style="color:var(--info);">0</div>
                <div class="metric-trend">Nothing scheduled yet</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="metric-card">
                <div class="metric-label"><span class="metric-dot" style="background:var(--success);"></span>Assigned Members</div>
                <div class="metric-value" style="color:var(--success);">{{ auth('trainer')->user()->members()->count() }}</div>
                <div class="metric-trend">Currently on your roster</div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="metric-card">
                <div class="metric-label"><span class="metric-dot" style="background:var(--warn);"></span>Plans Due</div>
                <div class="metric-value" style="color:var(--warn);">0</div>
                <div class="metric-trend">Training plans to update</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-speedometer2"></i> Dashboard</div>
        </div>
        <div class="card-body">
            <p style="color: var(--text-muted); margin: 0;">More dashboard widgets coming soon.</p>
        </div>
    </div>

@endsection
