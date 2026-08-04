<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — Desa Nekmese</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #0F172A;
            --accent: #0D9488;
            --accent-dark: #0F766E;
            --gold: #F59E0B;
            --bg: #F1F5F9;
            --sidebar: #0F172A;
            --sidebar-hover: #1E293B;
            --text: #1E293B;
            --text-muted: #64748B;
            --border: #E2E8F0;
            --radius: 10px;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }
        /* ── Sidebar ── */
        .sidebar {
            width: 260px;
            background: var(--sidebar);
            color: #fff;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
            overflow-y: auto;
        }
        .sidebar .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            font-weight: 700;
            font-size: 1rem;
        }
        .sidebar .brand .logo-icon {
            width: 30px; height: 30px;
            background: var(--accent);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 800;
            color: #fff;
        }
        .sidebar .nav-section { padding: 1rem 0; }
        .sidebar .nav-label {
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: rgba(255,255,255,0.3);
            padding: 0.5rem 1.5rem 0.25rem;
        }
        .sidebar a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.6rem 1.5rem;
            font-size: 0.82rem;
            font-weight: 500;
            color: rgba(255,255,255,0.6);
            text-decoration: none;
            transition: all 0.15s;
        }
        .sidebar a:hover { background: var(--sidebar-hover); color: #fff; }
        .sidebar a.active { background: rgba(13,148,136,0.15); color: var(--accent); }
        .sidebar a i { width: 18px; text-align: center; font-size: 0.8rem; }
        .sidebar .spacer { flex: 1; }
        .sidebar .logout-link {
            border-top: 1px solid rgba(255,255,255,0.06);
            padding-top: 0.5rem;
            margin-top: auto;
        }
        .sidebar .logout-link a { color: rgba(255,255,255,0.4); }
        .sidebar .logout-link a:hover { color: #ef4444; }

        /* ── Main Content ── */
        .main {
            margin-left: 260px;
            flex: 1;
            padding: 2rem;
            min-height: 100vh;
        }
        .main-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }
        .main-header h1 {
            font-size: 1.4rem;
            font-weight: 800;
        }
        .main-header .breadcrumb {
            font-size: 0.75rem;
            color: var(--text-muted);
        }
        .main-header .breadcrumb a { color: var(--accent); text-decoration: none; }

        /* ── Cards ── */
        .card {
            background: #fff;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .card-header {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border);
            font-weight: 700;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-body { padding: 1.25rem; }

        /* ── Stats Grid ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: #fff;
            border-radius: var(--radius);
            border: 1px solid var(--border);
            padding: 1.25rem;
        }
        .stat-card .num {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--accent);
        }
        .stat-card .label {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 2px;
            font-weight: 500;
        }
        .stat-card .icon {
            float: right;
            font-size: 1.5rem;
            color: var(--border);
        }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
        th { text-align: left; padding: 0.7rem 0.75rem; border-bottom: 2px solid var(--border); font-weight: 600; color: var(--text-muted); font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 0.7rem 0.75rem; border-bottom: 1px solid var(--border); }
        td .actions { display: flex; gap: 6px; }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 0.4rem 1rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            font-family: inherit;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
        }
        .btn-sm { padding: 0.3rem 0.7rem; font-size: 0.7rem; }
        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-dark); }
        .btn-outline { background: #fff; color: var(--text); border: 1.5px solid var(--border); }
        .btn-outline:hover { border-color: var(--accent); color: var(--accent); }
        .btn-danger { background: #ef4444; color: #fff; }
        .btn-danger:hover { background: #dc2626; }
        .btn-warning { background: var(--gold); color: #fff; }
        .btn-warning:hover { background: #d97706; }

        /* ── Form ── */
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-size: 0.78rem; font-weight: 600; margin-bottom: 0.3rem; color: var(--text); }
        .form-group .form-control {
            width: 100%;
            padding: 0.55rem 0.75rem;
            border: 1.5px solid var(--border);
            border-radius: 6px;
            font-size: 0.82rem;
            font-family: inherit;
            transition: border-color 0.15s;
            background: #fff;
        }
        .form-group .form-control:focus { outline: none; border-color: var(--accent); }
        .form-group textarea.form-control { min-height: 100px; resize: vertical; }
        .form-group .form-hint { font-size: 0.68rem; color: var(--text-muted); margin-top: 0.2rem; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        @media (max-width: 640px) { .form-row { grid-template-columns: 1fr; } }
        .form-group .form-check { display: flex; align-items: center; gap: 0.5rem; }
        .form-group .form-check input[type="checkbox"] { width: 16px; height: 16px; }
        .preview-img { max-width: 200px; border-radius: 8px; border: 1px solid var(--border); margin-top: 0.5rem; }

        /* ── Alert ── */
        .alert {
            padding: 0.75rem 1rem;
            border-radius: 6px;
            font-size: 0.82rem;
            margin-bottom: 1rem;
        }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        /* ── Badge ── */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 5px;
            font-size: 0.65rem;
            font-weight: 700;
        }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }

        /* ── Pagination ── */
        .pagination { display: flex; justify-content: center; gap: 4px; margin-top: 1.5rem; }
        .pagination a, .pagination span {
            padding: 0.35rem 0.7rem;
            border-radius: 6px;
            font-size: 0.78rem;
            text-decoration: none;
            border: 1px solid var(--border);
            color: var(--text);
        }
        .pagination .active { background: var(--accent); color: #fff; border-color: var(--accent); }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .sidebar { width: 220px; }
            .main { margin-left: 0; padding: 1rem; }
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .mobile-toggle { display: flex !important; }
        }
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: var(--text);
            padding: 0.3rem;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="adminSidebar">
        <div class="brand">
            <div class="logo-icon">N</div>
            Desa Nekmese
        </div>
        <div class="nav-section">
            <div class="nav-label">Menu</div>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-chart-pie"></i> Dashboard</a>
            <a href="{{ route('admin.facilities.index') }}" class="{{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}"><i class="fas fa-building"></i> Fasilitas</a>
            <a href="{{ route('admin.settings.hero') }}" class="{{ request()->routeIs('admin.settings.hero') ? 'active' : '' }}"><i class="fas fa-image"></i> Hero Background</a>
            <a href="{{ route('admin.section-assets.edit') }}" class="{{ request()->routeIs('admin.section-assets.*') ? 'active' : '' }}"><i class="fas fa-images"></i> Section Aset Beranda</a>
            <a href="{{ route('admin.settings.site') }}" class="{{ request()->routeIs('admin.settings.site') ? 'active' : '' }}"><i class="fas fa-palette"></i> Identitas Desa</a>
            <a href="{{ route('admin.sidebar-menus.index') }}" class="{{ request()->routeIs('admin.sidebar-menus.*') ? 'active' : '' }}"><i class="fas fa-bars"></i> Menu Sidebar</a>
        </div>
        <div class="spacer"></div>
        <div class="logout-link">
            <a href="{{ route('admin.password.edit') }}" class="{{ request()->routeIs('admin.password.*') ? 'active' : '' }}"><i class="fas fa-key"></i> Ganti Password</a>
            <a href="{{ route('admin.logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Keluar</a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display:none;">@csrf</form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="main">
        <div class="main-header">
            <div>
                <button class="mobile-toggle" onclick="document.getElementById('adminSidebar').classList.toggle('open')"><i class="fas fa-bars"></i></button>
                <div class="breadcrumb">
                    <a href="{{ route('admin.dashboard') }}">Admin</a>
                    @hasSection('breadcrumb') / @yield('breadcrumb') @endif
                </div>
                <h1>@yield('title', 'Dashboard')</h1>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-error" style="margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif

        @yield('content')
    </div>

    <script>
        document.addEventListener('click', function(e) {
            var sidebar = document.getElementById('adminSidebar');
            if (window.innerWidth <= 768 && sidebar.classList.contains('open') && !sidebar.contains(e.target) && !e.target.closest('.mobile-toggle')) {
                sidebar.classList.remove('open');
            }
        });
        // Auto-dismiss flash alerts
        document.querySelectorAll('.alert-success, .alert-error').forEach(function(el) {
            setTimeout(function() {
                el.style.transition = 'opacity 0.5s';
                el.style.opacity = '0';
                setTimeout(function() { el.remove(); }, 500);
            }, 4000);
        });
    </script>
</body>
</html>
