<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NexFit') &ndash; Fit Urban Staff Portal</title>

    {{-- Apply theme before paint to prevent flash --}}
    <script>
        (function() {
            var t = localStorage.getItem('nexfit-theme');
            if (t === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    {{-- DataTables (Bootstrap 5 build) --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <style>
        :root {
            --bg:           #FAF3E1;
            --bg-elevated:  #FFFFFF;
            --surface:      #FFFFFF;
            --surface-2:    #FAF3E1;
            --surface-3:    #F5E7C6;
            --border:       #EADFC0;
            --border-soft:  #DCCB9F;
            --text:         #222222;
            --text-strong:  #111111;
            --text-muted:   #6b6357;
            --text-faint:   #8a8275;
            --orange:       #FA8112;
            --accent:       #FA8112;
            --accent-2:     #FF9A47;
            --accent-hover: #E8650A;
            --accent-soft:  rgba(250, 129, 18, 0.14);
            --accent-ring:  rgba(250, 129, 18, 0.25);
            --success:      #16a34a;
            --success-bg:   rgba(22, 163, 74, 0.12);
            --danger:       #dc2626;
            --danger-bg:    rgba(220, 38, 38, 0.10);
            --info:         #2563eb;
            --info-bg:      rgba(37, 99, 235, 0.10);
            --warn:         #d97706;
            --warn-bg:      rgba(217, 119, 6, 0.12);
            --neutral:      #5b5347;
            --neutral-bg:   rgba(91, 83, 71, 0.16);
            --sidebar-w:    248px;
            --topbar-h:     55px;
            --radius:       14px;
            --radius-md:    10px;
            --radius-sm:    8px;
            --shadow-1:     0 1px 2px rgba(34,34,34,.04), 0 1px 0 rgba(255,255,255,.6) inset;
            --shadow-2:     0 10px 28px -16px rgba(34,34,34,.18);
        }

        /* =====================  DARK MODE  ===================== */
        html[data-theme="dark"] {
            --bg:           #111111;
            --bg-elevated:  #1a1a1a;
            --surface:      #1a1a1a;
            --surface-2:    #222222;
            --surface-3:    #2a2a2a;
            --border:       #2e2e2e;
            --border-soft:  #383838;
            --text:         #e8e8e8;
            --text-strong:  #f5f5f5;
            --text-muted:   #9a9a9a;
            --text-faint:   #666666;
            --accent-soft:  rgba(250, 129, 18, 0.18);
            --accent-ring:  rgba(250, 129, 18, 0.3);
            --success-bg:   rgba(22, 163, 74, 0.18);
            --danger-bg:    rgba(220, 38, 38, 0.18);
            --info-bg:      rgba(37, 99, 235, 0.18);
            --warn-bg:      rgba(217, 119, 6, 0.20);
            --neutral:      #b7ada0;
            --neutral-bg:   rgba(183, 173, 160, 0.18);
            --shadow-1:     0 1px 2px rgba(0,0,0,.3), 0 1px 0 rgba(255,255,255,.03) inset;
            --shadow-2:     0 10px 28px -16px rgba(0,0,0,.6);
        }
        html[data-theme="dark"] body {
            background:
                radial-gradient(1200px 600px at 80% -10%, rgba(250,129,18,.07), transparent 60%),
                radial-gradient(900px 500px at -10% 110%, rgba(30,20,10,.5), transparent 60%),
                var(--bg);
        }
        html[data-theme="dark"] .sidebar { background: #161616; }
        html[data-theme="dark"] .topbar { background: rgba(17,17,17,.9); }
        html[data-theme="dark"] .sidebar-nav .nav-link:hover { background: rgba(255,255,255,.06); }
        html[data-theme="dark"] .profile-header {
            background: linear-gradient(135deg, rgba(250,129,18,.10), rgba(96,165,250,.04) 60%, transparent), var(--surface);
        }
        html[data-theme="dark"] .form-select option { background: #222222; }

        /* Smooth theme transition */
        body, .sidebar, .topbar, .card, .metric-card,
        .form-control, .form-select, .btn, .badge, .profile-header {
            transition: background-color .25s ease, border-color .25s ease, color .2s ease;
        }

        * { box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            background:
                radial-gradient(1200px 600px at 80% -10%, rgba(250,129,18,.10), transparent 60%),
                radial-gradient(900px 500px at -10% 110%, rgba(245,231,198,.6), transparent 60%),
                var(--bg);
            color: var(--text);
            font-family: 'Inter', -apple-system, 'Segoe UI', system-ui, sans-serif;
            font-size: 0.9rem;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
        }

        a { color: var(--accent); text-decoration: none; transition: color .15s ease; }
        a:hover { color: var(--accent-2); }

        /* =====================  SIDEBAR  ===================== */
        .sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: #FAF7F2;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 1040;
            transition: transform .25s ease;
        }
        /* Brand block */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: .7rem;
            padding: 1.1rem 1.1rem .9rem;

            border-bottom: 1px solid var(--border);
            margin-bottom: .75rem;
        }
        .sidebar-brand .logo-mark {
            width: 40px; height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            display: grid; place-items: center;
            font-weight: 800; color: #fff; font-size: 1rem;
            box-shadow: 0 6px 16px -6px rgba(255,122,26,.6);
            flex-shrink: 0;
        }
        .sidebar-brand .brand-info { line-height: 1.15; }
        .sidebar-brand .brand-name { font-weight: 800; font-size: 1.05rem; letter-spacing: -.02em; color: var(--text-strong); }
        .sidebar-brand .brand-name span { color: var(--accent); }
        .sidebar-brand .brand-sub { font-size: .7rem; color: var(--text-faint); letter-spacing: .01em; }

        /* Section labels */
        .nav-section-label {
            padding: .85rem 1.1rem .3rem;
            font-size: .63rem;
            font-weight: 700;
            letter-spacing: .13em;
            color: var(--text-faint);
            text-transform: uppercase;
        }

        /* Nav links */
        .sidebar-nav { padding: 0 .6rem; overflow-y: auto; flex: 1; }
        .sidebar-nav .nav-link {
            position: relative;
            display: flex; align-items: center; gap: .65rem;
            padding: .52rem .8rem;
            border-radius: var(--radius-sm);
            color: var(--text-muted);
            font-size: .855rem;
            font-weight: 500;
            margin-bottom: 1px;
            transition: background .15s ease, color .15s ease;
        }
        .sidebar-nav .nav-link i { font-size: .95rem; width: 18px; text-align: center; flex-shrink: 0; }
        .sidebar-nav .nav-link:hover { background: rgba(255,255,255,.55); color: var(--text); }
        .sidebar-nav .nav-link.active {
            background: var(--accent-soft);
            color: var(--accent);
            font-weight: 600;
        }
        .sidebar-nav .nav-link.active i { color: var(--accent); }
        .sidebar-nav .nav-link .nav-badge {
            margin-left: auto;
            font-size: .6rem; font-weight: 700;
            padding: .15em .45em;
            border-radius: 4px;
            background: var(--accent);
            color: #fff;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        /* Footer user block */
        .sidebar-footer {
            padding: .85rem 1.1rem;
            border-top: 1px solid var(--border);
            display: flex; align-items: center; gap: .65rem;
        }
        .sidebar-footer .user-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            display: grid; place-items: center;
            font-size: .75rem; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }
        .sidebar-footer .user-info { min-width: 0; }
        .sidebar-footer .user-name { font-size: .82rem; font-weight: 600; color: var(--text-strong); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sidebar-footer .user-role { font-size: .7rem; color: var(--text-faint); }

        /* Mobile sidebar toggle */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 12px; left: 12px;
            z-index: 1050;
            background: var(--surface);
            border: 1px solid var(--border-soft);
            color: var(--text);
            width: 40px; height: 40px;
            border-radius: var(--radius-sm);
            align-items: center; justify-content: center;
        }
        .sidebar-backdrop {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.55);
            backdrop-filter: blur(2px);
            z-index: 1035;
        }

        /* =====================  MAIN  ===================== */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex; flex-direction: column;
        }
        .topbar {
            display: flex; align-items: center; justify-content: space-between;
            gap: 1rem;
            padding: 0 1.75rem;
            min-height: var(--topbar-h);
            border-bottom: 1px solid var(--border);
            background: rgba(250,243,225,.85);
            backdrop-filter: saturate(140%) blur(10px);
            -webkit-backdrop-filter: saturate(140%) blur(10px);
            position: sticky; top: 0; z-index: 1020;
        }
        /* Left: breadcrumb */
        .topbar-left { display: flex; align-items: center; gap: .4rem; min-width: 0; font-size: .8rem; color: var(--text-faint); }
        .topbar-left .bc-sep { opacity: .5; font-size: .7rem; }
        .topbar-left .bc-current { font-weight: 900; color: var(--text-strong); font-size: .85rem; }
        .topbar-left a { color: var(--text-faint); font-weight: 500; }
        .topbar-left a:hover { color: var(--accent); }
        /* Right: actions + controls */
        .topbar-right { display: flex; align-items: center; gap: .5rem; flex-shrink: 0; }
        .topbar-actions { display: flex; align-items: center; gap: .5rem; flex-wrap: wrap; }
        /* Light mode toggle pill */
        .topbar-mode-btn {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .38rem .75rem;
            border-radius: 999px;
            border: 1px solid var(--border-soft);
            background: var(--surface-2);
            color: var(--text-muted);
            font-size: .78rem; font-weight: 500;
            cursor: pointer;
            transition: background .15s, color .15s;
        }
        .topbar-mode-btn:hover { background: var(--surface-3); color: var(--text); }
        .topbar-mode-btn i { font-size: .85rem; }
        /* Bell */
        .topbar-bell {
            width: 34px; height: 34px;
            border-radius: 50%;
            border: 1px solid var(--border-soft);
            background: var(--surface-2);
            display: grid; place-items: center;
            color: var(--text-muted);
            cursor: pointer;
            transition: background .15s, color .15s;
            font-size: .9rem;
            flex-shrink: 0;
        }
        .topbar-bell:hover { background: var(--surface-3); color: var(--text); }

        /* Page heading area (below topbar, inside content-area) */
        .page-heading { margin-bottom: 1.25rem; }
        .page-title { font-size: 1.4rem; font-weight: 800; letter-spacing: -.02em; color: var(--text-strong); margin: 0; line-height: 1.2; }
        .page-subtitle { color: var(--text-muted); font-size: .82rem; margin-top: .2rem; display: flex; align-items: center; gap: .35rem; flex-wrap: wrap; }
        .page-subtitle a { color: var(--text-muted); }
        .page-subtitle a:hover { color: var(--accent-2); }
        .content-area { padding: 1.5rem 1.75rem 3rem; flex: 1; max-width: 1480px; width: 100%; }

        /* =====================  CARDS  ===================== */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow-1);
        }
        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border);
            padding: .9rem 1.25rem;
            display: flex; align-items: center; justify-content: space-between; gap: .75rem;
            flex-wrap: wrap;
        }
        .card-header .card-title {
            margin: 0;
            font-size: .92rem; font-weight: 700; color: var(--text-strong);
            display: flex; align-items: center; gap: .5rem;
        }
        .card-header .card-title i { color: var(--accent); font-size: 1.05rem; }
        .card-body { padding: 1.25rem; }
        .card-body.p-0 { padding: 0; }

        /* =====================  BUTTONS  ===================== */
        .btn {
            font-size: .84rem; font-weight: 600;
            border-radius: var(--radius-sm);
            padding: .45rem .9rem;
            line-height: 1.4;
            transition: background .15s ease, border-color .15s ease, color .15s ease, transform .05s ease;
            display: inline-flex; align-items: center; gap: .35rem;
        }
        .btn:active { transform: translateY(1px); }
        .btn-sm { padding: .3rem .65rem; font-size: .78rem; }
        .btn-orange, .btn-primary {
            background: linear-gradient(180deg, var(--accent), var(--accent-hover));
            border: 1px solid var(--accent-hover);
            color: #fff;
            box-shadow: 0 6px 14px -8px rgba(255,122,26,.7);
        }
        .btn-orange:hover, .btn-primary:hover {
            background: linear-gradient(180deg, var(--accent-2), var(--accent));
            border-color: var(--accent); color: #fff;
        }
        .btn-outline-secondary {
            border: 1px solid var(--border-soft);
            color: var(--text);
            background: var(--surface-2);
        }
        .btn-outline-secondary:hover { background: var(--surface-3); color: var(--text-strong); border-color: var(--border-soft); }
        .btn-outline-danger {
            border: 1px solid rgba(248,113,113,.4);
            color: var(--danger);
            background: transparent;
        }
        .btn-outline-danger:hover { background: var(--danger-bg); color: var(--danger); border-color: var(--danger); }
        .btn-ghost {
            background: transparent; border: 1px solid transparent;
            color: var(--text-muted);
        }
        .btn-ghost:hover { background: var(--surface-2); color: var(--text); }

        /* =====================  FORM  ===================== */
        .form-control, .form-select {
            background: var(--surface-2);
            border: 1px solid var(--border-soft);
            color: var(--text);
            font-size: .88rem;
            padding: .5rem .75rem;
            border-radius: var(--radius-sm);
            transition: border-color .15s ease, box-shadow .15s ease, background .15s ease;
        }
        .form-control-sm, .form-select-sm { font-size: .8rem; padding: .35rem .6rem; }
        .form-control:disabled, .form-control[readonly] {
            background: var(--surface-3) !important;
            color: var(--text-muted) !important;
            opacity: 1 !important;
        }
        .form-control::placeholder { color: var(--text-faint); }
        .form-control:focus, .form-select:focus {
            background: var(--surface-2);
            border-color: var(--accent);
            color: var(--text);
            box-shadow: 0 0 0 3px var(--accent-ring);
            outline: none;
        }
        .form-select { background-image: linear-gradient(45deg, transparent 50%, var(--text-muted) 50%), linear-gradient(135deg, var(--text-muted) 50%, transparent 50%); background-position: calc(100% - 18px) 50%, calc(100% - 13px) 50%; background-size: 5px 5px, 5px 5px; background-repeat: no-repeat; padding-right: 2rem; }
        .form-select option { background: var(--surface-2); color: var(--text); }
        label, .form-label {
            color: var(--text-muted); font-size: .76rem; font-weight: 600;
            margin-bottom: .35rem; letter-spacing: .01em; text-transform: uppercase;
        }
        .form-text { color: var(--text-faint); font-size: .76rem; }
        .invalid-feedback { display: block; color: var(--danger); font-size: .76rem; margin-top: .25rem; }
        .is-invalid { border-color: var(--danger) !important; box-shadow: 0 0 0 3px rgba(248,113,113,.18) !important; }
        hr { border-color: var(--border); opacity: 1; margin: 1rem 0; }

        /* =====================  BADGES  ===================== */
        .badge {
            font-weight: 600; font-size: .7rem;
            padding: .35em .65em;
            border-radius: 999px;
            display: inline-flex; align-items: center; gap: .3rem;
            letter-spacing: .01em;
        }
        .badge::before {
            content: ""; width: 6px; height: 6px; border-radius: 50%;
            background: currentColor; opacity: .85;
        }
        .badge.no-dot::before { display: none; }
        .badge-active   { background: var(--success-bg); color: var(--success); }
        .badge-inactive { background: var(--neutral-bg); color: var(--neutral); }
        .badge-churned  { background: var(--danger-bg); color: var(--danger); }
        .badge-expired  { background: var(--warn-bg);   color: var(--warn); }
        .badge-special  { background: var(--warn-bg); color: var(--warn); }
        .badge-info     { background: var(--info-bg); color: var(--info); }

        /* =====================  METRIC CARDS  ===================== */
        .metric-card {
            position: relative;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1rem 1.15rem;
            cursor: pointer;
            transition: border-color .15s ease, transform .1s ease, background .15s ease;
            overflow: hidden;
        }
        .metric-card::after {
            content: ""; position: absolute; inset: 0;
            background: radial-gradient(120% 60% at 100% 0%, rgba(255,255,255,.03), transparent 60%);
            pointer-events: none;
        }
        .metric-card:hover { border-color: var(--border-soft); transform: translateY(-1px); }
        .metric-card.is-active { border-color: var(--accent); box-shadow: 0 0 0 1px var(--accent), 0 10px 30px -18px rgba(255,122,26,.6); }
        .metric-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: .45rem; }
        .metric-label { color: var(--text-muted); font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; display: flex; align-items: center; }
        .metric-value { font-size: 1.85rem; font-weight: 800; line-height: 1.2; margin-top: .4rem; font-feature-settings: "tnum"; }
        .metric-trend { font-size: .72rem; color: var(--text-faint); margin-top: .15rem; }

        /* =====================  ALERTS  ===================== */
        .alert {
            border-radius: var(--radius);
            border: 1px solid transparent;
            padding: .85rem 1rem;
            display: flex; align-items: flex-start; gap: .55rem;
            font-size: .86rem;
        }
        .alert i { font-size: 1rem; margin-top: 1px; }
        .alert-expiry  { background: var(--warn-bg); border-color: rgba(251,191,36,.3); color: var(--warn); }
        .alert-danger  { background: var(--danger-bg); border-color: rgba(248,113,113,.3); color: var(--danger); }

        /* =====================  SECTION & DETAIL HELPERS  ===================== */
        .section-label {
            color: var(--text-strong);
            font-size: .8rem;
            font-weight: 700;
            letter-spacing: .02em;
            margin-bottom: 1rem;
            padding-bottom: .5rem;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: .5rem;
        }
        .section-label::before {
            content: ""; width: 3px; height: 14px; border-radius: 2px; background: var(--accent);
        }
        .info-label { color: var(--text-faint); font-size: .7rem; text-transform: uppercase; letter-spacing: .06em; margin-bottom: .15rem; font-weight: 600; }
        .info-value { color: var(--text); font-size: .9rem; font-weight: 500; word-break: break-word; }
        .avatar {
            width: 56px; height: 56px; border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent-2));
            color: #fff; display: grid; place-items: center;
            font-size: 1.2rem; font-weight: 700;
            box-shadow: 0 8px 20px -10px rgba(255,122,26,.5);
            flex-shrink: 0;
        }
        .avatar-sm { width: 36px; height: 36px; font-size: .8rem; box-shadow: none; }

        /* Profile header (show page) */
        .profile-header {
            background: linear-gradient(135deg, rgba(255,122,26,.12), rgba(96,165,250,.05) 60%, transparent),
                        var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem 1.4rem;
            display: flex; align-items: center; gap: 1rem;
            flex-wrap: wrap;
        }
        .profile-header .meta-pills { display: flex; flex-wrap: wrap; gap: .4rem; margin-top: .35rem; }

        /* =====================  TABLES  ===================== */
        .table {
            background-color: transparent !important;
            color: var(--text);
            margin-bottom: 0;
        }
        .table > :not(caption) > * > * {
            background-color: transparent !important;
            color: var(--text);
            box-shadow: none;
        }
        .table thead th {
            background-color: transparent !important;
            color: var(--text-faint) !important;
            font-size: .68rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-weight: 700;
            border-bottom: 1px solid var(--border) !important;
            padding: .75rem .75rem;
        }
        .table td, .table th { border-color: var(--border) !important; vertical-align: middle; }
        .table-borderless td, .table-borderless th {
            border: none !important;
            border-bottom: 1px solid var(--border) !important;
            padding: .75rem .75rem;
        }
        .table-borderless tbody tr:last-child td,
        .table-borderless tbody tr:last-child th { border-bottom: none !important; }
        .table-hover > tbody > tr:hover > * { background-color: var(--surface-2) !important; color: var(--text) !important; }

        /* DataTables theming */
        .dataTables_wrapper { background: transparent; color: var(--text); padding-top: .25rem; }
        table.dataTable,
        table.dataTable tbody,
        table.dataTable thead,
        div.dataTables_scrollBody table.dataTable {
            background-color: transparent !important;
            color: var(--text);
            border-collapse: separate !important;
        }
        table.dataTable thead th {
            background-color: transparent !important;
            color: var(--text-faint) !important;
            font-size: .68rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-weight: 700;
            border-bottom: 1px solid var(--border) !important;
            padding: .8rem .75rem;
        }
        table.dataTable tbody td {
            background-color: transparent !important;
            border-bottom: 1px solid var(--border) !important;
            padding: .75rem .75rem;
            vertical-align: middle;
            color: var(--text);
            font-size: .87rem;
        }
        table.dataTable tbody tr { background-color: transparent !important; transition: background .12s ease; }
        table.dataTable tbody tr:hover,
        table.dataTable.hover tbody tr:hover td { background-color: var(--surface-2) !important; }
        table.dataTable tbody tr:last-child td { border-bottom: none !important; }
        table.dataTable.dtr-inline.collapsed > tbody > tr > td.dtr-control:before {
            background-color: var(--accent) !important;
            box-shadow: none !important;
        }
        table.dataTable td.child { background: var(--surface-2) !important; color: var(--text) !important; }
        table.dataTable td.child ul.dtr-details { color: var(--text); }
        table.dataTable td.child span.dtr-title { color: var(--text-faint) !important; }
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            background: var(--surface-2);
            border: 1px solid var(--border-soft);
            color: var(--text);
            border-radius: var(--radius-sm);
            padding: .35rem .65rem;
            font-size: .82rem;
        }
        .dataTables_wrapper .dataTables_filter input { min-width: 220px; }
        .dataTables_wrapper .dataTables_filter input:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px var(--accent-ring); }
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate { color: var(--text-muted) !important; font-size: .82rem; padding: .5rem .9rem; }
        .dataTables_wrapper .dataTables_filter label,
        .dataTables_wrapper .dataTables_length label { color: var(--text-muted); }
        .dataTables_wrapper .dataTables_paginate {
            display: flex !important;
            align-items: center;
            flex-wrap: wrap;
        }
        .dataTables_wrapper .dataTables_paginate .pagination {
            gap: .3rem;
            margin: 0;
        }
        .dataTables_wrapper .dataTables_paginate .page-link {
            color: var(--text-muted) !important;
            border-radius: 999px !important;
            min-width: 30px;
            height: 30px;
            padding: 0 .6rem !important;
            margin: 0 !important;
            border: 1px solid transparent !important;
            background: var(--bg) !important;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: .8rem;
            line-height: 1;
            box-shadow: none !important;
            transition: background .15s ease, color .15s ease, border-color .15s ease, box-shadow .15s ease;
        }
        .dataTables_wrapper .dataTables_paginate .page-item.first .page-link,
        .dataTables_wrapper .dataTables_paginate .page-item.previous .page-link,
        .dataTables_wrapper .dataTables_paginate .page-item.next .page-link,
        .dataTables_wrapper .dataTables_paginate .page-item.last .page-link {
            background: var(--surface) !important;
            border-color: var(--border-soft) !important;
            font-weight: 700;
        }
        .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
            background: linear-gradient(180deg, var(--accent), var(--accent-hover)) !important;
            color: #fff !important;
            border-color: var(--accent-hover) !important;
            box-shadow: 0 6px 14px -8px rgba(255,122,26,.7) !important;
        }
        .dataTables_wrapper .dataTables_paginate .page-item:not(.active):not(.disabled) .page-link:hover {
            background: var(--surface-3) !important;
            color: var(--text-strong) !important;
            border-color: var(--border-soft) !important;
        }
        .dataTables_wrapper .dataTables_paginate .page-item.disabled .page-link {
            color: var(--text-faint) !important;
            background: transparent !important;
            border-color: transparent !important;
            box-shadow: none !important;
            cursor: default;
            opacity: .6;
        }
        .dataTables_wrapper .dataTables_paginate .page-link:focus {
            box-shadow: 0 0 0 3px var(--accent-ring) !important;
        }
        table.dataTable.no-footer { border-bottom: none !important; }
        .dataTables_empty { color: var(--text-muted) !important; padding: 2rem !important; }


        /* SweetAlert2 */
        .swal2-popup.nexfit-swal {
            background: var(--surface) !important;
            color: var(--text) !important;
            border: 1px solid var(--border);
            border-radius: var(--radius);
        }
        .swal2-popup.nexfit-swal .swal2-html-container { color: var(--text-muted) !important; }
        .swal2-popup.nexfit-swal .swal2-title { color: var(--text-strong) !important; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border-soft); border-radius: 8px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--accent); }

        /* =====================  RESPONSIVE  ===================== */

        /* Tablet / small laptop */
        @media (max-width: 1199px) {
            .content-area { padding: 1.25rem 1.25rem 2.5rem; }
        }

        /* Tablet portrait — sidebar still visible but tighten spacing */
        @media (max-width: 1024px) {
            :root { --sidebar-w: 220px; }
            .content-area { padding: 1.1rem 1.1rem 2.25rem; }
            .topbar { padding: 0 1.1rem; }
            .metric-value { font-size: 1.65rem; }
        }

        /* Collapse sidebar into off-canvas drawer */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.is-open { transform: translateX(0); box-shadow: 0 0 60px rgba(0,0,0,.6); }
            .sidebar-toggle { display: inline-flex; }
            .sidebar-backdrop.is-open { display: block; }
            .main-content { margin-left: 0 !important; }
            .topbar { padding-left: 4rem; }
            .topbar-mode-btn span { display: none; }

            .page-heading {
                flex-direction: column;
                align-items: stretch !important;
            }
            .page-heading .d-flex.align-items-center.gap-2 {
                width: 100%;
                flex-wrap: wrap;
            }

            /* Allow tables to scroll instead of squashing the layout */
            .table-responsive-wrap,
            .card-body.p-0 .table-responsive,
            .dataTables_wrapper {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }

        /* Phones (landscape and large) */
        @media (max-width: 767px) {
            .content-area { padding: 1rem 1rem 2rem; }
            .topbar { padding: .6rem 1rem .6rem 4rem; min-height: auto; flex-wrap: wrap; row-gap: .4rem; }
            .topbar-left { font-size: .75rem; }
            .topbar-left .bc-current { font-size: .8rem; }
            .topbar-right { gap: .35rem; }

            .page-title { font-size: 1.2rem; }
            .page-subtitle { font-size: .78rem; }

            .card-header {
                flex-direction: column;
                align-items: flex-start !important;
                gap: .5rem;
            }
            .card-header .d-flex { flex-wrap: wrap; }

            .profile-header {
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
                padding: 1rem;
            }

            .metric-card { padding: .85rem .95rem; }
            .metric-value { font-size: 1.4rem; }
        }

            /* Small phones */
            @media (max-width: 575px) {
                .content-area { padding: .85rem .85rem 1.75rem; }
                .topbar { padding: .5rem .75rem .5rem 3.75rem; }
                .topbar-actions .btn { padding: .4rem .7rem; font-size: .78rem; }
                .topbar-mode-btn { display: none; }

                .card-body { padding: .9rem; }
                .card-header { padding: .75rem .9rem; }

                .metric-value { font-size: 1.3rem; }
                .avatar { width: 44px; height: 44px; font-size: 1rem; }

                /* DataTables controls stack on tiny screens */
                .dataTables_wrapper .dataTables_length,
                .dataTables_wrapper .dataTables_filter {
                    width: 100%;
                    text-align: left !important;
                }
                .dataTables_wrapper .dataTables_filter input { width: 100%; min-width: 0; }
                .dataTables_wrapper .dataTables_paginate { justify-content: center !important; }

                .sidebar-toggle { top: 8px; left: 8px; width: 36px; height: 36px; }
            }
    </style>

    @stack('styles')
