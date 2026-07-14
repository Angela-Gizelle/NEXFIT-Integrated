<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Fit Urban</title>

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

        /* ── Top hero (no nav links — side nav handles navigation) ── */
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

        /* ── Page body ── */
        .page-body {
            max-width: 1100px;
            margin: -40px auto 0;
            padding: 0 48px 80px;
            position: relative;
            z-index: 2;
        }

        /* ── Profile summary card ── */
        .profile-card {
            background: var(--cream-card);
            border: 1px solid var(--border-soft);
            border-radius: var(--radius);
            padding: 28px 32px;
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .avatar {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #FBE9D6;
            color: var(--orange-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Anton', sans-serif;
            font-size: 22px;
            flex-shrink: 0;
        }

        .profile-info { flex: 1; min-width: 220px; }

        .profile-info h2 { font-size: 18px; margin: 0 0 4px; font-weight: 700; }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--green-bg);
            color: var(--green);
            font-size: 11.5px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 999px;
            margin-bottom: 2px;
        }

        .status-pill.pending { background: #FBE9D6; color: var(--orange-dark); }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(160px, 1fr));
            gap: 14px 32px;
            width: 100%;
            margin-top: 18px;
            padding-top: 18px;
            border-top: 1px solid var(--border-soft);
        }

        .detail-grid dt {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--text-soft);
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 3px;
        }

        .detail-grid dd { margin: 0; font-size: 13.5px; font-weight: 600; color: var(--text-main); }

        .edit-link { font-size: 12.5px; font-weight: 700; color: var(--orange-dark); }

        /* ── My Bookings ── */
        .section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 32px 0 14px;
        }

        .section-heading h3 { font-size: 15px; font-weight: 700; margin: 0; }

        .bookings-list { display: flex; flex-direction: column; gap: 12px; margin-bottom: 8px; }

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
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #FBE9D6;
            color: var(--orange-dark);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .booking-row .b-main { flex: 1; min-width: 180px; }
        .booking-row .b-main .b-service { font-size: 14px; font-weight: 700; margin-bottom: 2px; }
        .booking-row .b-main .b-meta { font-size: 12px; color: var(--text-soft); }

        .booking-row .b-when { text-align: right; font-size: 12.5px; color: var(--text-main); font-weight: 600; }
        .booking-row .b-when .b-time { display: block; font-size: 11.5px; color: var(--text-soft); font-weight: 500; margin-top: 2px; }

        .b-status {
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }
        .b-status.confirmed  { background: var(--green-bg); color: var(--green); }
        .b-status.pending    { background: #FBE9D6; color: var(--orange-dark); }
        .b-status.cancelled  { background: #FCE9E7; color: var(--red); }
        .b-status.rescheduled{ background: #EAF1FB; color: #3B6FB0; }
        .b-status.walk-in    { background: var(--border-soft); color: var(--text-soft); }

        .bookings-empty {
            background: var(--cream-card);
            border: 1px dashed var(--border);
            border-radius: var(--radius);
            padding: 24px;
            text-align: center;
            color: var(--text-soft);
            font-size: 13px;
            margin-bottom: 8px;
        }

        /* ── Action cards ── */
        .action-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

        .action-card {
            background: var(--cream-card);
            border: 1px solid var(--border-soft);
            border-radius: var(--radius);
            padding: 26px 28px;
            display: flex;
            flex-direction: column;
        }

        .action-card .icon {
            width: 42px;
            height: 42px;
            background: #FBE9D6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: var(--orange-dark);
            margin-bottom: 14px;
        }

        .action-card.member .icon { background: var(--green-bg); color: var(--green); }

        .action-card h3 { font-size: 16px; margin: 0 0 6px; font-weight: 700; }

        .action-card p {
            font-size: 13px;
            color: var(--text-soft);
            line-height: 1.5;
            margin: 0 0 20px;
            flex: 1;
        }

        .btn-primary {
            background: var(--orange);
            color: #fff;
            border: none;
            padding: 11px 22px;
            border-radius: 7px;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: fit-content;
        }
        .btn-primary.green { background: var(--green); }

        /* ── Mobile ── */
        @media (max-width: 900px) {
            .topbar { padding: 26px 24px 56px; }
            .hero h1 { font-size: 28px; }
            .page-body { padding: 0 24px 60px; }
            .action-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 480px) {
            .topbar { padding: 22px 16px 48px; }
            .hero h1 { font-size: 22px; }
            .page-body { margin-top: -32px; padding: 0 16px 50px; }
            .profile-card { padding: 20px; }
            .detail-grid { grid-template-columns: 1fr; }
            .action-card { padding: 20px; }
            .btn-primary { width: 100%; }
            .booking-row { padding: 14px 16px; }
            .booking-row .b-when { text-align: left; width: 100%; }
        }
    </style>
</head>
<body>

    @include('partials.side-nav')

    <div class="fu-content">

    <div class="topbar">
        <div class="hero">
            <span class="eyebrow">MY PROFILE</span>
            <h1>Welcome back, <span class="accent">{{ explode(' ', Auth::user()->name)[0] }}</span></h1>
            <p>Here's your account at a glance. Book a session anytime — no membership required — or become a member for unlimited access.</p>
        </div>
    </div>

    <div class="page-body">

        @if(session('success'))
            <div style="background:var(--green-bg); color:var(--green); border:1px solid #bfe9d4; border-radius:var(--radius); padding:12px 18px; font-size:13px; font-weight:600; margin-bottom:20px;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background:#FCE9E7; color:var(--red); border:1px solid #f3c6c1; border-radius:var(--radius); padding:12px 18px; font-size:13px; font-weight:600; margin-bottom:20px;">
                {{ session('error') }}
            </div>
        @endif

        <!-- Profile summary -->
        <div class="profile-card">
            <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>

            <div class="profile-info">
                <h2>{{ Auth::user()->name }}</h2>
                <span class="status-pill pending">
                    <i class="bi bi-person-check"></i> Registered User
                </span>
            </div>

            <a href="{{ route('profile.edit') }}" class="edit-link">Edit profile →</a>

            <dl class="detail-grid">
                <div>
                    <dt>Email address</dt>
                    <dd>{{ Auth::user()->email }}</dd>
                </div>
                <div>
                    <dt>Member since</dt>
                    <dd>{{ Auth::user()->created_at?->format('M d, Y') ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        <!-- My Bookings -->
        <div class="section-heading">
            <h3>My Bookings</h3>
        </div>

        @if($bookings->isEmpty())
            <div class="bookings-empty">
                You haven't booked a session yet — use "Book a Session" below to get started.
            </div>
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

        <!-- Action cards -->
        <div class="action-grid">
            <div class="action-card">
                <div class="icon"><i class="bi bi-calendar-check"></i></div>
                <h3>Book a Session</h3>
                <p>Take a quick fitness assessment and get matched with a program, trainer, and time slot that fits you.</p>
                <a href="{{ route('booking.assessment') }}" class="btn-primary">
                    Start booking <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            <div class="action-card member">
                <div class="icon"><i class="bi bi-star-fill"></i></div>
                <h3>Become a Member</h3>
                <p>Explore membership plans and get unlimited access to the training floor and open gym hours.</p>
                <a href="{{ route('booking.membership') }}" class="btn-primary green">
                    View plans <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>

    </div>

    </div><!-- /.fu-content -->

</body>
</html>