<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Fit Urban - Book Your Session')</title>

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

        .topbar {
            background: linear-gradient(180deg, var(--ink) 0%, var(--ink-soft) 100%);
            color: #fff;
            padding: 18px 48px 80px;
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

        .nav-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            position: relative;
            z-index: 1;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: 'Anton', sans-serif;
            font-size: 20px;
            letter-spacing: 0.5px;
        }

        .brand .mark { color: var(--orange); font-size: 18px; }
        .brand .name { color: #fff; }
        .brand .loc {
            font-family: 'Inter', sans-serif;
            font-weight: 400;
            color: #9a9486;
            font-size: 12px;
            margin-left: 10px;
            border-left: 1px solid #444;
            padding-left: 10px;
        }

        .nav-links { display: flex; align-items: center; gap: 24px; }
        .nav-links a { color: #cfc9bb; font-size: 13px; }
        .nav-links a.cta {
            background: #fff;
            color: var(--ink);
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
        }

        .hero {
            position: relative;
            z-index: 1;
            margin-top: 36px;
        }

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
            font-size: 38px;
            line-height: 1.05;
            margin: 0;
            color: #fff;
            letter-spacing: 0.3px;
        }

        .hero h1 .accent { color: var(--orange); display: block; }

        .hero p {
            color: #a39c8c;
            font-size: 14px;
            margin: 14px 0 0;
            max-width: 480px;
            line-height: 1.5;
        }

        .steps {
            max-width: 1240px;
            margin: -36px auto 0;
            padding: 0 48px;
            position: relative;
            z-index: 2;
        }

        .steps-track {
            display: flex;
            align-items: center;
            background: transparent;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .step .dot {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            background: var(--cream);
            border: 1.5px solid var(--text-faint);
            color: var(--text-faint);
        }

        .step.active .dot {
            background: var(--orange);
            border-color: var(--orange);
            color: #fff;
        }

        .step.done .dot {
            background: var(--orange);
            border-color: var(--orange);
            color: #fff;
        }

        .step .label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-faint);
        }

        .step.active .label,
        .step.done .label { color: var(--orange-dark); }

        .step-line {
            flex: 1;
            height: 1.5px;
            background: var(--border);
            margin: 0 14px;
            position: relative;
            top: -1px;
        }

        .step-line.filled { background: var(--orange); }

        .page-body {
            max-width: 1240px;
            margin: 0 auto;
            padding: 40px 48px 80px;
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 24px;
            align-items: start;
        }

       /* ── Tablet (max 900px) ─────────────────────────────────── */
@media (max-width: 900px) {
    .topbar { padding: 16px 24px 60px; }
    .hero h1 { font-size: 30px; }
    .hero p { font-size: 13px; }

    .steps { padding: 0 24px; margin-top: -28px; }
    .step .label { font-size: 11px; }
    .step .dot { width: 22px; height: 22px; font-size: 10px; }

    .page-body {
        grid-template-columns: 1fr;
        padding: 24px 24px 60px;
        gap: 16px;
    }

    .sidebar { position: static; }

    .main-card { padding: 20px 20px; }

    .field-row { grid-template-columns: 1fr; gap: 0; }

    .level-group { grid-template-columns: repeat(3, 1fr); gap: 8px; }
    .level-card { padding: 12px 10px; }
    .level-card .desc { font-size: 10px; }

    .slot-card { flex-wrap: wrap; gap: 8px; }

    .summary-card, .next-card { margin-bottom: 12px; }
}

