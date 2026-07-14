<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fit Urban - Membership</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --ink: #1a1a1a;
            --ink-soft: #2c2c2c;
            --cream: #F7F2E7;
            --orange: #E8732C;
            --orange-dark: #C95F1F;
            --green: #1FA86A;
            --text-soft: #8A8273;
            --text-faint: #B5AD9C;
            --border: #E7E0CF;
            --border-soft: #EDE7D8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--cream);
            color: var(--ink);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* ── NAV ──────────────────────────────────────────────── */
        nav {
            background: var(--ink);
            padding: 16px 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .brand img {
            height: 36px;
            width: auto;
            object-fit: contain;
        }

        .brand-text {
            font-family: 'Anton', sans-serif;
            font-size: 20px;
            color: #fff;
            letter-spacing: 0.5px;
        }

        .brand-loc {
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            font-weight: 400;
            color: #9a9486;
            border-left: 1px solid #444;
            padding-left: 10px;
            margin-left: 2px;
        }

        .nav-link {
            color: #cfc9bb;
            font-size: 13px;
            text-decoration: none;
        }

        /* ── HERO BANNER ──────────────────────────────────────── */
        .hero {
            background: linear-gradient(160deg, #1a1a1a 0%, #2a2018 60%, #1a1a1a 100%);
            padding: 56px 48px 70px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -60px;
            left: 50%;
            transform: translateX(-50%);
            width: 600px;
            height: 300px;
            background: radial-gradient(ellipse, rgba(232, 115, 44, 0.2) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: 0;
            right: 0;
            height: 50px;
            background: var(--cream);
            clip-path: ellipse(55% 100% at 50% 100%);
        }

        .hero-eyebrow {
            display: inline-block;
            border: 1px solid #5a5346;
            color: #d8d2c2;
            font-size: 11px;
            padding: 5px 14px;
            border-radius: 999px;
            margin-bottom: 18px;
            letter-spacing: 0.5px;
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-family: 'Anton', sans-serif;
            font-weight: 400;
            font-size: 40px;
            line-height: 1.05;
            color: #fff;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .hero h1 .accent {
            color: var(--orange);
        }

        .hero p {
            color: #a39c8c;
            font-size: 14px;
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }

        /* ── BODY ─────────────────────────────────────────────── */
        .wrap {
            max-width: 900px;
            margin: 0 auto;
            padding: 50px 24px 80px;
        }

        .section-label {
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-soft);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 28px;
        }

        /* ── MEMBERSHIP CARD ──────────────────────────────────── */
        .membership-card {
            background: #fff;
            border: 1px solid var(--border-soft);
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .membership-header {
            background: linear-gradient(135deg, var(--ink) 0%, #2c2c2c 100%);
            padding: 28px 32px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }

        .membership-header h2 {
            font-family: 'Anton', sans-serif;
            font-weight: 400;
            font-size: 26px;
            color: #fff;
            margin-bottom: 6px;
            letter-spacing: 0.3px;
        }

        .membership-header p {
            color: #a39c8c;
            font-size: 13px;
            line-height: 1.5;
            max-width: 380px;
        }

        .price-tag {
            text-align: right;
            flex-shrink: 0;
        }

        .price-label {
            font-size: 11px;
            color: #9a9486;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .price-amount {
            font-family: 'Anton', sans-serif;
            font-size: 36px;
            color: var(--orange);
            line-height: 1;
        }

        .price-period {
            font-size: 12px;
            color: #9a9486;
            margin-top: 2px;
        }

        .membership-body {
            padding: 28px 32px;
        }

        .perks-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
            margin-bottom: 28px;
        }

        .perk {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 13px;
            line-height: 1.5;
        }

        .perk .check {
            width: 20px;
            height: 20px;
            background: #EAF8F1;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            color: var(--green);
            flex-shrink: 0;
            margin-top: 1px;
        }

        .perk .perk-text strong {
            display: block;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .perk .perk-text span {
            color: var(--text-soft);
            font-size: 12px;
        }

        .membership-cta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 20px;
            border-top: 1px solid var(--border-soft);
            gap: 16px;
        }

        .membership-cta p {
            font-size: 12.5px;
            color: var(--text-soft);
            line-height: 1.5;
        }

        .btn-primary {
            background: var(--orange);
            color: #fff;
            border: none;
            padding: 13px 28px;
            border-radius: 9px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: var(--orange-dark);
        }

        /* ── DIVIDER ──────────────────────────────────────────── */
        .divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 28px 0;
        }

        .divider hr {
            flex: 1;
            border: none;
            border-top: 1px solid var(--border);
        }

        .divider span {
            font-size: 12px;
            color: var(--text-faint);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ── NO MEMBERSHIP CARD ───────────────────────────────── */
        .simple-card {
            background: #fff;
            border: 1px solid var(--border-soft);
            border-radius: 16px;
            padding: 24px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .simple-card .left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .simple-icon {
            width: 48px;
            height: 48px;
            background: #F5F0E6;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .simple-card h3 {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .simple-card p {
            font-size: 13px;
            color: var(--text-soft);
            line-height: 1.5;
        }

        .btn-secondary {
            background: #fff;
            color: var(--ink);
            border: 1.5px solid var(--border);
            padding: 11px 22px;
            border-radius: 9px;
            font-family: 'Inter', sans-serif;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .btn-secondary:hover {
            border-color: var(--ink);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--text-soft);
            font-size: 13px;
            text-decoration: none;
            margin-bottom: 32px;
        }

        .back-link:hover {
            color: var(--ink);
        }

        /* ── RESPONSIVE ───────────────────────────────────────── */
        @media (max-width: 700px) {
            nav {
                padding: 14px 20px;
            }

            .brand-loc {
                display: none;
            }

            .hero {
                padding: 40px 20px 60px;
            }

            .hero h1 {
                font-size: 28px;
            }

            .membership-header {
                flex-direction: column;
                padding: 22px 20px;
                gap: 16px;
            }

            .price-tag {
                text-align: left;
            }

            .membership-body {
                padding: 20px;
            }

            .perks-grid {
                grid-template-columns: 1fr;
            }

            .membership-cta {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-primary {
                width: 100%;
                justify-content: center;
            }

            .simple-card {
                flex-direction: column;
                align-items: flex-start;
                padding: 20px;
                gap: 16px;
            }

            .btn-secondary {
                width: 100%;
                justify-content: center;
            }

            .wrap {
                padding: 32px 16px 60px;
            }
        }
    </style>
</head>

<body>

    @include('partials.side-nav')

    <div class="fu-content">

        <nav>
            <a href="/" class="brand">
                <img src="/images/fit_urban_logo_white.png" alt="Fit Urban Logo">

                <span class="brand-loc">Barangay I, San Jose, Batangas</span>
            </a>
            @guest
            <a href="#" class="nav-link">Programs</a>
            @endguest
        </nav>

        <div class="hero">
            <div class="hero-eyebrow">● Online Booking</div>
            <h1>Before We Begin,<br><span class="accent">Are You a Member?</span></h1>
            <p>Choose the option that best describes you so we can tailor your booking experience.</p>
        </div>

        <div class="wrap">

            <a href="/" class="back-link">← Back to Home</a>

            <div class="section-label">Choose your path</div>

            {{-- MEMBERSHIP OPTION --}}
            <div class="membership-card">

                <div class="membership-header">
                    <div>
                        <h2>Become a Member</h2>
                        <p>Get full access to Fit Urban's facilities, classes, and personalized training programs with an ongoing membership.</p>
                    </div>
                    <div class="price-tag">
                        <div class="price-label">Starting at</div>
                        <div class="price-amount">₱XXX</div>
                        <div class="price-period">/ month</div>
                    </div>
                </div>

                <div class="membership-body">
                    <div class="perks-grid">
                        <div class="perk">
                            <span class="check"><i class="bi bi-check-lg"></i></span>
                            <div class="perk-text">
                                <strong>Unlimited Gym Access</strong>
                                <span>Use the gym floor anytime during operating hours</span>
                            </div>
                        </div>
                        <div class="perk">
                            <span class="check"><i class="bi bi-check-lg"></i></span>
                            <div class="perk-text">
                                <strong>Personal Training Sessions</strong>
                                <span>Scheduled 1-on-1 sessions with your assigned trainer</span>
                            </div>
                        </div>
                        <div class="perk">
                            <span class="check"><i class="bi bi-check-lg"></i></span>
                            <div class="perk-text">
                                <strong>Pilates Classes</strong>
                                <span>Access to mat and reformer Pilates sessions</span>
                            </div>
                        </div>
                        <div class="perk">
                            <span class="check"><i class="bi bi-check-lg"></i></span>
                            <div class="perk-text">
                                <strong>Personalized Program</strong>
                                <span>Custom training plan based on your goals and level</span>
                            </div>
                        </div>
                        <div class="perk">
                            <span class="check"><i class="bi bi-check-lg"></i></span>
                            <div class="perk-text">
                                <strong>Health Screening</strong>
                                <span>Full PAR-Q assessment and medical clearance support</span>
                            </div>
                        </div>
                        <div class="perk">
                            <span class="check"><i class="bi bi-check-lg"></i></span>
                            <div class="perk-text">
                                <strong>Priority Booking</strong>
                                <span>Get first access to preferred time slots and trainers</span>
                            </div>
                        </div>
                    </div>

                    <div class="membership-cta">
                        <p>Includes full fitness assessment, PAR-Q health screening,<br>and trainer matching — all part of the onboarding process.</p>
                        @auth
                        <a href="{{ route('member.join') }}" class="btn-primary">
                            Yes, I Want Membership →
                        </a>
                        @else
                        <a href="{{ route('register') }}" class="btn-primary">
                            Create an Account →
                        </a>
                        <p style="margin-top:10px; font-size:11.5px;">You'll need an account first — then come back here to activate your membership.</p>
                        @endauth
                    </div>
                </div>

            </div>

            <div class="divider">
                <hr>
                <span>or</span>
                <hr>
            </div>

            {{-- JUST BOOK OPTION --}}
            <div class="simple-card">
                <div class="left">
                    <div class="simple-icon"><i class="bi bi-calendar3" style="color:var(--orange);font-size:22px;"></i></div>
                    <div>
                        <h3>Just Book an Appointment</h3>
                        <p>Skip the membership and simply book a session. Fill in your personal info, pick a schedule, and you're all set.</p>
                    </div>
                </div>
                <a href="/booking/assessment?type=guest" class="btn-secondary">
                    Book Appointment →
                </a>
            </div>

        </div>

    </div>

</body>

</html>