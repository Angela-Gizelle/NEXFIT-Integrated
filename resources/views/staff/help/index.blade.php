@extends('layouts.staff')

@section('title', 'Help')
@section('page-title', 'Help')
@section('page-subtitle')
    <span>Quick guides, FAQs, and support contact</span>
@endsection

@push('styles')
<style>
    .help-layout { display: flex; gap: 1.5rem; align-items: flex-start; }
    .help-main { flex: 1; }
    .help-side { width: 280px; flex-shrink: 0; }

    .help-card {
        background: var(--surface); border: 1px solid var(--border);
        border-radius: var(--radius); padding: 1.5rem; margin-bottom: 1.25rem;
    }
    .help-card h5 { font-weight: 700; margin-bottom: .25rem; }
    .help-card .card-sub { color: var(--text-faint); font-size: .85rem; margin-bottom: 1rem; }

    .faq-item { border-bottom: 1px solid var(--border); }
    .faq-item:last-child { border-bottom: none; }
    .faq-q {
        display: flex; align-items: center; justify-content: space-between;
        padding: .9rem 0; cursor: pointer; font-weight: 600; color: var(--text-strong);
    }
    .faq-q i { transition: transform .15s ease; color: var(--text-faint); }
    .faq-q.open i { transform: rotate(180deg); }
    .faq-a { display: none; padding-bottom: 1rem; color: var(--text-muted); font-size: .9rem; line-height: 1.6; }
    .faq-a.open { display: block; }

    .contact-row { display: flex; align-items: center; gap: .7rem; padding: .6rem 0; }
    .contact-row i { font-size: 1.05rem; color: var(--accent); width: 20px; text-align: center; }
    .contact-row .label { font-size: .78rem; color: var(--text-faint); }
    .contact-row .value { font-weight: 600; color: var(--text-strong); font-size: .9rem; }

    .about-row { display: flex; justify-content: space-between; padding: .45rem 0; font-size: .85rem; }
    .about-row .k { color: var(--text-faint); }
    .about-row .v { font-weight: 600; color: var(--text-strong); }
</style>
@endpush

@section('content')
<div class="help-layout">

    {{-- MAIN: FAQ --}}
    <div class="help-main">

        <div class="help-card">
            <h5>Frequently Asked Questions</h5>
            <div class="card-sub">Quick answers to common tasks in the Staff Portal</div>

            <div class="faq-list">

                <div class="faq-item">
                    <div class="faq-q"><span>How do I book a session for a member?</span> <i class="bi bi-chevron-down"></i></div>
                    <div class="faq-a">
                        Go to <strong>Scheduling</strong> in the sidebar, pick the trainer and available time slot,
                        then confirm the member and program. The session will appear under
                        <strong>Session Management</strong> once booked.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-q"><span>How do I adjust a member's session credits?</span> <i class="bi bi-chevron-down"></i></div>
                    <div class="faq-a">
                        Open <strong>Session Credit Inventory</strong>, find the member, and use the adjust action
                        to add or deduct credits. Changes are reflected immediately on their balance.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-q"><span>How do I renew or expire a membership?</span> <i class="bi bi-chevron-down"></i></div>
                    <div class="faq-a">
                        Under <strong>Members</strong>, open a member's profile and use the
                        <strong>Renew</strong> action. Expired memberships are flagged automatically
                        based on their package end date.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-q"><span>How do AI Training Plans get generated?</span> <i class="bi bi-chevron-down"></i></div>
                    <div class="faq-a">
                        Training plans are generated automatically after a member completes their booking
                        and PAR-Q questionnaire. You can view or regenerate a plan under
                        <strong>AI Plans</strong>.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-q"><span>I can't log in — what should I check?</span> <i class="bi bi-chevron-down"></i></div>
                    <div class="faq-a">
                        Double-check your email and password under <strong>Settings &rarr; Account</strong>.
                        If you still can't access your account, contact your Studio Administrator to reset it.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-q"><span>Where can I update my profile or password?</span> <i class="bi bi-chevron-down"></i></div>
                    <div class="faq-a">
                        Go to <strong>Settings &rarr; Account</strong> to update your name, email,
                        contact number, or password.
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- SIDE: Contact + About --}}
    <div class="help-side">

        <div class="help-card">
            <h5>Contact Support</h5>
            <div class="card-sub">Reach out for technical issues</div>

            <div class="contact-row">
                <i class="bi bi-envelope-fill"></i>
                <div>
                    <div class="label">Email</div>
                    <div class="value">support@fiturban.test</div>
                </div>
            </div>
            <div class="contact-row">
                <i class="bi bi-telephone-fill"></i>
                <div>
                    <div class="label">Studio Contact</div>
                    <div class="value">Fit Urban, San Jose, Batangas</div>
                </div>
            </div>
            <div class="contact-row">
                <i class="bi bi-person-badge-fill"></i>
                <div>
                    <div class="label">Escalate to</div>
                    <div class="value">Studio Administrator</div>
                </div>
            </div>
        </div>

        <div class="help-card">
            <h5>About NexFit</h5>
            <div class="card-sub">System information</div>

            <div class="about-row">
                <span class="k">Version</span>
                <span class="v">1.0.0</span>
            </div>
            <div class="about-row">
                <span class="k">Modules</span>
                <span class="v">5 integrated</span>
            </div>
            <div class="about-row">
                <span class="k">Studio</span>
                <span class="v">Fit Urban</span>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.faq-q').forEach(function (q) {
        q.addEventListener('click', function () {
            this.classList.toggle('open');
            this.nextElementSibling.classList.toggle('open');
        });
    });
</script>
@endpush
