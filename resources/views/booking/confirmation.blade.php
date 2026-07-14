<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fit Urban - Booking Confirmed</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --ink: #1a1a1a;
            --cream: #F7F2E7;
            --orange: #E8732C;
            --orange-dark: #C95F1F;
            --text-main: #2B2B2B;
            --text-soft: #8A8273;
            --border-soft: #EDE7D8;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: var(--cream);
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
        }

        .topbar {
            background: var(--ink);
            color: #fff;
            padding: 16px 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: 'Anton', sans-serif;
            font-size: 18px;
        }
        .brand .mark { color: var(--orange); }
        .brand .loc {
            font-family: 'Inter', sans-serif;
            font-weight: 400;
            color: #9a9486;
            font-size: 12px;
            margin-left: 10px;
            border-left: 1px solid #444;
            padding-left: 10px;
        }
        .nav-links a { color: #cfc9bb; text-decoration: none; margin-left: 22px; font-size: 13px; }
        .nav-links a.cta { background: #fff; color: var(--ink); padding: 7px 14px; border-radius: 6px; font-weight: 600; }

        .wrap {
            max-width: 460px;
            margin: 90px auto;
            text-align: center;
            padding: 0 20px;
        }

        .celebrate-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #FBE9D6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin: 0 auto 22px;
        }

        h1 {
            font-family: 'Anton', sans-serif;
            font-weight: 400;
            font-size: 28px;
            margin: 0 0 12px;
            letter-spacing: 0.3px;
        }

        .sub {
            color: var(--text-soft);
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .ref-pill {
            display: inline-block;
            background: #fff;
            border: 1px solid var(--border-soft);
            border-radius: 10px;
            padding: 10px 22px;
            margin-bottom: 24px;
        }
        .ref-pill .label {
            font-size: 10.5px;
            color: var(--text-soft);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .ref-pill .value {
            font-size: 16px;
            font-weight: 800;
            color: var(--orange-dark);
        }

        .detail-card {
            background: #fff;
            border: 1px solid var(--border-soft);
            border-radius: 10px;
            padding: 18px 22px;
            text-align: left;
            margin-bottom: 26px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid var(--border-soft);
            font-size: 13px;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-row .k { color: var(--text-soft); }
        .detail-row .v { font-weight: 700; }
        .detail-row .v.accent { color: var(--orange-dark); }
        .detail-row .v.blue { color: #3B6FB0; }

        .btn-primary {
            display: inline-block;
            background: var(--orange);
            color: #fff;
            border: none;
            padding: 12px 26px;
            border-radius: 7px;
            font-size: 13.5px;
            font-weight: 700;
            text-decoration: none;
        }
    </style>
</head>
<body>

    @include('partials.side-nav')

    <div class="fu-content">

    <div class="topbar">
        <a href="/" style="display:flex;align-items:center;gap:8px;text-decoration:none;">
            <img src="/images/fit_urban_logo_white.png" alt="Fit Urban Logo" style="height:30px;width:auto;object-fit:contain;">
        </a>
        @guest
        <div class="nav-links">
            <a href="#">Programs</a>
            <a href="/booking/membership" class="cta">Book a Session</a>
        </div>
        @endguest
    </div>
    <div class="wrap">
        <div class="celebrate-icon"><i class="bi bi-check2-circle" style="color:var(--orange);font-size:28px;"></i></div>

        <h1>Booking Confirmed!</h1>
        <p class="sub">
            Your session has been reserved at Fit Urban. A staff member will contact you within
            24 hours to complete your enrollment.
        </p>

        <div class="ref-pill">
            <div class="label">Reference No.</div>
            <div class="value">#BK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</div>
        </div>

        <div class="detail-card">
            <div class="detail-row">
                <span class="k">Name</span>
                <span class="v">{{ $booking->assessment->full_name }}</span>
            </div>
            <div class="detail-row">
                <span class="k">Program</span>
                <span class="v accent">{{ $booking->service->name }}</span>
            </div>
            <div class="detail-row">
                <span class="k">Date</span>
                <span class="v blue">{{ \Carbon\Carbon::parse($booking->booking_date)->format('F j, Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="k">Time Slot</span>
                <span class="v blue">{{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}</span>
            </div>
            @if($booking->trainer)
                <div class="detail-row">
                    <span class="k">Trainer</span>
                    <span class="v">{{ $booking->trainer->name }}</span>
                </div>
            @endif
        </div>

        <a href="/booking/assessment" class="btn-primary">+ Book Another Session</a>
    </div>

    </div><!-- /.fu-content -->

</body>
</html>