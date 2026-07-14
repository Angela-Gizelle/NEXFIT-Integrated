<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NexFit') · Fit Urban</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-shell">

        {{-- Visual panel: brand + product-preview signature, unique per screen via @yield --}}
        <aside class="auth-visual">
            <div class="visual-brand">
                <div class="mark">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 2L4 14H11L10 22L20 9H13L13 2Z" fill="white"/>
                    </svg>
                </div>
                <div>
                    <div class="word">NEXFIT</div>
                    <div class="tag">Fit Urban Studio Platform</div>
                </div>
            </div>

            <div class="visual-copy">
                <span class="visual-eyebrow"><span class="dot"></span> {{ $eyebrow ?? 'Live Studio Access' }}</span>
                <h1>@yield('visual-title', 'Every session, tracked. Every member, known.')</h1>
                <p>@yield('visual-copy', 'Manage bookings, memberships, and AI-assisted training plans from one place.')</p>

                <div class="visual-pulse">
                    <svg viewBox="0 0 400 40" preserveAspectRatio="none">
                        <path d="M0 20 H120 L140 20 L155 6 L170 34 L185 20 L200 20 H400"/>
                    </svg>
                </div>
            </div>

            <div class="visual-footer">&copy; {{ date('Y') }} Fit Urban · Banaybanay I, San Jose, Batangas</div>
        </aside>

        {{-- Form panel --}}
        <main class="auth-main">
            <div class="auth-form-wrap">

                @yield('content')

            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('status'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: @json(session('status')),
                confirmButtonColor: '#FA8112',
            });
        </script>
    @endif

    @stack('scripts')
</body>
</html>