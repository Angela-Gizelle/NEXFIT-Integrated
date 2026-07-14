@extends('layouts.staff')

@section('title', 'Enroll Member')
@section('page-title', 'Enroll New Member')
@section('page-subtitle')
    <a href="{{ route('members.index') }}">
        <i class="bi bi-arrow-left"></i> Back to Members
    </a>
@endsection

@section('content')

    <div class="row g-4">
        <div class="col-12 col-xl-9">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('members.store') }}">
                        @csrf

                        {{-- REQ-MM-01: Personal Info --}}
                        <p class="section-label">Personal Information</p>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label>Full Name *</label>
                                <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" required>
                                @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label>Email *</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                            </div>
                            <div class="col-md-4">
                                <label>Birthdate</label>
                                <input type="date" name="birthdate" class="form-control" value="{{ old('birthdate') }}">
                            </div>
                            <div class="col-md-4">
                                <label>Enrollment Date *</label>
                                <input type="date" name="enrollment_date" class="form-control @error('enrollment_date') is-invalid @enderror" value="{{ old('enrollment_date', date('Y-m-d')) }}" required>
                                @error('enrollment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label>Address</label>
                                <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                            </div>
                            <div class="col-md-6">
                                <label>Emergency Contact Name</label>
                                <input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name') }}">
                            </div>
                            <div class="col-md-6">
                                <label>Emergency Contact Phone</label>
                                <input type="text" name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone') }}">
                            </div>
                        </div>

                        {{-- REQ-MM-01 & REQ-MM-06: Classification --}}
                        <p class="section-label">Fitness Classification</p>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6 col-lg-3">
                                <label>Fitness Level *</label>
                                <select name="fitness_level" class="form-select" required>
                                    <option value="Fundamentals" {{ old('fitness_level')=='Fundamentals'?'selected':'' }}>Fundamentals / Beginner</option>
                                    <option value="Mid-Level" {{ old('fitness_level')=='Mid-Level'?'selected':'' }}>Mid-Level</option>
                                    <option value="Advanced" {{ old('fitness_level')=='Advanced'?'selected':'' }}>Advanced</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label>Population Class *</label>
                                <select name="population_class" class="form-select" required>
                                    <option value="General" {{ old('population_class')=='General'?'selected':'' }}>General Population</option>
                                    <option value="Special" {{ old('population_class')=='Special'?'selected':'' }}>Special Population</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label>Status *</label>
                                <select name="status" class="form-select" required>
                                    <option value="Active" selected>Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-lg-3">
                                <label>Assigned Trainer</label>
                                <select name="assigned_trainer_id" class="form-select @error('assigned_trainer_id') is-invalid @enderror">
                                    <option value="">— None —</option>
                                    @foreach($trainers as $trainer)
                                        <option value="{{ $trainer->id }}" {{ old('assigned_trainer_id')==$trainer->id?'selected':'' }}>{{ $trainer->name }}</option>
                                    @endforeach
                                </select>
                                @error('assigned_trainer_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        {{-- REQ-MM-02 & REQ-MM-03: Package --}}
                        <p class="section-label">Membership Package</p>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6 col-lg-4">
                                <label>Package Type *</label>
                                <select id="packageType" name="package_type" class="form-select @error('package_type') is-invalid @enderror" required>
                                    @foreach(['Single Session','Monthly','3-Month','6-Month','Annual','Student Rate','PWD Rate'] as $pkg)
                                        <option value="{{ $pkg }}" {{ old('package_type')==$pkg?'selected':'' }}>{{ $pkg }}</option>
                                    @endforeach
                                </select>
                                @error('package_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <label>Session Credits *</label>
                                <input type="number" id="sessionCredits" name="session_credits" class="form-control @error('session_credits') is-invalid @enderror" value="{{ old('session_credits', 1) }}" min="1" required readonly>
                                @error('session_credits')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <label>Amount Paid (&#8369;) *</label>
                                <input type="number" name="amount_paid" class="form-control @error('amount_paid') is-invalid @enderror" value="{{ old('amount_paid') }}" min="0" step="0.01" required>
                                @error('amount_paid')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <label>Purchase Date *</label>
                                <input type="date" name="purchase_date" id="purchaseDate" class="form-control @error('purchase_date') is-invalid @enderror" value="{{ old('purchase_date', date('Y-m-d')) }}" required>
                                @error('purchase_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <label>Coverage Start *</label>
                                <input type="date" name="coverage_start" class="form-control @error('coverage_start') is-invalid @enderror" value="{{ old('coverage_start', date('Y-m-d')) }}" required>
                                @error('coverage_start')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <label>Coverage End (Auto)</label>
                                <input type="text" id="coverageEndPreview" class="form-control" readonly>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <label>Payment Mode *</label>
                                <select name="payment_mode" class="form-select" required>
                                    <option value="Cash">Cash</option>
                                    <option value="GCash">GCash</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <hr>
                        <div class="d-flex flex-wrap gap-2 justify-content-end">
                            <a href="{{ route('members.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-orange px-4">
                                <i class="bi bi-check2"></i> Enroll Member
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <p class="section-label">Enrollment Tips</p>
                    <ul class="ps-3 mb-0" style="color:var(--text-muted); font-size:.85rem; line-height:1.7;">
                        <li>Fill in personal info first, then choose a package.</li>
                        <li>Session credits and coverage end fill automatically from package type.</li>
                        <li>Mark <span class="badge badge-special">Special</span> population if there's a health flag.</li>
                        <li>Assigned trainer can be set later from the member profile.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
(function () {

    const PACKAGE_DURATIONS = {
        'Single Session': 1,
        'Monthly': 30,
        '3-Month': 90,
        '6-Month': 180,
        'Annual': 365,
        'Student Rate': 30,
        'PWD Rate': 30
    };

    const PACKAGE_CREDITS = {
        'Single Session': 1,
        'Monthly': 12,
        '3-Month': 36,
        '6-Month': 72,
        'Annual': 144,
        'Student Rate': 12,
        'PWD Rate': 12
    };

    const packageSelect = document.getElementById('packageType');
    const purchaseDateInput = document.getElementById('purchaseDate');
    const coverageEndPreview = document.getElementById('coverageEndPreview');
    const sessionCreditsInput = document.getElementById('sessionCredits');

    function updateSessionCredits() {
        const packageType = packageSelect.value;
        if (packageType && PACKAGE_CREDITS.hasOwnProperty(packageType)) {
            sessionCreditsInput.value = PACKAGE_CREDITS[packageType];
        }
    }

    function updateCoverageEndPreview() {
        const packageType = packageSelect.value;
        const purchaseDate = purchaseDateInput.value;

        if (!packageType || !purchaseDate) {
            coverageEndPreview.value = '';
            return;
        }

        const days = PACKAGE_DURATIONS[packageType];
        const date = new Date(purchaseDate);
        date.setDate(date.getDate() + days);

        const yyyy = date.getFullYear();
        const mm = String(date.getMonth() + 1).padStart(2, '0');
        const dd = String(date.getDate()).padStart(2, '0');

        coverageEndPreview.value = `${yyyy}-${mm}-${dd}`;
    }

    packageSelect.addEventListener('change', updateSessionCredits);
    packageSelect.addEventListener('change', updateCoverageEndPreview);
    purchaseDateInput.addEventListener('change', updateCoverageEndPreview);

    updateSessionCredits();
    updateCoverageEndPreview();

})();
</script>
@endpush
