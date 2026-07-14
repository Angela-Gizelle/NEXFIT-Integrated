<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Fit Urban - Membership Signup</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --ink: #1a1a1a; --ink-soft: #2c2c2c; --cream: #F7F2E7; --cream-card: #FFFFFF;
            --orange: #E8732C; --orange-dark: #C95F1F; --green: #1FA86A; --green-bg: #EAF8F1;
            --text-main: #2B2B2B; --text-soft: #8A8273; --border: #E7E0CF; --border-soft: #EDE7D8;
            --radius: 10px;
        }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--cream); font-family: 'Inter', sans-serif; color: var(--text-main); }
        a { text-decoration: none; }

        .topbar {
            background: linear-gradient(180deg, var(--ink) 0%, var(--ink-soft) 100%);
            color: #fff; padding: 34px 48px 60px; position: relative; overflow: hidden;
        }
        .topbar::after {
            content: ''; position: absolute; top: -40%; right: -10%; width: 480px; height: 480px;
            background: radial-gradient(circle, rgba(232,115,44,0.25) 0%, transparent 70%); pointer-events: none;
        }
        .hero { position: relative; z-index: 1; }
        .hero .eyebrow { display: inline-block; border: 1px solid #5a5346; color: #d8d2c2; font-size: 11px; padding: 4px 12px; border-radius: 999px; margin-bottom: 14px; }
        .hero h1 { font-family: 'Anton', sans-serif; font-weight: 400; font-size: 32px; margin: 0; color: #fff; }
        .hero h1 .accent { color: var(--orange); }
        .hero p { color: #a39c8c; font-size: 14px; margin: 10px 0 0; max-width: 520px; line-height: 1.5; }

        .page-body { max-width: 760px; margin: -30px auto 0; padding: 0 24px 80px; position: relative; z-index: 2; }

        .card { background: var(--cream-card); border: 1px solid var(--border-soft); border-radius: var(--radius); padding: 28px 30px; margin-bottom: 20px; }
        .card h3 { font-size: 15px; font-weight: 700; margin: 0 0 16px; }

        .plan-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .plan-option { position: relative; }
        .plan-option input { position: absolute; opacity: 0; }
        .plan-option label {
            display: block; border: 2px solid var(--border); border-radius: 8px; padding: 14px 16px; cursor: pointer;
        }
        .plan-option input:checked + label { border-color: var(--orange); background: #FBE9D6; }
        .plan-name { font-weight: 700; font-size: 13.5px; }
        .plan-price { font-family: 'Anton', sans-serif; color: var(--orange-dark); font-size: 18px; margin: 4px 0 2px; }
        .plan-meta { font-size: 11.5px; color: var(--text-soft); }

        .field { margin-bottom: 16px; }
        .field label { display: block; font-size: 12px; font-weight: 600; color: var(--text-soft); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.3px; }
        .field input, .field select {
            width: 100%; border: 1px solid var(--border); border-radius: 7px; padding: 10px 12px; font-size: 13.5px; font-family: inherit;
        }
        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

        .btn-primary {
            background: var(--orange); color: #fff; border: none; padding: 12px 26px; border-radius: 7px;
            font-size: 14px; font-weight: 700; cursor: pointer; width: 100%;
        }
        .error-list { background: #FCE9E7; color: #D1453B; border-radius: 8px; padding: 12px 16px; font-size: 12.5px; margin-bottom: 18px; }
        .back-link { display: inline-block; margin-bottom: 16px; font-size: 12.5px; color: var(--text-soft); }

        @media (max-width: 600px) {
            .plan-grid, .field-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    @include('partials.side-nav')

    <div class="fu-content">
        <div class="topbar">
            <div class="hero">
                <span class="eyebrow">MEMBERSHIP SIGNUP</span>
                <h1>Join <span class="accent">Fit Urban</span></h1>
                <p>Pick a plan below to activate your membership. You'll be able to take your PAR-Q health screening and view your sessions from your new member dashboard right after.</p>
            </div>
        </div>

        <div class="page-body">

            @if($errors->any())
                <div class="error-list">
                    <ul style="margin:0; padding-left:18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('member.join.store') }}">
                @csrf

                <div class="card">
                    <h3>Choose your plan</h3>
                    <div class="plan-grid">
                        @foreach($plans as $type => $plan)
                            <div class="plan-option">
                                <input type="radio" name="package_type" id="plan_{{ \Illuminate\Support\Str::slug($type) }}" value="{{ $type }}" {{ old('package_type', 'Monthly') === $type ? 'checked' : '' }}>
                                <label for="plan_{{ \Illuminate\Support\Str::slug($type) }}">
                                    <div class="plan-name">{{ $type }}</div>
                                    <div class="plan-price">₱{{ number_format($plan['price']) }}</div>
                                    <div class="plan-meta">{{ $plan['credits'] }} sessions &middot; {{ $plan['days'] }} days</div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card">
                    <h3>Your details</h3>
                    <div class="field-row">
                        <div class="field">
                            <label>Phone number</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="09XX XXX XXXX">
                        </div>
                        <div class="field">
                            <label>Birthdate</label>
                            <input type="date" name="birthdate" value="{{ old('birthdate') }}">
                        </div>
                    </div>
                    <div class="field">
                        <label>Address</label>
                        <input type="text" name="address" value="{{ old('address') }}" placeholder="Street, Barangay, City">
                    </div>
                    <div class="field-row">
                        <div class="field">
                            <label>Emergency contact name</label>
                            <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}">
                        </div>
                        <div class="field">
                            <label>Emergency contact phone</label>
                            <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}">
                        </div>
                    </div>
                    <div class="field">
                        <label>Payment mode</label>
                        <select name="payment_mode">
                            <option value="Cash">Cash</option>
                            <option value="GCash">GCash</option>
                            <option value="Card">Card</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-primary">Activate Membership →</button>
            </form>
        </div>
    </div>

</body>
</html>