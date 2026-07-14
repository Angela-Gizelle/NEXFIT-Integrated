@extends('layouts.staff')

@section('title', 'Renew Membership — ' . $member->full_name)
@section('page-title', 'Renew Membership')
@section('page-subtitle')
    <a href="{{ route('members.show', $member) }}">
        <i class="bi bi-arrow-left"></i> Back to {{ $member->full_name }}
    </a>
@endsection

@push('styles')
<style>
    /* Page-scoped polish — uses existing tokens (--warn, --orange, etc.) */
    .renew-wrap { max-width: 1180px; margin-inline: auto; }

    .renew-grid {
        display: grid;
        gap: 24px;
        grid-template-columns: minmax(0, 1fr);
    }
    @media (min-width: 1100px) {
        .renew-grid { grid-template-columns: minmax(320px, 380px) minmax(0, 1fr); align-items: start; }
    }

    /* Tighter card chrome */
    .renew-grid .card { border-radius: 14px; }
    .renew-grid .card-header { padding: 14px 20px; }
    .renew-grid .card-body   { padding: 20px; }
    .renew-grid .card-title  { font-size: 1rem; font-weight: 700; letter-spacing: .01em; margin: 0; }

    /* Compact 2x2 status grid */
    .status-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px 16px;
    }
    .status-grid .info-label { font-size: .72rem; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 2px; }
    .status-grid .info-value { font-size: .95rem; font-weight: 600; line-height: 1.2; }

    /* Form section heading */
    .form-section-title {
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: var(--muted, #6b7280);
        font-weight: 700;
        margin: 4px 0 12px;
    }
    .form-label { font-weight: 600; font-size: .85rem; margin-bottom: 4px; }
    .renew-grid .form-control, .renew-grid .form-select { padding: .55rem .7rem; }

    .form-divider { border: 0; border-top: 1px solid var(--border, #e5e7eb); margin: 18px 0 16px; }

    /* Sticky status card on wide screens for better space use */
    @media (min-width: 1100px) {
        .status-card-sticky { position: sticky; top: 88px; }
    }
</style>
@endpush

@section('content')

    <div class="renew-wrap">
        <div class="renew-grid">

            {{-- Current package summary --}}
            <div class="card status-card-sticky">
                <div class="card-header">
                    <div class="card-title"><i class="bi bi-shield-check"></i> Current Membership Status</div>
                </div>
                <div class="card-body">
                    @if($member->activePackage)
                        <div class="status-grid">
                            <div>
                                <div class="info-label">Package</div>
                                <div class="info-value">{{ $member->activePackage->package_type }}</div>
                            </div>
                            <div>
                                <div class="info-label">Coverage Ends</div>
                                <div class="info-value" style="color:var(--warn); font-weight:700;">
                                    {{ $member->activePackage->coverage_end->format('M d, Y') }}
                                </div>
                            </div>
                            <div>
                                <div class="info-label">Credits Left</div>
                                <div class="info-value">{{ $member->activePackage->credits_remaining }}</div>
                            </div>
                            <div>
                                <div class="info-label">Status</div>
                                <div class="info-value"><span class="badge badge-active">Active</span></div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-expiry mb-0">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <div>No active package on file. Renewing will create a new membership period.</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Renewal form --}}
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="bi bi-arrow-repeat"></i> New Membership Package</div>
                </div>
                <div class="card-body">

                    @if($errors->any())
                        <div class="alert alert-danger mb-3">
                            <i class="bi bi-x-circle-fill"></i>
                            <div>
                                <ul class="mb-0 ps-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('members.renew.store', $member) }}" method="POST">
                        @csrf

                        <p class="form-section-title">Package</p>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Package Type *</label>
                                <select id="renewPackageType" name="package_type"
                                        class="form-select @error('package_type') is-invalid @enderror" required>
                                    <option value="">— Select package —</option>
                                    @foreach(array_keys(\App\Http\Controllers\MemberController::PACKAGE_DURATIONS) as $type)
                                        <option value="{{ $type }}" {{ old('package_type') === $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('package_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-sm-6 col-xl-3">
                                <label class="form-label">Coverage Start *</label>
                                <input type="date"
                                       id="renewCoverageStart"
                                       name="coverage_start"
                                       class="form-control @error('coverage_start') is-invalid @enderror"
                                       value="{{ old('coverage_start', now()->toDateString()) }}"
                                       required>
                                @error('coverage_start') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-sm-6 col-xl-3">
                                <label class="form-label">Coverage End</label>
                                <input type="text" id="renewCoverageEndPreview" class="form-control" readonly
                                       placeholder="Auto from package">
                            </div>

                            <input type="hidden" id="renewSessionCredits" name="session_credits" value="{{ old('session_credits', 1) }}">

                            <div class="col-sm-6 col-xl-3">
                                <label class="form-label">Session Credits</label>
                                <input type="text" id="renewSessionCreditsDisplay" class="form-control" readonly
                                       placeholder="Auto from package">
                            </div>

                            <div class="col-sm-6 col-xl-3">
                                <label class="form-label">Amount Paid (₱) *</label>
                                <input type="number"
                                       name="amount_paid"
                                       class="form-control @error('amount_paid') is-invalid @enderror"
                                       min="0" step="0.01"
                                       value="{{ old('amount_paid') }}"
                                       required>
                                @error('amount_paid') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <p class="form-section-title" style="margin-top: 20px;">Payment</p>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Payment Mode *</label>
                                <select name="payment_mode" class="form-select @error('payment_mode') is-invalid @enderror" required>
                                    <option value="Cash"          {{ old('payment_mode', 'Cash') === 'Cash'          ? 'selected' : '' }}>Cash</option>
                                    <option value="GCash"         {{ old('payment_mode') === 'GCash'         ? 'selected' : '' }}>GCash</option>
                                    <option value="Card"          {{ old('payment_mode') === 'Card'          ? 'selected' : '' }}>Card</option>
                                    <option value="Bank Transfer" {{ old('payment_mode') === 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                </select>
                                @error('payment_mode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <hr class="form-divider">
                        <div class="d-flex flex-wrap gap-2 justify-content-end">
                            <a href="{{ route('members.show', $member) }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-orange">
                                <i class="bi bi-arrow-repeat"></i> Confirm Renewal
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script>
    const PACKAGE_DURATIONS = {
        'Single Session': 1, 'Monthly': 30, '3-Month': 90, '6-Month': 180,
        'Annual': 365, 'Student Rate': 30, 'PWD Rate': 30
    };
    const PACKAGE_CREDITS = {
        'Single Session': 1, 'Monthly': 12, '3-Month': 36, '6-Month': 72,
        'Annual': 144, 'Student Rate': 12, 'PWD Rate': 12
    };

    const packageSelect  = document.getElementById('renewPackageType');
    const startInput     = document.getElementById('renewCoverageStart');
    const endPreview     = document.getElementById('renewCoverageEndPreview');
    const creditsHidden  = document.getElementById('renewSessionCredits');
    const creditsDisplay = document.getElementById('renewSessionCreditsDisplay');

    function updateFields() {
        const pkg   = packageSelect.value;
        const start = startInput.value;

        if (pkg && PACKAGE_CREDITS.hasOwnProperty(pkg)) {
            const c = PACKAGE_CREDITS[pkg];
            creditsHidden.value  = c;
            creditsDisplay.value = c + ' session' + (c !== 1 ? 's' : '');
        } else {
            creditsHidden.value  = 1;
            creditsDisplay.value = '';
        }

        if (pkg && start && PACKAGE_DURATIONS.hasOwnProperty(pkg)) {
            const d = new Date(start);
            d.setDate(d.getDate() + PACKAGE_DURATIONS[pkg]);
            endPreview.value = d.toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
        } else {
            endPreview.value = '';
        }
    }

    packageSelect.addEventListener('change', updateFields);
    startInput.addEventListener('change', updateFields);
    updateFields();
</script>
@endpush
