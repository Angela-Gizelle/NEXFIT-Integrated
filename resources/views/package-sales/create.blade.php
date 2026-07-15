@extends('layouts.staff')

@section('title', 'Record Package Sale')
@section('page-title', 'Record Package Sale')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/nexfit.css') }}">
@endpush

@section('content')
<div class="pt-3">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="nf-page-title mb-0">Record Package Sale</h1>
            <p class="nf-page-sub mb-0">New enrollment, renewal, additional package, or walk-in</p>
        </div>
        <a href="{{ route('package-sales.index') }}" class="btn nf-btn-ghost">
            <i class="bi bi-arrow-left me-1"></i> Back to Sales
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="nf-card">
                <h2 class="nf-section-title mb-4">Sale Details</h2>

                <form action="{{ route('package-sales.store') }}" method="POST" id="saleForm">
                    @csrf

                    {{-- Member or Walk-in --}}
                    <div class="nf-toggle-tabs mb-4">
                        <button type="button" class="nf-toggle-tab active" data-target="member-panel">Member</button>
                        <button type="button" class="nf-toggle-tab" data-target="walkin-panel">Walk-in</button>
                    </div>

                    <div id="member-panel">
                        <div class="mb-3">
                            <label class="nf-label">Select Member</label>
                            <select name="member_id" class="form-select nf-input @error('member_id') is-invalid @enderror">
                                <option value="">— Select member —</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}"
                                        {{ old('member_id', request('member_id')) == $member->id ? 'selected' : '' }}>
                                        {{ $member->full_name }} (MEM-{{ str_pad($member->id, 3, '0', STR_PAD_LEFT) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('member_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div id="walkin-panel" style="display:none;">
                        <div class="mb-3">
                            <label class="nf-label">Walk-in Name</label>
                            <input type="text" name="walkin_name" value="{{ old('walkin_name') }}"
                                   class="form-control nf-input @error('walkin_name') is-invalid @enderror"
                                   placeholder="Full name of walk-in client">
                            @error('walkin_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row g-3">

                        {{-- Package --}}
                        <div class="col-md-6">
                            <label class="nf-label">Session Package <span class="text-accent">*</span></label>
                            <select name="session_package_id" id="packageSelect"
                                    class="form-select nf-input @error('session_package_id') is-invalid @enderror" required>
                                <option value="">— Select package —</option>
                                @foreach($packages->groupBy('program') as $program => $pkgs)
                                    <optgroup label="{{ ucwords(str_replace('_', ' ', $program)) }}">
                                        @foreach($pkgs as $pkg)
                                            <option value="{{ $pkg->id }}"
                                                    data-price="{{ $pkg->base_price }}"
                                                    data-student="{{ $pkg->student_price }}"
                                                    data-pwd="{{ $pkg->pwd_price }}"
                                                    {{ old('session_package_id') == $pkg->id ? 'selected' : '' }}>
                                                {{ $pkg->name }} — &#8369;{{ number_format($pkg->base_price, 2) }}
                                                ({{ $pkg->session_credits }} sessions)
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('session_package_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Pricing type --}}
                        <div class="col-md-6">
                            <label class="nf-label">Pricing Type <span class="text-accent">*</span></label>
                            <select name="pricing_type" id="pricingType"
                                    class="form-select nf-input @error('pricing_type') is-invalid @enderror" required>
                                <option value="standard" {{ old('pricing_type', 'standard') === 'standard' ? 'selected' : '' }}>Standard</option>
                                <option value="student"  {{ old('pricing_type') === 'student'  ? 'selected' : '' }}>Student Rate</option>
                                <option value="pwd"      {{ old('pricing_type') === 'pwd'      ? 'selected' : '' }}>PWD Rate</option>
                                <option value="promo"    {{ old('pricing_type') === 'promo'    ? 'selected' : '' }}>Promo / Discount</option>
                            </select>
                        </div>

                        {{-- Amount paid --}}
                        <div class="col-md-6">
                            <label class="nf-label">Amount Paid (&#8369;) <span class="text-accent">*</span></label>
                            <input type="number" name="amount_paid" id="amountPaid"
                                   value="{{ old('amount_paid') }}" step="0.01" min="0"
                                   class="form-control nf-input @error('amount_paid') is-invalid @enderror" required>
                            @error('amount_paid') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Payment mode --}}
                        <div class="col-md-6">
                            <label class="nf-label">Payment Mode <span class="text-accent">*</span></label>
                            <select name="payment_mode" class="form-select nf-input @error('payment_mode') is-invalid @enderror" required>
                                <option value="">— Select mode —</option>
                                <option value="cash"          {{ old('payment_mode') === 'cash'          ? 'selected' : '' }}>Cash</option>
                                <option value="gcash"         {{ old('payment_mode') === 'gcash'         ? 'selected' : '' }}>GCash</option>
                                <option value="bank_transfer" {{ old('payment_mode') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="other"         {{ old('payment_mode') === 'other'         ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('payment_mode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Reference number --}}
                        <div class="col-md-6">
                            <label class="nf-label">Reference No. (for GCash / Bank)</label>
                            <input type="text" name="reference_number" value="{{ old('reference_number') }}"
                                   class="form-control nf-input"
                                   placeholder="Optional — transaction reference">
                        </div>

                        {{-- Sale type --}}
                        <div class="col-md-6">
                            <label class="nf-label">Sale Type <span class="text-accent">*</span></label>
                            <select name="sale_type" class="form-select nf-input @error('sale_type') is-invalid @enderror" required>
                                <option value="new_enrollment" {{ old('sale_type', 'new_enrollment') === 'new_enrollment' ? 'selected' : '' }}>New Enrollment</option>
                                <option value="renewal"        {{ old('sale_type') === 'renewal'        ? 'selected' : '' }}>Renewal</option>
                                <option value="additional"     {{ old('sale_type') === 'additional'     ? 'selected' : '' }}>Additional Package</option>
                                <option value="walkin"         {{ old('sale_type') === 'walkin'         ? 'selected' : '' }}>Single Walk-in</option>
                            </select>
                            @error('sale_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Date --}}
                        <div class="col-md-6">
                            <label class="nf-label">Sale Date <span class="text-accent">*</span></label>
                            <input type="date" name="sale_date" value="{{ old('sale_date', date('Y-m-d')) }}"
                                   class="form-control nf-input @error('sale_date') is-invalid @enderror" required>
                            @error('sale_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Time --}}
                        <div class="col-md-6">
                            <label class="nf-label">Sale Time <span class="text-accent">*</span></label>
                            <input type="time" name="sale_time" value="{{ old('sale_time', date('H:i')) }}"
                                   class="form-control nf-input @error('sale_time') is-invalid @enderror" required>
                            @error('sale_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Notes --}}
                        <div class="col-12">
                            <label class="nf-label">Notes</label>
                            <textarea name="notes" rows="2"
                                      class="form-control nf-input"
                                      placeholder="Optional — any relevant notes for this sale">{{ old('notes') }}</textarea>
                        </div>

                        <div class="col-12 d-flex gap-2 justify-content-end pt-2">
                            <a href="{{ route('package-sales.index') }}" class="btn nf-btn-ghost">Cancel</a>
                            <button type="submit" class="btn nf-btn-primary">
                                <i class="bi bi-bag-check me-1"></i> Save Sale & Credit Member
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Summary sidebar --}}
        <div class="col-lg-4">
            <div class="nf-card nf-info-card" id="priceSummary">
                <h3 class="nf-section-title mb-3">
                    <i class="bi bi-receipt me-2 text-accent"></i>Price Reference
                </h3>
                <div id="priceDisplay">
                    <p class="text-muted">Select a package to see pricing details.</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    const packageSelect = document.getElementById('packageSelect');
    const pricingType   = document.getElementById('pricingType');
    const amountPaid    = document.getElementById('amountPaid');
    const priceDisplay  = document.getElementById('priceDisplay');

    // Toggle member / walk-in panels
    document.querySelectorAll('.nf-toggle-tab').forEach(tab => {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.nf-toggle-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            const target = this.dataset.target;
            document.getElementById('member-panel').style.display = target === 'member-panel' ? '' : 'none';
            document.getElementById('walkin-panel').style.display = target === 'walkin-panel' ? '' : 'none';
        });
    });

    // Auto-fill amount based on package + pricing type
    function updatePrice() {
        const opt = packageSelect.options[packageSelect.selectedIndex];
        if (!opt || !opt.value) {
            priceDisplay.innerHTML = '<p class="text-muted">Select a package to see pricing details.</p>';
            return;
        }

        const base    = parseFloat(opt.dataset.price)   || 0;
        const student = parseFloat(opt.dataset.student) || base;
        const pwd     = parseFloat(opt.dataset.pwd)     || base;
        const type    = pricingType.value;

        const price = type === 'student' ? student : (type === 'pwd' ? pwd : base);
        amountPaid.value = price.toFixed(2);

        priceDisplay.innerHTML = `
            <div class="nf-price-row"><span>Standard</span> <strong>&#8369;${base.toFixed(2)}</strong></div>
            <div class="nf-price-row"><span>Student Rate</span> <strong>&#8369;${student.toFixed(2)}</strong></div>
            <div class="nf-price-row"><span>PWD Rate</span> <strong>&#8369;${pwd.toFixed(2)}</strong></div>
            <hr class="nf-divider my-2">
            <div class="nf-price-selected">Applied: <strong class="text-accent">&#8369;${price.toFixed(2)}</strong></div>
        `;
    }

    packageSelect.addEventListener('change', updatePrice);
    pricingType.addEventListener('change', updatePrice);
})();
</script>
@endpush
