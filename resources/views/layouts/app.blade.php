<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Desa Nekmese - Amarasi Selatan, Kabupaten Kupang')</title>
    <meta name="description" content="Desa Nekmese, Kecamatan Amarasi Selatan, Kabupaten Kupang, Nusa Tenggara Timur">
    @php
        $_favicon = App\Models\Setting::getValue('favicon_url');
        $_faviconHref = $_favicon
            ? (str_starts_with($_favicon, 'http') ? $_favicon : asset('images/' . ltrim($_favicon, '/')))
            : "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='6' fill='%230D9488'/%3E%3Ctext x='16' y='22' text-anchor='middle' font-size='18' font-weight='800' font-family='sans-serif' fill='%23fff'%3EN%3C/text%3E%3C/svg%3E";
    @endphp
    <link rel="icon" href="{{ $_faviconHref }}">
    <link rel="shortcut icon" href="{{ $_faviconHref }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Instrument+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <style>
        :root {
            --primary: #0F172A;
            --primary-light: #1E293B;
            --accent: #0D9488;
            --accent-dark: #0F766E;
            --accent-light: #14B8A6;
            --gold: #F59E0B;
            --gold-light: #FEF3C7;
            --bg: #F8FAFC;
            --card-bg: #ffffff;
            --text: #1E293B;
            --text-muted: #64748B;
            --border: #E2E8F0;
            --shadow: 0 1px 3px rgba(0,0,0,0.06);
            --shadow-lg: 0 8px 30px rgba(0,0,0,0.1);
            --radius: 12px;
            --radius-sm: 8px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', 'Instrument Sans', sans-serif; color: var(--text); background: var(--bg); }
        a { text-decoration: none; color: inherit; }

        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 0 2rem;
            background: transparent;
            border-bottom: 1px solid transparent;
            transition: all 0.3s ease;
        }
        .navbar.scrolled {
            background: rgba(15,23,42,0.92);
            backdrop-filter: blur(18px) saturate(180%);
            -webkit-backdrop-filter: blur(18px) saturate(180%);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        .navbar .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #fff;
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: -0.3px;
            flex-shrink: 0;
            margin-right: auto;
        }
        .navbar.scrolled .logo { color: #fff; }

        .nav-right-group {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        .navbar .logo-icon {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 800;
            color: #fff;
            background: var(--accent);
        }

        .desktop-nav {
            display: flex;
            align-items: center;
            gap: 2rem;
            overflow-x: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .desktop-nav::-webkit-scrollbar { display: none; }
        .desktop-nav a {
            position: relative;
            padding: 0.4rem 0;
            font-size: 0.82rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: rgba(255,255,255,0.75);
            transition: color 0.2s ease;
            white-space: nowrap;
        }
        .navbar.scrolled .desktop-nav a { color: rgba(255,255,255,0.65); }
        .desktop-nav a::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 50%;
            width: 100%;
            height: 2px;
            background: #fff;
            border-radius: 2px;
            transform: translateX(-50%) scaleX(0);
            transition: transform 0.25s cubic-bezier(0.4,0,0.2,1);
            transform-origin: center;
        }
        .navbar.scrolled .desktop-nav a::after { background: #14B8A6; }
        .desktop-nav a:hover { color: #fff; }
        .navbar.scrolled .desktop-nav a:hover { color: #fff; }
        .desktop-nav a:hover::after { transform: translateX(-50%) scaleX(0.4); }
        .desktop-nav a.active { color: #fff; font-weight: 600; }
        .navbar.scrolled .desktop-nav a.active { color: #fff; }
        .desktop-nav a.active::after { transform: translateX(-50%) scaleX(1); }

        .navbar-cta {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.55rem 1.35rem;
            border-radius: 100px;
            background: var(--accent);
            color: #fff;
            font-size: 0.82rem;
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all 0.2s ease;
            letter-spacing: -0.1px;
            flex-shrink: 0;
            white-space: nowrap;
        }
        .navbar-cta:hover {
            background: #0f766e;
            box-shadow: 0 4px 16px rgba(13,148,136,0.2);
            transform: translateY(-1px);
        }
        .navbar-cta i { font-size: 0.6rem; }

        .nav-mobile-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.3rem;
            border-radius: 8px;
            color: #fff;
            width: 40px;
            height: 40px;
            position: relative;
            transition: background 0.2s;
        }
        .navbar.scrolled .nav-mobile-toggle { color: #fff; }
        .nav-mobile-toggle:hover { background: #f1f5f9; }
        .nav-mobile-toggle .h-line {
            display: block;
            width: 20px;
            height: 2.5px;
            background: currentColor;
            border-radius: 2px;
            position: absolute;
            left: 10px;
            transition: all 0.25s cubic-bezier(0.4,0,0.2,1);
        }
        .nav-mobile-toggle .h-line:nth-child(1) { top: 13px; }
        .nav-mobile-toggle .h-line:nth-child(2) { top: 19px; }
        .nav-mobile-toggle.open .h-line:nth-child(1) { top: 16px; transform: rotate(45deg); }
        .nav-mobile-toggle.open .h-line:nth-child(2) { top: 16px; transform: rotate(-45deg); }

        .mobile-overlay {
            position: fixed;
            inset: 0;
            z-index: 999;
            display: flex;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        .mobile-overlay.open { opacity: 1; pointer-events: all; }
        .mobile-overlay .backdrop {
            position: absolute;
            inset: 0;
            background: rgba(15,23,42,0.55);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .mobile-overlay.open .backdrop { opacity: 1; }
        .mobile-overlay .drawer {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 280px;
            max-width: 85vw;
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            display: flex;
            flex-direction: column;
            border-radius: 24px 0 0 24px;
            box-shadow: -8px 0 40px rgba(0,0,0,0.12);
            transform: translateX(100%);
            transition: transform 0.35s cubic-bezier(0.32,0.72,0,1);
            overflow-y: auto;
        }
        .mobile-overlay.open .drawer { transform: translateX(0); }
        .mobile-overlay .drawer-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.25rem 0.75rem;
            border-bottom: 1px solid rgba(226,232,240,0.5);
        }
        .mobile-overlay .drawer-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--text);
        }
        .mobile-overlay .drawer-logo img { height: 28px; width: auto; border-radius: 6px; }
        .mobile-overlay .drawer-logo .logo-fallback {
            width: 26px; height: 26px; border-radius: 7px;
            background: var(--accent); color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.6rem; font-weight: 800;
        }
        .mobile-overlay .drawer-close {
            width: 30px; height: 30px; border-radius: 8px;
            background: #f1f5f9; border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: #64748b; font-size: 0.85rem;
            transition: all 0.15s;
        }
        .mobile-overlay .drawer-close:hover { background: #e2e8f0; color: var(--text); }
        .mobile-overlay .drawer-body {
            flex: 1;
            padding: 0.5rem 0.75rem;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .mobile-overlay .m-group-label {
            font-size: 0.55rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #94a3b8;
            padding: 0.5rem 0.75rem 0;
            margin-top: 0.25rem;
        }
        .mobile-overlay .drawer-body .menu-card {
            display: block;
            position: relative;
            height: 68px;
            border-radius: 14px;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .mobile-overlay .drawer-body .menu-card::before {
            content: '';
            position: absolute;
            inset: 0;
            z-index: 1;
            background: linear-gradient(to right, rgba(15,23,42,0.82) 0%, rgba(15,23,42,0.35) 65%, transparent 100%);
        }
        .mobile-overlay .drawer-body .menu-card:active { transform: scale(0.97); }
        .mobile-overlay .drawer-body .menu-card .mc-content {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 12px;
            height: 100%;
            padding: 0 1rem;
        }
        .mobile-overlay .drawer-body .menu-card .mc-content i {
            width: 22px;
            text-align: center;
            font-size: 0.95rem;
            color: #fff;
        }
        .mobile-overlay .drawer-body .menu-card .mc-content .mc-text {
            font-size: 0.8rem;
            font-weight: 600;
            color: #fff;
        }
        .mobile-overlay .drawer-body .menu-card .mc-icon-bg {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 2.5rem;
            opacity: 0.08;
            color: #fff;
            z-index: 1;
            pointer-events: none;
        }
        .mobile-overlay .drawer-body .menu-card.active {
            box-shadow: 0 0 0 2px rgba(255,255,255,0.3);
        }
        .mobile-overlay .drawer-cta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin: 0.75rem;
            padding: 0.75rem;
            border-radius: 12px;
            background: var(--primary);
            color: #fff;
            font-weight: 700;
            font-size: 0.8rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        .mobile-overlay .drawer-cta:hover { background: #1e293b; }
        .mobile-overlay .drawer-cta i { color: #fff; width: auto; font-size: 0.65rem; }

        @media (max-width: 1024px) {
            .navbar { padding: 0 1rem; height: 64px; }
            .nav-right-group, .desktop-nav, .navbar-cta { display: none; }
            .nav-mobile-toggle { display: block; }
        }
        @media (min-width: 1025px) {
            .mobile-overlay { display: none !important; }
        }

        .hero-section {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: var(--primary);
        }
        .hero-bg {
            position: absolute;
            inset: 0;
            background:
                linear-gradient(135deg, #0c1422 0%, #0a1020 30%, var(--primary) 60%, #141a2a 100%);
        }
        .hero-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse at 30% 40%, rgba(13,148,136,0.1) 0%, transparent 60%),
                radial-gradient(ellipse at 70% 60%, rgba(217,119,6,0.06) 0%, transparent 50%);
        }
        .hero-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.45);
        }
        .hero-pattern {
            position: absolute;
            inset: 0;
            opacity: 0.04;
            background-image:
                repeating-linear-gradient(0deg, transparent, transparent 40px, rgba(255,255,255,0.03) 40px, rgba(255,255,255,0.03) 41px),
                repeating-linear-gradient(90deg, transparent, transparent 40px, rgba(255,255,255,0.03) 40px, rgba(255,255,255,0.03) 41px);
        }
        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 800px;
            padding: 2rem;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.35rem 1.1rem;
            border-radius: 100px;
            font-size: 0.68rem;
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255,255,255,0.12);
            color: rgba(255,255,255,0.6);
            background: rgba(255,255,255,0.04);
        }
        .hero-title {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            font-size: 4.5rem;
            font-weight: 800;
            line-height: 1.05;
            color: #fff;
            margin-bottom: 0.3rem;
            letter-spacing: -0.02em;
        }
        .hero-subtitle {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            font-size: 1.6rem;
            font-weight: 500;
            color: rgba(255,255,255,0.65);
            margin-bottom: 1.5rem;
            letter-spacing: -0.01em;
        }
        .hero-tagline {
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 1rem;
        }
        .hero-desc {
            font-size: 0.95rem;
            color: rgba(255,255,255,0.55);
            max-width: 580px;
            margin: 0 auto 2rem;
            line-height: 1.8;
        }
        .hero-cta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.75rem 2rem;
            border: none;
            border-radius: 100px;
            color: #fff;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: var(--transition);
            background: var(--accent);
            box-shadow: 0 4px 14px rgba(13,148,136,0.25);
        }
        .hero-cta:hover {
            background: var(--accent-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13,148,136,0.35);
        }
        .hero-cta i { font-size: 0.7rem; }
        .scroll-indicator {
            position: absolute;
            bottom: 2.5rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            color: rgba(255,255,255,0.35);
            font-size: 0.6rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .scroll-indicator .scroll-arrow {
            width: 24px;
            height: 24px;
            border: 1.5px solid rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.55rem;
            animation: scrollBounce 2s ease infinite;
        }
        @keyframes scrollBounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(6px); }
        }

        @media (max-width: 768px) {
            .hero-title { font-size: 2.5rem; }
            .hero-subtitle { font-size: 1.3rem; }
            .hero-tagline { font-size: 0.65rem; letter-spacing: 3px; }
            .hero-desc { font-size: 0.85rem; }
            .hero-cta { padding: 0.6rem 1.5rem; font-size: 0.72rem; }
            .hero-badge { font-size: 0.6rem; }
        }

        .section-title {
            text-align: center;
            margin: 0 0 1.5rem;
        }
        .section-title h2 {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.02em;
            margin-bottom: 0.2rem;
        }
        .section-title h2 span { color: var(--accent); }
        .section-title p {
            color: #64748b;
            font-size: 0.82rem;
            max-width: 500px;
            margin: 0 auto;
            line-height: 1.6;
        }
        .section-title .accent-line {
            width: 36px;
            height: 3px;
            background: var(--gold);
            border-radius: 3px;
            margin: 0.5rem auto;
        }

        .map-section {
            padding: 0 1.5rem 3rem;
            max-width: 1440px;
            margin: 0 auto;
        }
        .map-section .dashboard-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06), 0 1px 4px rgba(0,0,0,0.03);
            padding: 1.5rem;
        }
        @media (max-width: 768px) {
            .map-section { padding: 0 0.75rem 1.5rem; }
            .map-section .dashboard-card { padding: 0.75rem; border-radius: 14px; }
            .section-title h2 { font-size: 1.6rem; }
        }

        .stats-section { max-width: 1440px; margin: 0 auto; padding: 0 1.5rem 3rem; }
        .stats-section .dashboard-card { background: #fff; border-radius: 20px; box-shadow: 0 4px 24px rgba(0,0,0,0.06); padding: 1.5rem; }
        .chart-container { width: 100%; }
        @media (max-width: 768px) {
            .chart-container { height: 280px !important; }
        }

        .fab {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 999;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: #fff;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 4px 15px rgba(13,148,136,0.3);
            transition: var(--transition);
        }
        .fab:hover { transform: scale(1.08); box-shadow: 0 6px 20px rgba(13,148,136,0.4); }
        .fab .badge-dot {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 14px;
            height: 14px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .footer {
            background: var(--primary);
            color: rgba(255,255,255,0.6);
            padding: 3.5rem 2rem 1.5rem;
            position: relative;
        }
        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
        }
        .footer-grid { max-width: 1240px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 2.5rem; }
        .footer h4 {
            color: #fff;
            margin-bottom: 1rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            position: relative;
            padding-bottom: 0.6rem;
        }
        .footer h4::after { content: ''; position: absolute; bottom: 0; left: 0; width: 24px; height: 2px; background: var(--gold); border-radius: 2px; }
        .footer p, .footer a { font-size: 0.82rem; line-height: 2; color: rgba(255,255,255,0.5); transition: var(--transition); }
        .footer a:hover { color: var(--gold); }
        .footer .footer-logo { font-size: 1.2rem; font-weight: 800; color: #fff; display: flex; align-items: center; gap: 10px; margin-bottom: 0.75rem; }
        .footer .footer-logo .logo-icon { width: 34px; height: 34px; background: var(--gold); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.85rem; font-weight: 800; color: #0d1a14; }
        .footer-bottom { text-align: center; padding-top: 2rem; margin-top: 2rem; border-top: 1px solid rgba(255,255,255,0.06); font-size: 0.72rem; color: rgba(255,255,255,0.3); max-width: 1240px; margin-left: auto; margin-right: auto; }
        .footer-bottom strong { color: rgba(255,255,255,0.45); }

        .leaflet-popup-content-wrapper { border-radius: 12px !important; box-shadow: 0 8px 30px rgba(0,0,0,0.15) !important; padding: 0 !important; overflow: hidden; }
        .leaflet-popup-content { margin: 0 !important; }
        .leaflet-popup-tip { box-shadow: 0 4px 14px rgba(0,0,0,0.1) !important; }
        .popup-content { min-width: 260px; max-width: 320px; }
        .popup-content .popup-img { width: 100%; height: 140px; object-fit: cover; }
        .popup-content .popup-img-placeholder { width: 100%; height: 100px; background: linear-gradient(135deg, #ccfbf1, #99f6e4); display: flex; align-items: center; justify-content: center; font-size: 2rem; color: var(--accent); opacity: 0.5; }
        .popup-content .popup-body { padding: 12px 14px 14px; }
        .popup-content h3 { font-size: 0.92rem; font-weight: 700; color: var(--text); margin-bottom: 2px; }
        .popup-content .popup-badge { display: inline-block; padding: 2px 8px; background: #ccfbf1; color: var(--accent-dark); border-radius: 5px; font-size: 0.62rem; font-weight: 700; margin: 3px 0 6px; }
        .popup-content .popup-addr { font-size: 0.73rem; color: var(--text-muted); line-height: 1.4; margin-bottom: 5px; display: flex; align-items: flex-start; gap: 4px; }
        .popup-content .popup-addr i { margin-top: 3px; font-size: 0.65rem; color: var(--accent); }
        .popup-content .popup-desc { font-size: 0.76rem; color: var(--text); line-height: 1.5; margin-bottom: 8px; }
        .popup-content .popup-nav { display: inline-flex; align-items: center; gap: 5px; padding: 6px 14px; background: var(--accent); color: #fff; border-radius: 6px; font-size: 0.7rem; font-weight: 600; transition: var(--transition); }
        .popup-content .popup-nav:hover { background: var(--accent-dark); }
        .leaflet-popup-close-button { top: 8px !important; right: 8px !important; width: 22px !important; height: 22px !important; background: rgba(0,0,0,0.45) !important; color: #fff !important; border-radius: 50% !important; font-size: 12px !important; line-height: 22px !important; display: flex !important; align-items: center !important; justify-content: center !important; }
        .leaflet-popup-close-button:hover { background: rgba(0,0,0,0.7) !important; }
        .marker-pin { width: 30px; height: 30px; border-radius: 50% 50% 50% 0; position: relative; transform: rotate(-45deg); border: 2px solid #fff; box-shadow: 0 2px 6px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center; }
        .marker-pin i { transform: rotate(45deg); color: #fff; font-size: 0.7rem; }
        .leaflet-control-zoom a { background: var(--card-bg) !important; color: var(--text) !important; border-color: var(--border) !important; width: 36px !important; height: 36px !important; line-height: 36px !important; font-size: 1.05rem !important; border-radius: 0 !important; box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important; }
        .leaflet-control-zoom a:first-child { border-radius: 10px 10px 0 0 !important; }
        .leaflet-control-zoom a:last-child { border-radius: 0 0 10px 10px !important; border-top: 0 !important; }
        .leaflet-control-zoom a:hover { background: #f1f5f9 !important; }
        .leaflet-control-zoom { border: none !important; box-shadow: none !important; }
        .leaflet-control-attribution { font-size: 0.6rem !important; }

        .fade-in { animation: fadeIn 0.4s ease both; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes markerPulse {
            0% { box-shadow: 0 0 0 0 rgba(13,148,136,0.5); }
            70% { box-shadow: 0 0 0 14px rgba(13,148,136,0); }
            100% { box-shadow: 0 0 0 0 rgba(13,148,136,0); }
        }
        .marker-pulse { animation: markerPulse 0.8s ease 2; }
        .toast {
            position: fixed; bottom: 80px; right: 24px;
            background: var(--accent-dark); color: #fff; padding: 0.7rem 1.5rem;
            border-radius: var(--radius-sm); font-size: 0.82rem; font-weight: 500;
            opacity: 0; transform: translateY(20px); transition: var(--transition);
            z-index: 9999; box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        .toast.show { opacity: 1; transform: translateY(0); }

        /* â”€â”€ Scroll-Reveal Animation â”€â”€ */
        .reveal {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity 0.7s ease-out, transform 0.7s ease-out;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
        .reveal-delay-4 { transition-delay: 0.4s; }
        @media (prefers-reduced-motion: reduce) {
            .reveal { opacity: 1; transform: none; transition: none; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar" id="navbar">
        @php $_logo = App\Models\Setting::getValue('logo_url'); @endphp
        <a href="/" class="logo">
            @if($_logo)
            <img src="{{ str_starts_with($_logo, 'http') ? $_logo : asset('images/' . ltrim($_logo, '/')) }}" alt="Logo" style="height:36px;width:auto;border-radius:8px;">
            @else
            <div class="logo-icon">N</div>
            @endif
            Desa Nekmese
        </a>

        <div class="nav-right-group">
            <div class="desktop-nav">
                <a href="/">Beranda</a>
                <a href="/aset-desa">Aset Desa</a>
                <a href="/pendidikan">Pendidikan</a>
                <a href="/kesehatan">Kesehatan</a>
                <a href="/ibadah">Tempat Ibadah</a>
            </div>

            <a href="/#peta-desa" class="navbar-cta">
                Jelajahi Peta <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <button class="nav-mobile-toggle" id="mobileToggle" aria-label="Menu">
            <span class="h-line"></span>
            <span class="h-line"></span>
        </button>
    </nav>

    <div class="mobile-overlay" id="mobileOverlay">
        <div class="backdrop" id="sidebar-overlay"></div>
        <div class="drawer">
            <div class="drawer-header">
                <div class="drawer-logo">
                    @if($_logo)
<img src="{{ str_starts_with($_logo, 'http') ? $_logo : asset('images/' . ltrim($_logo, '/')) }}" alt="Logo">
                     @else
                     <div class="logo-fallback">N</div>
                    @endif
                    Desa Nekmese
                </div>
                <button class="drawer-close" id="close-sidebar-btn" aria-label="Tutup menu"><i class="fas fa-xmark"></i></button>
            </div>
            <div class="drawer-body">
                @php $_sidebarMenus = App\Models\SidebarMenu::active()->ordered()->get(); @endphp
                @foreach($_sidebarMenus as $i => $m)
                @if($i === 0)<div class="m-group-label">Menu Utama</div>@endif
                @if($i === 1)<div class="m-group-label">Fasilitas</div>@endif
                <a href="{{ $m->target_link }}" class="menu-card" style="{{ $m->banner_image_url ? 'background-image:url(' . $m->banner_url . ')' : $m->default_gradient }}">
                    <span class="mc-icon-bg"><i class="{{ $m->icon_name }}"></i></span>
                    <span class="mc-content"><i class="{{ $m->icon_name }}"></i><span class="mc-text">{{ $m->menu_name }}</span></span>
                </a>
                @endforeach
            </div>
            <a href="/#peta-desa" class="drawer-cta" onclick="closeDrawer()">Jelajahi Peta <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>

    @yield('hero')

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="footer-grid">
            <div>
                <div class="footer-logo">
                    @if($_logo)
                    <img src="{{ str_starts_with($_logo, 'http') ? $_logo : asset('images/' . ltrim($_logo, '/')) }}" alt="Logo" style="height:32px;width:auto;border-radius:50%;">
                    @else
                    <div class="logo-icon">N</div>
                    @endif
                    Desa Nekmese
                </div>
                <p>Sistem Informasi Geografis fasilitas umum Desa Nekmese, Kecamatan Amarasi Selatan, Kabupaten Kupang, Provinsi Nusa Tenggara Timur.</p>
            </div>
            <div>
                <h4>Fasilitas</h4>
                <p><a href="/">Beranda / Peta Utama</a><br>
                <a href="#map-section">Peta Fasilitas</a><br>
                Kantor Desa<br>
                Sekolah / Pendidikan<br>
                Gereja / Tempat Ibadah<br>
                Kesehatan (Posyandu/Pustu)</p>
            </div>
            <div>
                <h4>Kontak</h4>
                <p><i class="fas fa-map-marker-alt" style="width:18px;opacity:0.5;"></i> Kantor Desa Nekmese<br>
                <i class="fas fa-map-pin" style="width:18px;opacity:0.5;"></i> Kec. Amarasi Selatan<br>
                <i class="fas fa-globe" style="width:18px;opacity:0.5;"></i> Kab. Kupang, NTT</p>
            </div>
            <div>
                <h4>Tautan</h4>
                <p><a href="https://desanekmese.vercel.app/" target="_blank"><i class="fas fa-globe" style="width:16px;"></i> Portal Resmi Desa</a><br>
                <a href="https://www.google.com/maps/search/Desa+Nekmese+Amarasi+Selatan" target="_blank"><i class="fas fa-map" style="width:16px;"></i> Google Maps</a></p>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; {{ date('Y') }} GIS Fasilitas Umum Desa Nekmese &mdash; Dikembangkan oleh <strong>Tim KKN UNWIRA Kupang</strong>
        </div>
    </footer>

    <button class="fab" id="fabBtn" onclick="showToast('Halo! Ada yang bisa kami bantu?')">
        <i class="fas fa-comment"></i>
        <div class="badge-dot"></div>
    </button>

    <div class="toast" id="toast"></div>

    <script>
        var navbar = document.getElementById('navbar');
        var mobileToggle = document.getElementById('mobileToggle');
        var mobileOverlay = document.getElementById('mobileOverlay');

        window.addEventListener('scroll', function() {
            if (window.scrollY > 20) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        mobileToggle.addEventListener('click', function() {
            if (mobileOverlay.classList.contains('open')) {
                closeDrawer();
            } else {
                mobileOverlay.classList.add('open');
                mobileToggle.classList.add('open');
                document.body.style.overflow = 'hidden';
            }
        });

        function closeDrawer() {
            mobileOverlay.classList.remove('open');
            mobileToggle.classList.remove('open');
            document.body.style.overflow = '';
        }

        document.getElementById('close-sidebar-btn').addEventListener('click', closeDrawer);
        document.getElementById('sidebar-overlay').addEventListener('click', closeDrawer);

        mobileOverlay.querySelectorAll('.drawer-body a').forEach(function(link) {
            link.addEventListener('click', closeDrawer);
        });

        (function() {
            var path = window.location.pathname;
            document.querySelectorAll('.desktop-nav a, .drawer-body a').forEach(function(link) {
                var href = link.getAttribute('href');
                if (href === path || (href === '/' && path === '')) {
                    link.classList.add('active');
                }
            });
        })();

        function showToast(msg) {
            var t = document.getElementById('toast');
            t.textContent = msg;
            t.classList.add('show');
            clearTimeout(t._timer);
            t._timer = setTimeout(function() { t.classList.remove('show'); }, 2500);
        }

        document.querySelectorAll('a[href*="#peta-desa"]').forEach(function(a) {
            a.addEventListener('click', function(e) {
                if (window.location.pathname === '/' || window.location.pathname === '') {
                    e.preventDefault();
                    var el = document.getElementById('peta-desa');
                    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        if (window.location.hash === '#peta-desa') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    var el = document.getElementById('peta-desa');
                    if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 400);
            });
        }

        (function() {
            var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (reduceMotion) return;
            var revealEls = document.querySelectorAll('.reveal');
            if (!revealEls.length) return;
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15 });
            revealEls.forEach(function(el) { observer.observe(el); });
        })();
    </script>
    @stack('scripts')
</body>
</html>
