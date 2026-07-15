@extends('layouts.trainer')

@section('title', 'Members')
@section('page-title', 'Members')
@section('page-subtitle')
    <span>{{ $members->count() }} total</span>
    <span style="opacity:.45">&middot;</span>
    <span style="color:var(--success)">{{ $activeCount }} active</span>
    <span style="opacity:.45">&middot;</span>
    <span>{{ $inactiveCount }} inactive</span>
@endsection

@section('content')

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-people"></i> My Members</div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-borderless align-middle w-100" style="padding: 0 .5rem;">
                    <thead>
                        <tr>
                            <th class="ps-3">Member</th>
                            <th>Package</th>
                            <th>Credits</th>
                            <th>Expires</th>
                            <th class="pe-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar avatar-sm flex-shrink-0">
                                        {{ strtoupper(substr($member->full_name, 0, 1)) }}{{ strtoupper(substr(strrchr($member->full_name, ' ') ?: '', 1, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold" style="color:var(--text-strong);">{{ $member->full_name }}</div>
                                        <div class="small" style="color:var(--text-faint);">#{{ $member->id }} &middot; {{ $member->fitness_level }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $member->activePackage?->package_type ?? '—' }}</td>
                            <td>
                                @if($member->activePackage)
                                    <span style="color:var(--success); font-weight:600;">{{ $member->activePackage->credits_remaining }}</span>
                                @else
                                    <span style="color:var(--text-faint);">—</span>
                                @endif
                            </td>
                            <td>
                                @if($member->activePackage)
                                    {{ $member->activePackage->coverage_end->format('M d, Y') }}
                                @else
                                    <span style="color:var(--text-faint);">No package</span>
                                @endif
                            </td>
                            <td class="pe-3">
                                <span class="badge badge-{{ strtolower($member->status) }}">{{ $member->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4" style="color: var(--text-faint);">No members assigned yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