</head>
<body>

<button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle navigation">
    <i class="bi bi-list fs-4"></i>
</button>
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<aside class="sidebar" id="sidebar">
    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="logo-mark">
            <i class="bi bi-lightning-fill" style="font-size:1.1rem;"></i>
        </div>
        <div class="brand-info">
            <div class="brand-name">NEXFIT</div>
            <div class="brand-sub">Fit Urban &middot; Staff Portal</div>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>
        <a href="#" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
            <i class="bi bi-house-door"></i> Home
        </a>
        <a href="#" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="nav-section-label">My Work</div>
        <a href="#" class="nav-link {{ request()->routeIs('scheduling*') ? 'active' : '' }}">
            <i class="bi bi-calendar-check"></i> Scheduling
        </a>
        <a href="#" class="nav-link {{ request()->routeIs('session-management.*') ? 'active' : '' }}">
            <i class="bi bi-calendar2-week"></i>
            Session Management
        </a>
        <a href="#" class="nav-link {{ request()->routeIs('session-credit-inventory.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                Session Credit Inventory
            </a>
        <a href="{{ route('members.index') }}" class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Members
        </a>
        <a href="#" class="nav-link {{ request()->routeIs('trainers*') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i> Trainers
        </a>
        <a href="#" class="nav-link {{ request()->routeIs('ai-plans*') ? 'active' : '' }}">
            <i class="bi bi-stars"></i> AI Plans
            <span class="nav-badge">AI</span>
        </a>

        <div class="nav-section-label">Reports</div>
        <a href="#" class="nav-link {{ request()->routeIs('reports*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> Reports
        </a>

        <div class="nav-section-label">Support</div>
        <a href="#" class="nav-link {{ request()->routeIs('settings*') ? 'active' : '' }}">
            <i class="bi bi-gear"></i> Settings
        </a>
        <a href="#" class="nav-link">
            <i class="bi bi-question-circle"></i> Help
        </a>
    </nav>

    {{-- User footer --}}
    <div class="sidebar-footer">
        <div class="user-avatar">JM</div>
        <div class="user-info">
            <div class="user-name">Jesa Mojares</div>
            <div class="user-role">Administrator</div>
        </div>
    </div>