/* ── Mobile (max 480px) ─────────────────────────────────── */
@media (max-width: 480px) {
    .topbar { padding: 14px 16px 50px; }

    .brand .loc { display: none; }

    .hero h1 { font-size: 24px; }
    .hero p { font-size: 12px; max-width: 100%; }

    .steps { padding: 0 16px; margin-top: -22px; }
    .step .label { display: none; }
    .step-line { margin: 0 6px; }

    .page-body { padding: 16px 16px 50px; gap: 14px; }

    .main-card { padding: 16px 16px; border-radius: 8px; }

    .card-head { gap: 10px; }
    .card-head h2 { font-size: 15px; }
    .card-head .icon { width: 32px; height: 32px; font-size: 14px; }

    .field-row { grid-template-columns: 1fr; gap: 0; }

    .field input,
    .field select,
    .field textarea {
        font-size: 16px; /* prevents iOS zoom on focus */
    }

    .pill-group { gap: 6px; }
    .pill { font-size: 11.5px; padding: 7px 11px; }

    .level-group { grid-template-columns: 1fr; gap: 8px; }
    .level-card { display: flex; align-items: center; gap: 12px; text-align: left; padding: 12px 14px; }
    .level-card .emoji { font-size: 18px; margin: 0; flex-shrink: 0; }
    .level-card .title { font-size: 13px; margin: 0 0 2px; }
    .level-card .desc { font-size: 11px; }

    .yn-row { flex-wrap: wrap; gap: 10px; }
    .yn-row .q { font-size: 12px; width: 100%; }
    .yn-toggle { width: 100%; justify-content: flex-start; }
    .yn-toggle button { flex: 1; }

    .slot-card { flex-direction: column; align-items: flex-start; gap: 6px; }
    .slot-right { align-self: flex-end; }
    .slot-time { font-size: 13px; }

    .btn-row { flex-direction: column-reverse; gap: 10px; }
    .btn-primary, .btn-secondary { width: 100%; text-align: center; justify-content: center; }

    .summary-body { padding: 12px 14px; }
    .summary-row { font-size: 11.5px; }
    .summary-head { padding: 12px 14px; font-size: 12px; }

    .next-card { padding: 12px 14px; }
    .next-card li { font-size: 11.5px; }

    .walkin-card { padding: 14px 16px; }
    .walkin-card h3 { font-size: 13px; }

    .parq-box { padding: 12px 14px; }

    .nav-links a:not(.cta) { display: none; }
    .nav-links .cta { padding: 7px 12px; font-size: 12px; }

    .reco-banner { font-size: 12px; padding: 12px 14px; }
}

        .main-card {
            background: var(--cream-card);
            border: 1px solid var(--border-soft);
            border-radius: var(--radius);
            padding: 28px 32px;
        }

        .card-head {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding-bottom: 18px;
            margin-bottom: 22px;
            border-bottom: 1px solid var(--border-soft);
        }

        .card-head .icon {
            width: 38px;
            height: 38px;
            background: #FBE9D6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }

        .card-head h2 {
            font-size: 16px;
            margin: 0 0 3px;
            font-weight: 700;
        }

        .card-head p {
            font-size: 12.5px;
            color: var(--text-soft);
            margin: 0;
        }

        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
        .field { margin-bottom: 16px; }
        .field label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--text-main);
        }
        .field label .req { color: var(--red); }

        .field input[type=text],
        .field input[type=email],
        .field input[type=tel],
        .field input[type=number],
        .field input[type=date],
        .field input[type=time],
        .field select,
        .field textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border);
            border-radius: 7px;
            background: #FCFAF4;
            font-family: 'Inter', sans-serif;
            font-size: 13.5px;
            color: var(--text-main);
        }

        .field input:focus,
        .field select:focus,
        .field textarea:focus {
            outline: none;
            border-color: var(--orange);
            background: #fff;
        }

        .field input::placeholder, .field textarea::placeholder { color: var(--text-faint); }

        .pill-group { display: flex; flex-wrap: wrap; gap: 8px; }
        .pill {
            border: 1px solid var(--border);
            background: #FCFAF4;
            border-radius: 999px;
            padding: 8px 14px;
            font-size: 12.5px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            user-select: none;
        }
        .pill input { display: none; }
        .pill.selected {
            border-color: var(--orange);
            background: #FDEEE0;
            color: var(--orange-dark);
            font-weight: 700;
        }

        .level-group { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        .level-card {
            border: 1px solid var(--border);
            border-radius: 9px;
            padding: 16px 14px;
            text-align: center;
            cursor: pointer;
            background: #FCFAF4;
        }
        .level-card input { display: none; }
        .level-card .emoji { font-size: 20px; margin-bottom: 8px; }
        .level-card .title { font-size: 13px; font-weight: 700; margin-bottom: 4px; }
        .level-card .desc { font-size: 11px; color: var(--text-soft); line-height: 1.4; }
        .level-card.selected {
            border-color: var(--orange);
            background: #FDEEE0;
        }
        .level-card.selected .title { color: var(--orange-dark); }

        .yn-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-soft);
        }
        .yn-row:last-child { border-bottom: none; }
        .yn-row .q { font-size: 13px; line-height: 1.45; flex: 1; }
        .yn-toggle { display: flex; gap: 6px; flex-shrink: 0; }
        .yn-toggle button {
            border: 1px solid var(--border);
            background: #fff;
            padding: 6px 16px;
            border-radius: 6px;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            color: var(--text-soft);
        }
        .yn-toggle button.active.yes { background: #FCE9E7; border-color: var(--red); color: var(--red); }
        .yn-toggle button.active.no  { background: var(--green-bg); border-color: var(--green); color: var(--green); }

        .parq-box {
            background: #FBF6E9;
            border: 1px solid #EFE2B8;
            border-radius: 9px;
            padding: 16px 18px;
            margin-bottom: 20px;
        }
        .parq-box .parq-title {
            font-size: 13px;
            font-weight: 700;
            color: #9A6A0E;
            margin-bottom: 4px;
        }
        .parq-box .parq-sub {
            font-size: 12px;
            color: #8A7A4E;
            margin-bottom: 6px;
            line-height: 1.5;
        }

        .reco-banner {
            background: #FDEEE0;
            border: 1px solid #F3C99A;
            border-radius: 9px;
            padding: 14px 16px;
            margin-bottom: 20px;
            font-size: 13px;
            line-height: 1.6;
        }
        .reco-banner .reco-title {
            font-weight: 700;
            color: var(--orange-dark);
            font-size: 13px;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .reco-banner b { color: var(--orange-dark); }

        .slot-card {
            border: 1px solid var(--border);
            border-radius: 9px;
            padding: 14px 16px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            background: #FCFAF4;
        }
        .slot-card input { display: none; }
        .slot-card.recommended { border-color: var(--orange); background: #FFF8F0; }
        .slot-card.selected { border-color: var(--orange); background: #FDEEE0; box-shadow: 0 0 0 1px var(--orange); }
        .slot-card.full { opacity: 0.5; cursor: not-allowed; }
        .slot-time { font-size: 14px; font-weight: 700; }
        .slot-meta { font-size: 11.5px; color: var(--text-soft); margin-top: 2px; }
        .slot-right { text-align: right; font-size: 11.5px; }
        .slot-badge {
            display: inline-block;
            background: var(--orange);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 999px;
            margin-bottom: 4px;
        }
        .slot-left { color: var(--green); font-weight: 600; }
        .slot-full-label { color: var(--text-faint); font-weight: 600; }

        .walkin-card {
            background: #fff;
            border: 1px solid var(--border-soft);
            border-radius: var(--radius);
            padding: 18px 24px;
            text-align: center;
            margin-top: 18px;
        }
        .walkin-card h3 { font-size: 13.5px; margin: 0 0 4px; }
        .walkin-card p { font-size: 12px; color: var(--text-soft); margin: 0 0 12px; }
        .walkin-card a.btn-outline {
            display: inline-block;
            border: 1px solid var(--ink);
            color: var(--ink);
            padding: 9px 18px;
            border-radius: 7px;
            font-size: 12.5px;
            font-weight: 600;
        }

        .btn-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 26px;
            padding-top: 18px;
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
            gap: 6px;
        }
        .btn-primary.green { background: var(--green); }
        .btn-secondary {
            background: #fff;
            border: 1px solid var(--border);
            color: var(--text-main);
            padding: 11px 18px;
            border-radius: 7px;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
        }
        .login-hint { font-size: 12.5px; color: var(--text-soft); }
        .login-hint a { color: var(--orange); font-weight: 600; }

        .sidebar { position: sticky; top: 24px; }
        .summary-card {
            border-radius: var(--radius);
            overflow: hidden;
            border: 1px solid var(--border-soft);
            margin-bottom: 16px;
        }
        .summary-head {
            background: var(--ink);
            color: #fff;
            padding: 14px 18px;
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .summary-body { background: #fff; padding: 16px 18px; }
        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid var(--border-soft);
            font-size: 12.5px;
        }
        .summary-row:last-child { border-bottom: none; }
        .summary-row .k { color: var(--text-soft); }
        .summary-row .v { font-weight: 700; color: var(--text-main); text-align: right; }
        .summary-row .v.accent { color: var(--orange-dark); }
        .summary-row .v.blue { color: #3B6FB0; }
        .summary-row .v.muted { color: var(--text-faint); font-weight: 500; }

        .next-card {
            background: #fff;
            border: 1px solid var(--border-soft);
            border-radius: var(--radius);
            padding: 16px 18px;
        }
        .next-card h4 { font-size: 12.5px; margin: 0 0 10px; }
        .next-card ul { list-style: none; margin: 0; padding: 0; }
        .next-card li {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-size: 12px;
            color: var(--text-soft);
            margin-bottom: 9px;
            line-height: 1.4;
        }
        .next-card li .ico { flex-shrink: 0; font-size: 13px; margin-top: 1px; }

        .error-banner {
            background: #FCE9E7;
            border: 1px solid #E7A8A2;
            color: #9A2E26;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 18px;
        }

        .success-banner {
            background: var(--green-bg);
            border: 1px solid #A6E1C5;
            color: #146C45;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 18px;

        @media (max-width: 480px) {
         .topbar { padding: 14px 16px; }
         .brand .loc { display: none; }
          .nav-links a:not(.cta) { display: none; }
        .wrap { margin: 40px auto; padding: 0 16px; }
         h1 { font-size: 22px; }
         .detail-card { padding: 14px 16px; }
          .detail-row { font-size: 12px; }
         .btn-primary { width: 100%; text-align: center; }
}
        }
    </style>

    @stack('styles')
</head>
<body>

    @include('partials.side-nav')

    <div class="fu-content">

    <div class="topbar">
        <div class="nav-row">

           <a href="/" class="brand" style="text-decoration:none;display:flex;align-items:center;gap:10px;">
            <img src="/images/fit_urban_logo_white.png"
                 alt="Fit Urban Logo"
                 style="height:36px; width:auto; object-fit:contain;">
        </a>

            @guest
            <div class="nav-links">
                <a href="#">Programs</a>
                <a href="/booking/membership" class="cta">Book a Session</a>
            </div>
            @endguest
        </div>

        <div class="hero">
            <span class="eyebrow">● Online Booking</span>
            <h1>Book Your<span class="accent">First Session</span></h1>
            <p>Answer a few quick questions so we can match you with the right program, level, and trainer at Fit Urban.</p>
        </div>
    </div>

    <div class="steps">
        <div class="steps-track">
           @php
    $bookingType = session('booking_type', 'guest');
    $isMember = $bookingType === 'member';

    $stepNames = $isMember
        ? ['About You', 'Health Check', 'Choose Schedule', 'Confirm']
        : ['About You', 'Choose Schedule', 'Confirm'];

    $current = $currentStep ?? 1;
@endphp

            @foreach($stepNames as $i => $name)
                @php $n = $i + 1; @endphp

                <div class="step {{ $n < $current ? 'done' : ($n == $current ? 'active' : '') }}">
                    <span class="dot">@if($n < $current)<i class="bi bi-check-lg"></i>@else{{ $n }}@endif</span>
                    <span class="label">{{ $name }}</span>
                </div>

                @if(!$loop->last)
                    <div class="step-line {{ $n < $current ? 'filled' : '' }}"></div>
                @endif
            @endforeach
        </div>
    </div>

    <div class="page-body">
        <div class="main-col">

            @if(session('error'))
                <div class="error-banner">{{ session('error') }}</div>
            @endif

            @if(session('success'))
                <div class="success-banner">{{ session('success') }}</div>
            @endif

            @yield('content')
        </div>

        <div class="sidebar">
            @yield('sidebar')
        </div>
    </div>

    </div><!-- /.fu-content -->

</body>
</html>