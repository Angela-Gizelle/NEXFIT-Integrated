<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fit Urban - Visit Us</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --ink:         #1a1a1a;
            --cream:       #F7F2E7;
            --orange:      #E8732C;
            --orange-dark: #C95F1F;
            --text-soft:   #8A8273;
            --border:      #E7E0CF;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            
            font-family: 'Inter', sans-serif;
            background: var(--cream);
            color: var(--ink);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

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
        .brand img { height: 36px; width: auto; object-fit: contain; }
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

        .wrap {
            max-width: 560px;
            margin: 60px auto;
            padding: 0 20px 80px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--text-soft);
            font-size: 13px;
            text-decoration: none;
            margin-bottom: 28px;
        }
        .back-link:hover { color: var(--ink); }

        .page-title {
            font-family: 'Anton', sans-serif;
            font-weight: 400;
            font-size: 32px;
            margin-bottom: 8px;
            letter-spacing: 0.3px;
        }
        .page-title .accent { color: var(--orange); }

        .page-sub {
            color: var(--text-soft);
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .info-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 28px;
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 14px 0;
            border-bottom: 1px solid var(--border);
        }
        .info-row:first-child { padding-top: 0; }
        .info-row:last-child { border-bottom: none; padding-bottom: 0; }

        .info-icon {
            width: 40px;
            height: 40px;
            background: #FBE9D6;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .info-label {
            font-size: 12px;
            font-weight: 700;
            color: var(--text-soft);
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 600;
            line-height: 1.5;
        }

        .info-value span {
            display: block;
            font-weight: 400;
            color: var(--text-soft);
            font-size: 13px;
        }

        .map-card {
            border-radius: 14px;
            overflow: hidden;
            border: 1px solid var(--border);
            margin-bottom: 20px;
        }

        .map-card iframe {
            display: block;
            width: 100%;
            height: 260px;
            border: 0;
        }

        .btn-directions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            background: var(--orange);
            color: #fff;
            border: none;
            padding: 15px;
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.15s;
        }
        .btn-directions:hover { background: var(--orange-dark); }

        @media (max-width: 480px) {
            nav { padding: 14px 20px; }
            .brand-loc { display: none; }
            .wrap { margin: 32px auto; }
            .page-title { font-size: 26px; }
            .info-card { padding: 20px; }
            .map-card iframe { height: 200px; }
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

    <div class="wrap">

        <a href="/" class="back-link">← Back to Home</a>

        <h1 class="page-title">Visit Us <span class="accent">In Person</span></h1>
        <p class="page-sub">
            Drop by the studio and our staff will personally walk you through
            our programs, help with your fitness assessment, and get you scheduled — no booking required.
        </p>

        <div class="info-card">

            <div class="info-row">
                <div class="info-icon"><i class="bi bi-building-fill" style="color:var(--orange);"></i></div>
                <div>
                    <div class="info-label">Studio Location</div>
                    <div class="info-value">
                        Fit Urban
                        <span>Barangay I, San Jose, Batangas</span>
                    </div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon"><i class="bi bi-clock-fill" style="color:var(--orange);"></i></div>
                <div>
                    <div class="info-label">Operating Hours</div>
                    <div class="info-value">
                        Monday – Sunday
                        <span>5:00 AM – 10:00 PM</span>
                    </div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-icon"><i class="bi bi-chat-dots-fill" style="color:var(--orange);"></i></div>
                <div>
                    <div class="info-label">What to Expect</div>
                    <div class="info-value">
                        Our staff will assist you
                        <span>Program selection, fitness assessment, and scheduling — all on the spot.</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="map-card">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3868.5!2d121.0437!3d13.9565!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bd174a2f3e0001%3A0x1234567890abcdef!2sFit+Urban!5e0!3m2!1sen!2sph!4v1234567890"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>

        <a href="https://maps.app.goo.gl/JMJf6Uhkmh8vbmDL7"
           target="_blank"
           class="btn-directions">
            <i class="bi bi-map-fill"></i> Get Directions
        </a>

    </div>

    </div><!-- /.fu-content -->

</body>
</html>