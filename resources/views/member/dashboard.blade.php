<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Membership - Fit Urban</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --ink:          #1a1a1a;
            --ink-soft:     #2c2c2c;
            --cream:        #F7F2E7;
            --cream-card:   #FFFFFF;
            --orange:       #E8732C;
            --orange-dark:  #C95F1F;
            --green:        #1FA86A;
            --green-bg:     #EAF8F1;
            --red:          #D1453B;
            --red-bg:       #FCE9E7;
            --text-main:    #2B2B2B;
            --text-soft:    #8A8273;
            --text-faint:   #B5AD9C;
            --border:       #E7E0CF;
            --border-soft:  #EDE7D8;
            --radius:       10px;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--cream);
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
            -webkit-font-smoothing: antialiased;
        }

        a { text-decoration: none; }

        .topbar {
            background: linear-gradient(180deg, var(--ink) 0%, var(--ink-soft) 100%);
            color: #fff;
            padding: 34px 48px 70px;
            position: relative;
            overflow: hidden;
        }

        .topbar::after {
            content: '';
            position: absolute;
            top: -40%;
            right: -10%;
            width: 480px;
            height: 480px;
            background: radial-gradient(circle, rgba(232,115,44,0.25) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero { position: relative; z-index: 1; }

        .hero .eyebrow {
            display: inline-block;
            border: 1px solid #5a5346;
            color: #d8d2c2;
            font-size: 11px;
            padding: 4px 12px;
            border-radius: 999px;
            margin-bottom: 14px;
            letter-spacing: 0.3px;
        }

        .hero h1 {
            font-family: 'Anton', sans-serif;
            font-weight: 400;
            font-size: 34px;
            line-height: 1.1;
            margin: 0;
            color: #fff;
            letter-spacing: 0.3px;
        }

        .hero h1 .accent { color: var(--orange); }

        .hero p {
            color: #a39c8c;
            font-size: 14px;
            margin: 10px 0 0;
            max-width: 480px;
            line-height: 1.5;
        }

        .page-body {
            max-width: 1100px;
            margin: -40px auto 0;
            padding: 0 48px 80px;
            position: relative;
            z-index: 2;
        }

        .flash {
            background: var(--green-bg);
            color: var(--green);
            border: 1px solid #bfe9d4;
            border-radius: var(--radius);
            padding: 12px 18px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .grid-2 { display: grid; grid-template-columns: 1.3fr 1fr; gap: 20px; align-items: start; }

        .card {
            background: var(--cream-card);
            border: 1px solid var(--border-soft);
            border-radius: var(--radius);
            padding: 26px 28px;
            margin-bottom: 20px;
        }

        .card-head-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 10px; }
        .card-head-row h3 { font-size: 15px; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px; }
        .card-head-row h3 i { color: var(--orange-dark); }

        .avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #FBE9D6;
            color: var(--orange-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Anton', sans-serif;
            font-size: 20px;
            flex-shrink: 0;
        }

        .profile-top { display: flex; align-items: center; gap: 16px; margin-bottom: 18px; }
        .profile-top h2 { font-size: 18px; margin: 0 0 4px; font-weight: 700; }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--green-bg);
            color: var(--green);
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .status-pill.inactive { background: #F1EEE4; color: var(--text-soft); }
        .status-pill.churned  { background: var(--red-bg); color: var(--red); }
        .status-pill.special  { background: #FBE9D6; color: var(--orange-dark); }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(140px, 1fr));
            gap: 14px 24px;
        }

        .detail-grid dt { font-size: 11px; font-weight: 600; color: var(--text-soft); text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 3px; }
        .detail-grid dd { margin: 0; font-size: 13.5px; font-weight: 600; color: var(--text-main); }

        /* ── Package / sessions ── */
        .pkg-stat-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 16px; }
        .pkg-stat { background: var(--cream); border: 1px solid var(--border-soft); border-radius: 8px; padding: 14px; text-align: center; }
        .pkg-stat .num { font-family: 'Anton', sans-serif; font-size: 22px; color: var(--orange-dark); }
        .pkg-stat .lbl { font-size: 10.5px; color: var(--text-soft); text-transform: uppercase; letter-spacing: 0.3px; margin-top: 2px; }

        .progress-track { width: 100%; height: 8px; background: var(--border-soft); border-radius: 99px; overflow: hidden; margin: 4px 0 18px; }
        .progress-fill { height: 100%; background: var(--orange); border-radius: 99px; }

        .empty-note {
            background: var(--cream-card);
            border: 1px dashed var(--border);
            border-radius: var(--radius);
            padding: 22px;
            text-align: center;
            color: var(--text-soft);
            font-size: 13px;
        }

        .btn-primary {
            background: var(--orange);
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: fit-content;
        }
        .btn-primary.green { background: var(--green); }
        .btn-outline {
            background: transparent;
            color: var(--orange-dark);
            border: 1px solid var(--orange-dark);
            padding: 9px 18px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        /* ── Bookings ── */
        .bookings-list { display: flex; flex-direction: column; gap: 12px; }
        .booking-row {
            background: var(--cream-card);
            border: 1px solid var(--border-soft);
            border-radius: var(--radius);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }
        .booking-row .b-icon {
            width: 40px; height: 40px; border-radius: 8px;
            background: #FBE9D6; color: var(--orange-dark);
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; flex-shrink: 0;
        }
        .booking-row .b-main { flex: 1; min-width: 180px; }
        .booking-row .b-main .b-service { font-size: 14px; font-weight: 700; margin-bottom: 2px; }
        .booking-row .b-main .b-meta { font-size: 12px; color: var(--text-soft); }
        .booking-row .b-when { text-align: right; font-size: 12.5px; font-weight: 600; }
        .booking-row .b-when .b-time { display: block; font-size: 11.5px; color: var(--text-soft); font-weight: 500; margin-top: 2px; }
        .b-status { font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 999px; text-transform: uppercase; letter-spacing: 0.3px; white-space: nowrap; }
        .b-status.confirmed  { background: var(--green-bg); color: var(--green); }
        .b-status.pending    { background: #FBE9D6; color: var(--orange-dark); }
        .b-status.cancelled  { background: var(--red-bg); color: var(--red); }
        .b-status.rescheduled{ background: #EAF1FB; color: #3B6FB0; }
        .b-status.walk-in    { background: var(--border-soft); color: var(--text-soft); }

        /* ── ParQ ── */
        .parq-flag { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; }
        .parq-chip { font-size: 11.5px; font-weight: 600; padding: 5px 10px; border-radius: 999px; background: var(--green-bg); color: var(--green); }
        .parq-chip.yes { background: var(--red-bg); color: var(--red); }
        .parq-note { font-size: 12.5px; color: var(--text-soft); }

        .yn-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--border-soft); }
        .yn-row .q { font-size: 13px; line-height: 1.4; flex: 1; }
        .yn-toggle { display: flex; gap: 6px; flex-shrink: 0; }
        .yn-toggle button {
            border: 1px solid var(--border); background: #fff; color: var(--text-soft);
            font-size: 12px; font-weight: 700; padding: 6px 14px; border-radius: 6px; cursor: pointer;
        }
        .yn-toggle button.active.yes { background: var(--red-bg); border-color: var(--red); color: var(--red); }
        .yn-toggle button.active.no  { background: var(--green-bg); border-color: var(--green); color: var(--green); }

        .parq-modal-backdrop {
            display: none;
            position: fixed; inset: 0; background: rgba(26,26,26,0.55);
            z-index: 200; align-items: center; justify-content: center; padding: 20px;
        }
        .parq-modal-backdrop.open { display: flex; }
        .parq-modal {
            background: #fff; border-radius: var(--radius); max-width: 560px; width: 100%;
            max-height: 90vh; overflow-y: auto; padding: 28px 30px;
        }
        .parq-modal h3 { margin: 0 0 4px; font-size: 17px; }
        .parq-modal .sub { font-size: 12.5px; color: var(--text-soft); margin-bottom: 12px; }
        .parq-modal textarea, .parq-modal .field {
            width: 100%; border: 1px solid var(--border); border-radius: 7px; padding: 10px 12px;
            font-family: inherit; font-size: 13px; margin-top: 14px; resize: vertical;
        }
        .modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
        .btn-ghost { background: none; border: none; color: var(--text-soft); font-weight: 700; font-size: 13px; cursor: pointer; padding: 10px 14px; }

        /* ── AI plan placeholder ── */
        .plan-placeholder {
            text-align: center;
            padding: 30px 16px;
            color: var(--text-soft);
        }
        .plan-placeholder i { font-size: 26px; color: var(--orange-dark); margin-bottom: 10px; display: block; }
        .plan-placeholder .tag {
            display: inline-block; background: var(--border-soft); color: var(--text-soft);
            font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px;
            padding: 4px 10px; border-radius: 999px; margin-top: 10px;
        }

        @media (max-width: 900px) {
            .topbar { padding: 26px 24px 56px; }
            .hero h1 { font-size: 28px; }
            .page-body { padding: 0 24px 60px; }
            .grid-2 { grid-template-columns: 1fr; }
            .pkg-stat-row { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 480px) {
            .topbar { padding: 22px 16px 48px; }
            .hero h1 { font-size: 22px; }
            .page-body { margin-top: -32px; padding: 0 16px 50px; }
            .card { padding: 20px; }
            .detail-grid { grid-template-columns: 1fr; }
            .pkg-stat-row { grid-template-columns: 1fr 1fr; }
            .yn-row { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>

    @include('partials.side-nav')

    <div class="fu-content">

    <div class="topbar">
        <div class="hero">
            <span class="eyebrow">MEMBER DASHBOARD</span>
            <h1>Welcome back, <span class="accent">{{ explode(' ', $member->full_name)[0] }}</span></h1>
            <p>Your membership, health screening, sessions, and bookings — all in one place.</p>
        </div>
    </div>

    <div class="page-body">

        @if(session('success'))
            <div class="flash">{{ session('success') }}</div>
        @endif

        <div class="grid-2">
            <div>
                <!-- Profile -->
                <div class="card">
                    <div class="profile-top">
                        <div class="avatar">{{ strtoupper(substr($member->full_name, 0, 1)) }}</div>
                        <div>
                            <h2>{{ $member->full_name }}</h2>
                            <span class="status-pill {{ strtolower($member->status) }}">
                                <i class="bi bi-patch-check-fill"></i> {{ $member->status }} Member
                            </span>
                            @if($member->population_class === 'Special')
                                <span class="status-pill special"><i class="bi bi-shield-plus"></i> Special Population</span>
                            @endif
                        </div>
                    </div>

                    <dl class="detail-grid">
                        <div>
                            <dt>Email</dt>
                            <dd>{{ $member->email }}</dd>
                        </div>
                        <div>
                            <dt>Phone</dt>
                            <dd>{{ $member->phone ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt>Enrollment date</dt>
                            <dd>{{ $member->enrollment_date?->format('M d, Y') ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt>Fitness level</dt>
                            <dd>{{ $member->fitness_level }}</dd>
                        </div>
                        <div>
                            <dt>Assigned trainer</dt>
                            <dd>{{ $member->trainer->name ?? 'Not yet assigned' }}</dd>
                        </div>
                        <div>
                            <dt>Emergency contact</dt>
                            <dd>{{ $member->emergency_contact_name ?? '—' }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- My Bookings -->
                <div class="card">
                    <div class="card-head-row">
                        <h3><i class="bi bi-calendar-event"></i> My Bookings</h3>
                        <a href="{{ route('booking.assessment') }}" class="btn-outline">Book a session</a>
                    </div>

                    @if($bookings->isEmpty())
                        <div class="empty-note">You haven't booked a session yet.</div>
                    @else
                        <div class="bookings-list">
                            @foreach($bookings as $booking)
                                <div class="booking-row">
                                    <div class="b-icon"><i class="bi bi-calendar-event"></i></div>
                                    <div class="b-main">
                                        <div class="b-service">{{ $booking->service->name ?? 'Session' }}</div>
                                        <div class="b-meta">
                                            {{ $booking->trainer->name ?? 'Open Gym' }}
                                            @if($booking->program) &middot; {{ $booking->program->name }} @endif
                                        </div>
                                    </div>
                                    <div class="b-when">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}
                                        <span class="b-time">{{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}</span>
                                    </div>
                                    <span class="b-status {{ strtolower($booking->status) }}">{{ $booking->status }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div>
                <!-- Membership package / sessions availed -->
                <div class="card">
                    <div class="card-head-row">
                        <h3><i class="bi bi-credit-card-2-front-fill"></i> Membership Package</h3>
                    </div>

                    @if($member->activePackage)
                        @php $pkg = $member->activePackage; $pct = $pkg->session_credits > 0 ? min(100, round(($pkg->credits_used / $pkg->session_credits) * 100)) : 0; @endphp
                        <dl class="detail-grid" style="margin-bottom:16px;">
                            <div>
                                <dt>Plan</dt>
                                <dd>{{ $pkg->package_type }}</dd>
                            </div>
                            <div>
                                <dt>Coverage</dt>
                                <dd>{{ $pkg->coverage_start->format('M d') }} – {{ $pkg->coverage_end->format('M d, Y') }}</dd>
                            </div>
                        </dl>

                        <div class="pkg-stat-row">
                            <div class="pkg-stat">
                                <div class="num">{{ $pkg->session_credits }}</div>
                                <div class="lbl">Total sessions</div>
                            </div>
                            <div class="pkg-stat">
                                <div class="num">{{ $pkg->credits_used }}</div>
                                <div class="lbl">Sessions availed</div>
                            </div>
                            <div class="pkg-stat">
                                <div class="num">{{ $pkg->credits_remaining }}</div>
                                <div class="lbl">Remaining</div>
                            </div>
                        </div>

                        <div class="progress-track"><div class="progress-fill" style="width:{{ $pct }}%"></div></div>

                        @if($member->isNearExpiry())
                            <a href="{{ route('booking.membership') }}" class="btn-primary">Renew membership <i class="bi bi-arrow-right"></i></a>
                        @endif
                    @else
                        <div class="empty-note">No active package on file yet.</div>
                        <div style="margin-top:14px;">
                            <a href="{{ route('booking.membership') }}" class="btn-primary green">View membership plans</a>
                        </div>
                    @endif
                </div>

                <!-- PAR-Q -->
                <div class="card">
                    <div class="card-head-row">
                        <h3><i class="bi bi-heart-pulse-fill"></i> PAR-Q Health Check</h3>
                        <button type="button" class="btn-outline" onclick="document.getElementById('parqModal').classList.add('open')">Reassess</button>
                    </div>

                    @if($member->latestParq)
                        @php $parq = $member->latestParq; @endphp
                        <div class="parq-note" style="margin-bottom:10px;">Last assessed {{ $parq->assessment_date?->format('M d, Y') ?? $parq->created_at->format('M d, Y') }}</div>
                        <div class="parq-flag">
                            @php
                                $flags = [
                                    'Heart condition'   => $parq->heart_condition,
                                    'Chest pain'        => $parq->chest_pain_activity || $parq->chest_pain_rest,
                                    'Dizziness/balance'  => $parq->dizziness_balance,
                                    'Bone/joint issue'  => $parq->bone_joint_condition,
                                    'BP medication'     => $parq->blood_pressure_medication,
                                    'Other condition'   => $parq->other_medical_reason,
                                ];
                            @endphp
                            @foreach($flags as $label => $val)
                                <span class="parq-chip {{ $val ? 'yes' : '' }}">{{ $label }}: {{ $val ? 'Yes' : 'No' }}</span>
                            @endforeach
                        </div>
                        @if($parq->medical_clearance_required)
                            <div class="parq-note" style="color:var(--red); font-weight:600;">Medical clearance recommended before training.</div>
                        @endif
                    @else
                        <div class="empty-note">No PAR-Q on file yet — take the health screening to get matched safely with a trainer.</div>
                    @endif
                </div>

                <!-- AI Training Plan -->
                <div class="card">
                    <div class="card-head-row">
                        <h3><i class="bi bi-cpu-fill"></i> AI Training Plan</h3>
                    </div>
                    @php $currentPlan = $member->user?->trainingPlans()->where('is_current', true)->first(); @endphp
                    @if ($currentPlan)
                        <div class="plan-placeholder">
                            <i class="bi bi-stars"></i>
                            Your personalized training plan is ready.
                            <div class="tag"><a href="{{ route('member.training-plan.show') }}" style="color:inherit;">View plan &rarr;</a></div>
                        </div>
                    @else
                        <div class="plan-placeholder">
                            <i class="bi bi-stars"></i>
                            Generate a personalized, AI-assisted training plan based on your profile.
                            <div class="tag"><a href="{{ route('member.training-plan.show') }}" style="color:inherit;">Get started &rarr;</a></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    </div><!-- /.fu-content -->

    <!-- PAR-Q Reassessment Modal -->
    <div class="parq-modal-backdrop" id="parqModal">
        <div class="parq-modal">
            <h3>PAR-Q Health Screening</h3>
            <div class="sub">Answer Yes or No to each question. This helps us keep your training plan safe and up to date.</div>

            <form method="POST" action="{{ route('member.parq.store') }}">
                @csrf

                @php
                    $questions = [
                        'heart_condition'           => 'Has your doctor ever said that you have a heart condition and that you should only do physical activity recommended by a doctor?',
                        'chest_pain_activity'       => 'Do you feel pain in your chest when you do physical activity?',
                        'chest_pain_rest'           => 'Have you had chest pain when not doing physical activity in the past month?',
                        'dizziness_balance'         => 'Do you lose your balance because of dizziness, or do you ever lose consciousness?',
                        'bone_joint_condition'      => 'Do you have a bone or joint problem that could worsen with a change in physical activity?',
                        'blood_pressure_medication' => 'Do you have high blood pressure or are you currently taking medication for it?',
                        'other_medical_reason'      => 'Do you know of any other reason why you should not do physical activity?',
                    ];
                @endphp

                @foreach($questions as $key => $text)
                    <div class="yn-row" data-question="{{ $key }}">
                        <span class="q">{{ $text }}</span>
                        <div class="yn-toggle">
                            <button type="button" class="yes" data-key="{{ $key }}" data-val="1">Yes</button>
                            <button type="button" class="no" data-key="{{ $key }}" data-val="0">No</button>
                        </div>
                        <input type="hidden" name="{{ $key }}" id="parq_input_{{ $key }}" value="0">
                    </div>
                @endforeach

                <textarea name="health_notes" rows="2" placeholder="Any other health notes for your trainer? (optional)"></textarea>

                <div class="modal-actions">
                    <button type="button" class="btn-ghost" onclick="document.getElementById('parqModal').classList.remove('open')">Cancel</button>
                    <button type="submit" class="btn-primary">Submit reassessment</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.yn-toggle button').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const key = this.dataset.key;
                const row = document.querySelector('.yn-row[data-question="' + key + '"]');
                row.querySelectorAll('.yn-toggle button').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('parq_input_' + key).value = this.dataset.val;
            });
        });
    </script>

</body>
</html>