</aside>

<div class="main-content">
    <div class="topbar">
        {{-- Left: breadcrumb --}}
        <div class="topbar-left">
            <a href="#">ACCOUNT</a>
            <span class="bc-sep"><i class="bi bi-chevron-right"></i></span>
            <span class="bc-current">@yield('page-title', 'Members') &mdash; NexFit</span>
        </div>

        {{-- Right: mode toggle + bell --}}
        <div class="topbar-right">
            <button class="topbar-mode-btn" id="themeModeBtn" type="button" title="Toggle display mode">
                <i class="bi bi-moon" id="themeModeIcon"></i>
                <span id="themeModeLabel">Dark Mode</span>
            </button>
            <div class="topbar-bell" title="Notifications">
                <i class="bi bi-bell"></i>
            </div>
        </div>
    </div>

    <div class="content-area">
        @hasSection('page-title')
        <div class="page-heading d-flex align-items-start justify-content-between gap-3 flex-wrap mb-4">
            <div>
                <h1 class="page-title">@yield('page-title')</h1>
                @hasSection('page-subtitle')
                <div class="page-subtitle">@yield('page-subtitle')</div>
                @endif
            </div>
            @hasSection('topbar-actions')
            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                @yield('topbar-actions')
            </div>
            @endif
        </div>
        @endif
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Mobile sidebar toggle
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    document.getElementById('sidebarToggle')?.addEventListener('click', () => {
        sidebar.classList.toggle('is-open');
        backdrop.classList.toggle('is-open');
    });
    backdrop?.addEventListener('click', () => {
        sidebar.classList.remove('is-open');
        backdrop.classList.remove('is-open');
    });

    /**
     * Reusable delete confirmation with SweetAlert2.
     */
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.js-delete-btn');
        if (!btn) return;
        e.preventDefault();

        const url = btn.dataset.url;
        const name = btn.dataset.name || 'this record';
        const token = document.querySelector('meta[name="csrf-token"]')?.content;

        Swal.fire({
            title: 'Are you sure?',
            html: `You are about to remove <strong>${name}</strong>.<br>This action cannot be undone from here.`,
            icon: 'warning',
            iconColor: '#FA8112',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel',
            customClass: { popup: 'nexfit-swal', confirmButton: 'btn btn-orange', cancelButton: 'btn btn-outline-secondary ms-2' },
            buttonsStyling: false,
        }).then((result) => {
            if (!result.isConfirmed) return;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.innerHTML = `
                <input type="hidden" name="_token" value="${token}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            form.submit();
        });
    });
</script>

@stack('scripts')

<script>
    // ===== DARK MODE TOGGLE =====
    const html        = document.documentElement;
    const btn         = document.getElementById('themeModeBtn');
    const icon        = document.getElementById('themeModeIcon');
    const label       = document.getElementById('themeModeLabel');

    // Apply saved preference immediately (no flash)
    const saved = localStorage.getItem('nexfit-theme') || 'light';
    applyTheme(saved);

    btn.addEventListener('click', function () {
        const next = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
        applyTheme(next);
        localStorage.setItem('nexfit-theme', next);
    });

    function applyTheme(theme) {
        html.setAttribute('data-theme', theme);
        if (theme === 'dark') {
            icon.className  = 'bi bi-sun';
            label.textContent = 'Light Mode';
        } else {
            icon.className  = 'bi bi-moon';
            label.textContent = 'Dark Mode';
        }
    }
</script>

</body>
</html>