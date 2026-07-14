<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Training Plan - Fit Urban</title>

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
            max-width: 900px;
            margin: -40px auto 0;
            padding: 0 48px 80px;
            position: relative;
            z-index: 2;
        }

        .flash {
            border-radius: var(--radius);
            padding: 12px 18px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .flash.success { background: var(--green-bg); color: var(--green); border: 1px solid #bfe9d4; }
        .flash.error   { background: var(--red-bg); color: var(--red); border: 1px solid #f3c9c5; }

        .card {
            background: var(--cream-card);
            border: 1px solid var(--border-soft);
            border-radius: var(--radius);
            padding: 28px;
            margin-bottom: 20px;
        }

        .card-head-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 10px; }
        .card-head-row h3 { font-size: 15px; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px; }
        .card-head-row h3 i { color: var(--orange-dark); }

        .meta-row { display: flex; flex-wrap: wrap; gap: 18px; font-size: 13px; color: var(--text-soft); margin-bottom: 6px; }
        .meta-row strong { color: var(--text-main); }

        .plan-section h4 {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: var(--orange-dark);
            margin: 24px 0 12px;
        }

        .plan-section ul { margin: 0; padding-left: 20px; }
        .plan-section li { font-size: 14px; line-height: 1.6; margin-bottom: 8px; color: var(--text-main); }

        .empty-note {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-soft);
            font-size: 14px;
        }
        .empty-note i { font-size: 32px; color: var(--text-faint); display: block; margin-bottom: 12px; }

        .btn-primary {
            display: inline-block;
            background: var(--orange);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 22px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
        }
        .btn-primary:hover { background: var(--orange-dark); }

        .btn-outline {
            display: inline-block;
            background: none;
            border: 1px solid var(--border);
            color: var(--text-main);
            border-radius: 8px;
            padding: 11px 20px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
        }

        .btn-ghost { background: none; border: none; color: var(--text-soft); font-weight: 700; font-size: 13px; cursor: pointer; padding: 10px 14px; }

        .badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 999px;
            background: var(--green-bg);
            color: var(--green);
        }
        .badge.fallback { background: #FCEFDB; color: var(--orange-dark); }

        @media (max-width: 640px) {
            .topbar { padding: 24px 20px 60px; }
            .page-body { padding: 0 16px 60px; }
            .card { padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="hero">
            <span class="eyebrow">FIT URBAN &middot; MEMBER PORTAL</span>
            <h1>My Training <span class="accent">Plan</span></h1>
            <p>A personalized pre- and post-session plan, matched to your assigned trainer and fitness profile.</p>
        </div>
    </div>

    <div class="page-body">
        <a href="{{ route('member.dashboard') }}" class="btn-ghost"><i class="bi bi-arrow-left"></i> Back to dashboard</a>

        @if (session('success'))
            <div class="flash success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="flash error">{{ session('error') }}</div>
        @endif

        @if ($plan)
            <div class="card">
                <div class="card-head-row">
                    <h3><i class="bi bi-clipboard2-pulse"></i> {{ $plan->plan_content['title'] ?? 'Training Plan' }}</h3>
                    @if (($plan->generated_by ?? '') === 'default-fallback')
                        <span class="badge fallback">Standard plan</span>
                    @else
                        <span class="badge">AI-generated</span>
                    @endif
                </div>

                <div class="meta-row">
                    <span>Assigned trainer: <strong>{{ $plan->trainer->name ?? 'Unassigned' }}</strong></span>
                    <span>Specialization: <strong>{{ $plan->trainer->specialization ?? '—' }}</strong></span>
                    <span>Generated: <strong>{{ $plan->created_at->format('M j, Y g:ia') }}</strong></span>
                </div>

                <div class="plan-section">
                    <h4>Pre-Training</h4>
                    <ul>
                        @foreach ($plan->plan_content['pre_bullets'] ?? [] as $bullet)
                            <li>{{ $bullet }}</li>
                        @endforeach
                    </ul>

                    <h4>Post-Training</h4>
                    <ul>
                        @foreach ($plan->plan_content['post_bullets'] ?? [] as $bullet)
                            <li>{{ $bullet }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <form method="POST" action="{{ route('member.training-plan.regenerate') }}">
                @csrf
                <button type="submit" class="btn-outline"><i class="bi bi-arrow-repeat"></i> Regenerate plan</button>
            </form>
        @else
            <div class="card">
                <div class="empty-note">
                    <i class="bi bi-clipboard2-pulse"></i>
                    You don't have a training plan yet.<br>
                    Generate one based on your fitness profile and assigned trainer.
                </div>
                <form method="POST" action="{{ route('member.training-plan.generate') }}" style="text-align:center;">
                    @csrf
                    <button type="submit" class="btn-primary"><i class="bi bi-magic"></i> Generate my training plan</button>
                </form>
            </div>
        @endif
    </div>
</body>
</html>
