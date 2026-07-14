{{--
    Shared left side-navigation for logged-in members.
    Include this right after <body> on any authenticated page
    (dashboard, booking flow, membership) to get a consistent nav.
    Only renders for authenticated users — guests browsing the
    public booking flow won't see it.
--}}
@auth
@php $isMember = auth()->user()->member()->exists(); @endphp
<aside class="fu-sidenav">
    <a href="{{ $isMember ? route('member.dashboard') : route('dashboard') }}" class="fu-sidenav-brand">
        <span class="mark"><i class="bi bi-lightning-charge-fill"></i></span>
        <span class="fu-sidenav-brand-text">FIT URBAN</span>
    </a>

    <nav class="fu-sidenav-links">
        <a href="{{ $isMember ? route('member.dashboard') : route('dashboard') }}"
           class="{{ request()->routeIs('dashboard') || request()->routeIs('member.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i><span>{{ $isMember ? 'My Dashboard' : 'Profile' }}</span>
        </a>
        <a href="{{ route('booking.assessment') }}"
           class="{{ request()->routeIs('booking.assessment') || request()->is('booking/parq*', 'booking/program*', 'booking/review*', 'booking/open-gym*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check-fill"></i><span>Book a Session</span>
        </a>
        @unless($isMember)
        <a href="{{ route('booking.membership') }}"
           class="{{ request()->routeIs('booking.membership') ? 'active' : '' }}">
            <i class="bi bi-star-fill"></i><span>Become a Member</span>
        </a>
        @endunless
    </nav>

    <div class="fu-sidenav-bottom">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"><i class="bi bi-box-arrow-right"></i><span>Log out</span></button>
        </form>
    </div>
</aside>

<style>
    .fu-content { margin-left: 220px; }

    @media (max-width: 900px) {
        .fu-content { margin-left: 0; margin-bottom: 64px; }
    }

    .fu-sidenav {
        position: fixed;
        top: 0; left: 0; bottom: 0;
        width: 220px;
        background: linear-gradient(180deg, #1a1a1a 0%, #2c2c2c 100%);
        display: flex;
        flex-direction: column;
        padding: 22px 14px;
        z-index: 100;
        box-sizing: border-box;
    }

    .fu-sidenav-brand {
        display: flex;
        align-items: center;
        gap: 8px;
        font-family: 'Anton', sans-serif;
        font-size: 17px;
        color: #fff;
        text-decoration: none;
        letter-spacing: 0.4px;
        padding: 0 8px;
        margin-bottom: 32px;
    }
    .fu-sidenav-brand .mark { color: #E8732C; font-size: 16px; }

    .fu-sidenav-links {
        display: flex;
        flex-direction: column;
        gap: 4px;
        flex: 1;
    }

    .fu-sidenav-links a,
    .fu-sidenav-bottom a,
    .fu-sidenav-bottom button {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 8px;
        color: #cfc9bb;
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none;
        font-family: 'Inter', sans-serif;
        background: none;
        border: none;
        cursor: pointer;
        width: 100%;
        text-align: left;
    }

    .fu-sidenav-links a:hover,
    .fu-sidenav-bottom a:hover,
    .fu-sidenav-bottom button:hover { background: rgba(255,255,255,0.07); color: #fff; }

    .fu-sidenav-links a.active {
        background: #E8732C;
        color: #fff;
    }

    .fu-sidenav-bottom {
        display: flex;
        flex-direction: column;
        gap: 4px;
        border-top: 1px solid #3a352c;
        padding-top: 14px;
    }

    .fu-sidenav-bottom a.active { background: rgba(232,115,44,0.18); color: #fff; }

    @media (max-width: 900px) {
        .fu-sidenav {
            top: auto; bottom: 0; left: 0; right: 0;
            width: 100%; height: 64px;
            flex-direction: row;
            align-items: center;
            padding: 0 6px;
        }

        .fu-sidenav-brand { display: none; }

        .fu-sidenav-links {
            flex-direction: row;
            flex: 1;
            justify-content: space-around;
        }

        .fu-sidenav-links a,
        .fu-sidenav-bottom a,
        .fu-sidenav-bottom button {
            flex-direction: column;
            gap: 2px;
            font-size: 10px;
            padding: 6px 4px;
        }

        .fu-sidenav-bottom {
            flex-direction: row;
            border-top: none;
            border-left: 1px solid #3a352c;
            padding-top: 0;
            padding-left: 6px;
        }
    }
</style>
@endauth