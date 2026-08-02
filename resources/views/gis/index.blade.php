@extends('layouts.app')

@section('title', 'Desa Nekmese - Beranda')

@php
    $sektorTotals = [];
    $sektorIcons = [
        'Pemerintahan'   => 'fa-landmark',
        'Pendidikan'     => 'fa-school',
        'Kesehatan'      => 'fa-heartbeat',
        'Tempat Ibadah'  => 'fa-place-of-worship',
        'Fasilitas Umum' => 'fa-futbol',
    ];
    $sektorColors = [
        'Pemerintahan'   => '#0D9488',
        'Pendidikan'     => '#0D9488',
        'Kesehatan'      => '#14B8A6',
        'Tempat Ibadah'  => '#F59E0B',
        'Fasilitas Umum' => '#F59E0B',
    ];
    $uniqueKategoris = [];
    foreach ($facilities as $f) {
        $sektor = $sektorList[$f->kategori] ?? 'Fasilitas Umum';
        if (!isset($sektorTotals[$sektor])) $sektorTotals[$sektor] = 0;
        $sektorTotals[$sektor]++;
        $uniqueKategoris[$f->kategori] = true;
    }
    arsort($sektorTotals);
    $asetKategoriKeys = array_keys(array_filter($jenisList, fn($v) => $v === 'aset_desa'));
    $asetDesaFacilities = $facilities->filter(fn($f) => in_array($f->kategori, $asetKategoriKeys))->take(3);
    $pendidikanFacilities = $facilities->filter(fn($f) => ($sektorList[$f->kategori] ?? '') === 'Pendidikan')->take(3);
    $kesehatanFacilities = $facilities->filter(fn($f) => ($sektorList[$f->kategori] ?? '') === 'Kesehatan')->take(3);
    $ibadahFacilities = $facilities->filter(fn($f) => ($sektorList[$f->kategori] ?? '') === 'Tempat Ibadah')->take(3);
    $umumFacilities = $facilities->filter(fn($f) => ($sektorList[$f->kategori] ?? '') === 'Fasilitas Umum')->take(3);
@endphp

@push('styles')
<style>
    .hp-section {
        max-width: 1440px;
        margin: 0 auto;
        padding: 2.5rem 1.5rem;
    }
    @media (max-width: 768px) {
        .hp-section { padding: 1.5rem 0.75rem; }
    }

    .sec-badge {
        display: inline-flex; align-items: center; gap: 5px;
        font-size: 0.6rem; font-weight: 700; letter-spacing: 1px;
        text-transform: uppercase; color: #94a3b8; margin-bottom: 0.1rem;
    }
    .sec-badge i { color: var(--accent); font-size: 0.65rem; }

    .sec-head {
        display: flex; align-items: flex-end; justify-content: space-between;
        flex-wrap: wrap; gap: 0.5rem; margin-bottom: 1.25rem;
    }
    .sec-head-left { flex: 1; min-width: 200px; }
    .sec-head-left .gold-line {
        width: 32px; height: 3px; background: var(--gold);
        border-radius: 3px; margin: 0 0 0.5rem;
    }
    .sec-head-left h2 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.35rem; font-weight: 800; color: var(--primary); line-height: 1.2;
    }
    .sec-head-left h2 span { color: var(--accent); }
    .sec-head-left p {
        margin: 0.1rem 0 0; font-size: 0.77rem; color: #64748b;
    }

    .pill-cta {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 0.7rem 1.8rem; border-radius: 9999px; border: none;
        background: var(--primary); color: #fff;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.72rem; font-weight: 700; letter-spacing: 1px;
        text-transform: uppercase; cursor: pointer;
        transition: all 0.25s ease; text-decoration: none;
    }
    .pill-cta:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(15,23,42,0.18);
        color: #fff;
    }
    .pill-cta.teal { background: var(--accent); }
    .pill-cta.teal:hover { box-shadow: 0 8px 24px rgba(13,148,136,0.25); }
    .pill-cta i { font-size: 0.65rem; transition: transform 0.2s; }
    .pill-cta:hover i { transform: translateX(3px); }
    .pill-wrap { display: flex; justify-content: center; margin-top: 1.5rem; }

    /* ── Section backgrounds disabled on homepage per user request ── */

    .aset-grid {
        display: grid;
        grid-template-columns: 38% 62%;
        gap: 2.5rem;
        align-items: center;
    }

    /* ── Image Collage (Left) ── */
    .aset-collage {
        position: relative;
        width: 100%;
        aspect-ratio: 4 / 3;
        max-height: 340px;
    }
    .aset-collage .img-main {
        position: absolute;
        inset: 0;
        border-radius: 24px;
        overflow: hidden;
        display: flex; align-items: center; justify-content: center;
        font-size: 4rem; color: rgba(255,255,255,0.3);
        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
    }
    .aset-collage .img-main img { width: 100%; height: 100%; object-fit: cover; }
    .aset-collage .img-secondary {
        position: absolute;
        bottom: -10%;
        right: -6%;
        width: 44%;
        aspect-ratio: 1;
        border-radius: 18px;
        overflow: hidden;
        border: 4px solid #fff;
        box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; color: rgba(255,255,255,0.3);
    }
    .aset-collage .img-secondary img { width: 100%; height: 100%; object-fit: cover; }

    /* ── Storytelling (Right) ── */
    .aset-story { padding: 0.5rem 0; }
    .aset-story .story-headline {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.2;
        color: var(--primary);
        margin-bottom: 1rem;
        letter-spacing: -0.02em;
    }
    .aset-story .story-headline .teal { color: var(--accent); }
    .aset-story .story-body {
        font-size: 0.88rem;
        color: var(--text);
        line-height: 1.8;
        margin-bottom: 0.75rem;
    }
    .aset-story .story-body .highlight {
        font-weight: 700;
        color: var(--primary);
    }
    .aset-story .story-body .italic {
        font-style: italic;
        color: var(--accent-dark);
    }

    @media (max-width: 900px) {
        .aset-grid { grid-template-columns: 1fr; gap: 1.5rem; }
        .aset-collage { max-height: 280px; }
        .aset-collage .img-secondary { width: 38%; bottom: -6%; right: -4%; }
        .aset-story .story-headline { font-size: 1.5rem; }
        .aset-story .story-body { font-size: 0.82rem; }
    }

    @media (max-width: 480px) {
        .aset-collage .img-secondary { width: 42%; bottom: -5%; right: -3%; }
    }

    .aset-cta-row {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
        margin-top: 1.25rem;
    }
    .aset-cta-row .pill-cta { flex-shrink: 0; }
    .aset-stats {
        display: flex;
        align-items: center;
        gap: 0;
    }
    .aset-stats .as-item {
        text-align: center;
        padding: 0 1.25rem;
    }
    .aset-stats .as-item + .as-item { border-left: 1px solid #e2e8f0; }
    .aset-stats .as-item .as-num {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.4rem;
        font-weight: 800;
        color: #F59E0B;
        line-height: 1;
        margin-bottom: 1px;
    }
    .aset-stats .as-item .as-lbl {
        font-size: 0.52rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #94a3b8;
    }

    @media (max-width: 640px) {
        .aset-cta-row { flex-direction: column; align-items: flex-start; }
        .aset-stats .as-item:first-child { padding-left: 0; }
    }

    .preview-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
    }
    @media (max-width: 900px) { .preview-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 600px) { .preview-grid { grid-template-columns: 1fr; } }

    .preview-card {
        background: #fff; border: 1px solid var(--border); border-radius: 18px;
        overflow: hidden; transition: all 0.3s ease; cursor: pointer;
    }
    .preview-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 36px rgba(0,0,0,0.07);
        border-color: #cbd5e1;
    }
    .preview-card .pc-img {
        width: 100%; aspect-ratio: 16 / 10;
        overflow: hidden; background: var(--bg);
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; color: #cbd5e1; position: relative;
    }
    .preview-card .pc-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
    .preview-card:hover .pc-img img { transform: scale(1.04); }
    .preview-card .pc-img .pc-badge {
        position: absolute; top: 10px; left: 10px;
        padding: 3px 10px; border-radius: 100px;
        font-size: 0.55rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.4px;
        background: rgba(255,255,255,0.85); backdrop-filter: blur(4px);
        color: var(--accent-dark);
    }
    .preview-card .pc-img .pc-badge i { font-size: 0.5rem; margin-right: 3px; }
    .preview-card .pc-body { padding: 1rem 1.1rem 1.1rem; }
    .preview-card .pc-body h4 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.92rem; font-weight: 700;
        color: var(--primary); margin-bottom: 3px; line-height: 1.3;
    }
    .preview-card .pc-body .pc-sub {
        font-size: 0.68rem; color: #64748b;
        display: flex; align-items: center; gap: 4px; margin-bottom: 0.4rem;
    }
    .preview-card .pc-body .pc-sub i { font-size: 0.55rem; color: var(--accent); }
    .preview-card .pc-body .pc-desc {
        font-size: 0.74rem; color: #64748b;
        line-height: 1.5; margin-bottom: 0.65rem;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .preview-card .pc-body .pc-action {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 0.35rem 0.9rem; border-radius: 100px;
        border: 1.5px solid var(--accent); background: #fff;
        font-size: 0.62rem; font-weight: 700; color: var(--accent);
        cursor: pointer; transition: all 0.2s;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .preview-card .pc-body .pc-action:hover {
        background: var(--accent); color: #fff;
        box-shadow: 0 2px 10px rgba(13,148,136,0.18);
    }

    .hero-wrap {
        position: relative;
        background: var(--primary) !important;
        background-image: linear-gradient(180deg, rgba(15,23,42,0.85) 0%, rgba(15,23,42,0.95) 100%) !important;
        background-size: cover !important;
        background-position: center !important;
        overflow: hidden; padding: 130px 1.5rem 120px;
    }
    .hero-wrap::before {
        content: ''; position: absolute; inset: 0;
        background:
            radial-gradient(ellipse at 30% 40%, rgba(13,148,136,0.12) 0%, transparent 60%),
            radial-gradient(ellipse at 70% 60%, rgba(217,119,6,0.06) 0%, transparent 50%);
    }
    .hero-pattern {
        position: absolute; inset: 0; opacity: 0.035;
        background-image:
            repeating-linear-gradient(0deg, transparent, transparent 40px, rgba(255,255,255,0.025) 40px, rgba(255,255,255,0.025) 41px),
            repeating-linear-gradient(90deg, transparent, transparent 40px, rgba(255,255,255,0.025) 40px, rgba(255,255,255,0.025) 41px);
    }
    .hero-inner {
        position: relative; z-index: 2;
        max-width: 1240px; margin: 0 auto;
        display: grid; grid-template-columns: 1fr auto; gap: 2rem;
        align-items: center;
    }
    .hero-left { max-width: 640px; }
    .hero-left .hero-badge {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 0.3rem 1rem; border-radius: 100px;
        font-size: 0.65rem; font-weight: 600; letter-spacing: 0.8px;
        text-transform: uppercase; margin-bottom: 1rem;
        border: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.6);
        background: rgba(255,255,255,0.04);
    }
    .hero-left h1 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 3.2rem; font-weight: 800;
        line-height: 1.08; color: #fff; margin-bottom: 0.6rem;
        letter-spacing: -0.02em;
    }
    .hero-left h1 span { color: var(--accent); }
    .hero-left p {
        font-size: 0.95rem; color: rgba(255,255,255,0.55);
        max-width: 520px; line-height: 1.8; margin-bottom: 1.5rem;
    }
    .hero-left .hero-cta {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 0.7rem 2rem; border-radius: 9999px; border: none;
        background: var(--accent); color: #fff;
        font-size: 0.75rem; font-weight: 700; letter-spacing: 1px;
        text-transform: uppercase; cursor: pointer;
        transition: all 0.25s ease;
    }
    .hero-left .hero-cta:hover {
        background: #0f766e;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(13,148,136,0.35);
    }
    .hero-left .hero-cta i { font-size: 0.65rem; }

    .hero-right .hero-stat-card {
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(16px) saturate(180%);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 20px;
        padding: 1.25rem 1.5rem;
        min-width: 260px;
    }
    .hero-stat-card .hs-item {
        display: flex; align-items: center; gap: 12px;
        padding: 0.7rem 0;
    }
    .hero-stat-card .hs-item + .hs-item { border-top: 1px solid rgba(255,255,255,0.06); }
    .hero-stat-card .hs-item .hs-icon {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem; color: #fff; flex-shrink: 0;
    }
    .hero-stat-card .hs-item .hs-info { flex: 1; }
    .hero-stat-card .hs-item .hs-info .hs-num {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.3rem; font-weight: 800; color: #fff; line-height: 1;
    }
    .hero-stat-card .hs-item .hs-info .hs-label {
        font-size: 0.55rem; font-weight: 700; letter-spacing: 1px;
        text-transform: uppercase; color: rgba(255,255,255,0.45);
    }

    .scroll-indicator {
        position: absolute; bottom: 2rem; left: 50%; transform: translateX(-50%);
        z-index: 2; display: flex; flex-direction: column; align-items: center; gap: 6px;
        color: rgba(255,255,255,0.25);
        font-size: 0.55rem; font-weight: 600; letter-spacing: 2px; text-transform: uppercase;
    }
    .scroll-indicator .arrow-down {
        width: 24px; height: 24px;
        border: 1.5px solid rgba(255,255,255,0.15); border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.5rem; animation: scrollBounce 2s ease infinite;
    }
    @keyframes scrollBounce { 0%,100% { transform: translateY(0); } 50% { transform: translateY(6px); } }

    @media (max-width: 900px) {
        .hero-inner { grid-template-columns: 1fr; }
        .hero-left h1 { font-size: 2.2rem; }
        .hero-wrap { padding: 110px 1rem 100px; }
        .hero-right .hero-stat-card { min-width: 0; }
    }

    .aset-editorial { }

    .pendidikan-header {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 2rem;
        align-items: start;
        margin-bottom: 1.75rem;
    }
    @media (max-width: 768px) {
        .pendidikan-header { grid-template-columns: 1fr; }
    }
    .pendidikan-left { max-width: 560px; }
    .pendidikan-badge {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 0.6rem; font-weight: 700; letter-spacing: 1.2px;
        text-transform: uppercase; color: #0D9488; margin-bottom: 0.5rem;
    }
    .pendidikan-badge i { font-size: 0.65rem; }
    .pendidikan-left h2 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.5rem; font-weight: 800;
        color: #0F172A; line-height: 1.15; margin-bottom: 0.4rem;
    }
    .pendidikan-left h2 .teal { color: #0D9488; }
    .pendidikan-left p {
        font-size: 0.82rem; color: #64748b;
        line-height: 1.7; margin: 0;
    }
    .pendidikan-stats { flex-shrink: 0; }
    .stats-card {
        background: #fff; border-radius: 16px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04), 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid #f1f5f9;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0;
    }
    .stat-item { text-align: center; padding: 0 1rem; }
    .stat-item:first-child { padding-left: 0; }
    .stat-item:last-child { padding-right: 0; }
    .stat-divider { width: 1px; height: 36px; background: #e2e8f0; flex-shrink: 0; }
    .stat-number {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.25rem; font-weight: 800;
        color: #F59E0B; line-height: 1.1;
    }
    .stat-label {
        font-size: 0.58rem; font-weight: 700;
        letter-spacing: 0.8px; color: #64748b;
        margin-top: 2px; text-transform: uppercase;
    }

    .aset-grid {
        display: grid;
        grid-template-columns: 38% 62%;
        gap: 2.5rem;
        align-items: center;
    }

    /* ── Image Collage (Left) ── */
    .aset-collage {
        position: relative;
        width: 100%;
        aspect-ratio: 4 / 3;
        max-height: 340px;
    }
    .aset-collage .img-main {
        position: absolute;
        inset: 0;
        border-radius: 24px;
        overflow: hidden;
        display: flex; align-items: center; justify-content: center;
        font-size: 4rem; color: rgba(255,255,255,0.3);
        box-shadow: 0 8px 32px rgba(0,0,0,0.08);
    }
    .aset-collage .img-main img { width: 100%; height: 100%; object-fit: cover; }
    .aset-collage .img-secondary {
        position: absolute;
        bottom: -10%;
        right: -6%;
        width: 44%;
        aspect-ratio: 1;
        border-radius: 18px;
        overflow: hidden;
        border: 4px solid #fff;
        box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; color: rgba(255,255,255,0.3);
    }
    .aset-collage .img-secondary img { width: 100%; height: 100%; object-fit: cover; }

    /* ── Storytelling (Right) ── */
    .aset-story { padding: 0.5rem 0; }
    .aset-story .story-headline {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.2;
        color: var(--primary);
        margin-bottom: 1rem;
        letter-spacing: -0.02em;
    }
    .aset-story .story-headline .teal { color: var(--accent); }
    .aset-story .story-body {
        font-size: 0.88rem;
        color: var(--text);
        line-height: 1.8;
        margin-bottom: 0.75rem;
    }
    .aset-story .story-body .highlight {
        font-weight: 700;
        color: var(--primary);
    }
    .aset-story .story-body .italic {
        font-style: italic;
        color: var(--accent-dark);
    }

    @media (max-width: 900px) {
        .aset-grid { grid-template-columns: 1fr; gap: 1.5rem; }
        .aset-collage { max-height: 280px; }
        .aset-collage .img-secondary { width: 38%; bottom: -6%; right: -4%; }
        .aset-story .story-headline { font-size: 1.5rem; }
        .aset-story .story-body { font-size: 0.82rem; }
    }

    @media (max-width: 480px) {
        .aset-collage .img-secondary { width: 42%; bottom: -5%; right: -3%; }
    }

    .aset-cta-row {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
        margin-top: 1.25rem;
    }
    .aset-cta-row .pill-cta { flex-shrink: 0; }
    .aset-stats {
        display: flex;
        align-items: center;
        gap: 0;
    }
    .aset-stats .as-item {
        text-align: center;
        padding: 0 1.25rem;
    }
    .aset-stats .as-item + .as-item { border-left: 1px solid #e2e8f0; }
    .aset-stats .as-item .as-num {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.4rem;
        font-weight: 800;
        color: #F59E0B;
        line-height: 1;
        margin-bottom: 1px;
    }
    .aset-stats .as-item .as-lbl {
        font-size: 0.52rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #94a3b8;
    }

    @media (max-width: 640px) {
        .aset-cta-row { flex-direction: column; align-items: flex-start; }
        .aset-stats .as-item:first-child { padding-left: 0; }
    }

    .map-preview-wrap {
        width: 100%; height: 480px;
        border-radius: 18px; overflow: hidden;
        border: 1px solid var(--border); position: relative;
    }
    .map-preview-wrap #homeMap { width: 100%; height: 100%; position: absolute; inset: 0; }
    @media (max-width: 768px) { .map-preview-wrap { height: 340px; } }

</style>
@endpush

@section('hero')
<!-- ════════════════════════════════════════════════════
     SECTION 1: HERO BANNER & STATISTIK RINGKAS DESA (ISOLATED)
     ════════════════════════════════════════════════════ -->
<section id="sec-beranda" class="hero-wrap"
    style="background-image:linear-gradient(180deg, rgba(15,23,42,0.85) 0%, rgba(15,23,42,0.95) 100%){{ $heroBg ? ', url(' . str_replace("'", '%27', str_starts_with($heroBg, 'http') ? $heroBg : '/images/' . ltrim($heroBg, '/')) . '?v=' . $heroTs . ')' : '' }} !important;">
    <div class="hero-pattern"></div>
    <div class="hero-inner">
        <div class="hero-left reveal">
            <h1><span>Fasilitas</span> Umum Desa Nekmese</h1>
            <p>Sistem Informasi Geografis fasilitas umum, Desa Nekmese Kecamatan Amarasi Selatan, Kabupaten Kupang, Nusa Tenggara Timur.</p>
            <a href="#sec-aset-desa" class="hero-cta">Jelajahi Aset <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="hero-right reveal reveal-delay-1">
            <div class="hero-stat-card">
                <div class="hs-item">
                    <div class="hs-icon" style="background:var(--accent);"><i class="fas fa-building"></i></div>
                    <div class="hs-info">
                        <div class="hs-num stat-counter" data-target="{{ $facilities->count() }}">0</div>
                        <div class="hs-label">Total Fasilitas</div>
                    </div>
                </div>
                <div class="hs-item">
                    <div class="hs-icon" style="background:var(--gold);"><i class="fas fa-tag"></i></div>
                    <div class="hs-info">
                        <div class="hs-num stat-counter" data-target="{{ count($uniqueKategoris) }}">0</div>
                        <div class="hs-label">Jenis Fasilitas</div>
                    </div>
                </div>
                <div class="hs-item">
                    <div class="hs-icon" style="background:#D97706;"><i class="fas fa-layer-group"></i></div>
                    <div class="hs-info">
                        <div class="hs-num stat-counter" data-target="{{ count($sektorTotals) }}">0</div>
                        <div class="hs-label">Sektor Layanan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('content')
<!-- ════════════════════════════════════════════════════
     SECTION 2: ASET DESA
     ════════════════════════════════════════════════════ -->
<section id="sec-aset-desa" class="hp-section aset-editorial">
        <div class="sec-badge reveal"><i class="fas fa-landmark"></i> Aset Pemerintah Desa Nekmese</div>
        <div class="sec-head reveal reveal-delay-1" style="margin-bottom:1.5rem;">
            <div class="sec-head-left">
                <div class="gold-line"></div>
                <h2>Aset <span>Pemerintah</span></h2>
                <p>Seluruh aset milik Desa Nekmese yang tercatat dan dikelola secara akuntabel untuk pelayanan masyarakat</p>
            </div>
        </div>

        <div class="aset-grid">
            <div class="aset-collage reveal reveal-delay-1">
                <div class="img-main" style="background:linear-gradient(145deg,#0D9488,#0F766E);">
                    @if($asetMainImage)
                    <img src="/images/{{ ltrim($asetMainImage, '/') }}" alt="Aset Utama" class="w-full h-full object-cover">
                    @else
                    <i class="fas fa-building"></i>
                    @endif
                </div>
                <div class="img-secondary" style="background:linear-gradient(145deg,#14B8A6,#0F766E);">
                    @if($asetSubImage)
                    <img src="/images/{{ ltrim($asetSubImage, '/') }}" alt="Aset Pendukung" class="w-full h-full object-cover">
                    @else
                    <i class="fas fa-truck"></i>
                    @endif
                </div>
            </div>
            <div class="aset-story reveal reveal-delay-2">
                <div class="story-headline">
                    Pengelolaan Aset untuk <span class="teal">Pelayanan Desa.</span>
                </div>
                <p class="story-body">
                    Desa Nekmese mencatat dan mengelola seluruh aset milik desa secara akuntabel, mulai dari gedung pemerintahan, balai pertemuan, hingga sarana pelayanan publik untuk mendukung efektivitas tata kelola pemerintahan desa.
                </p>

                <div class="aset-cta-row reveal reveal-delay-3">
                    <a href="/aset-desa" class="pill-cta" style="margin:0;">Jelajahi Aset Desa <i class="fas fa-arrow-right"></i></a>
                    <div class="aset-stats">
                        <div class="as-item">
                            <div class="as-num">{{ $totalAset ?? 0 }}+</div>
                            <div class="as-lbl">Aset Terdata</div>
                        </div>
                        <div class="as-item">
                            <div class="as-num">{{ $persenTerintegrasi ?? 100 }}%</div>
                            <div class="as-lbl">Terintegrasi</div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════════════════════════════
     SECTION 3: PENDIDIKAN
     ════════════════════════════════════════════════════ -->
<section id="sec-pendidikan" class="hp-section">
    <div class="pendidikan-header">
        <div class="pendidikan-left">
            <div class="pendidikan-badge reveal"><i class="fas fa-school"></i> Sarana Belajar & Generasi Desa</div>
            <h2 class="reveal reveal-delay-1">Fasilitas Pendidikan <span class="teal">Nekmese</span></h2>
            <p class="reveal reveal-delay-1">Menyediakan sarana dan prasarana pendidikan yang kondusif untuk mendukung tumbuh kembang, pengetahuan, dan masa depan anak-anak serta generasi muda Desa Nekmese.</p>
        </div>
        <div class="pendidikan-stats reveal reveal-delay-2">
            <div class="stats-card">
                <div class="stat-item">
                    <div class="stat-number">{{ $totalSD ?? 0 }}</div>
                    <div class="stat-label">SD</div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <div class="stat-number">{{ $totalSMP ?? 0 }}</div>
                    <div class="stat-label">SMP</div>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <div class="stat-number">{{ $totalSMA ?? 0 }}</div>
                    <div class="stat-label">SMA / SMK</div>
                </div>
            </div>
        </div>
    </div>

    @if($pendidikanFacilities->count() > 0)
    <div class="preview-grid">
        @foreach($pendidikanFacilities as $f)
        <div class="preview-card reveal reveal-delay-{{ $loop->iteration }}" onclick="openModal({{ $f->id }})">
            <div class="pc-img">
                    @if($f->photos && $f->photos->isNotEmpty())
                        <img src="{{ $f->photos->first()->photo_url }}" alt="{{ $f->nama }}">
                    @else
                        <i class="fas fa-school"></i>
                    @endif
                    <span class="pc-badge"><i class="fas fa-building"></i> Fas. Publik</span>
                </div>
                <div class="pc-body">
                    <h4>{{ $f->nama }}</h4>
                    <div class="pc-sub"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($f->alamat ?? 'Desa Nekmese', 40) }}</div>
                    <div class="pc-desc">{{ $f->deskripsi ?? 'Fasilitas pendidikan di Desa Nekmese.' }}</div>
                    <button class="pc-action" onclick="event.stopPropagation();openModal({{ $f->id }})"><i class="fas fa-info-circle"></i> Detail</button>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align:center;padding:2rem;color:#94a3b8;"><i class="fas fa-inbox" style="font-size:1.5rem;margin-bottom:0.5rem;opacity:0.4;"></i><p style="font-size:0.82rem;">Belum ada data fasilitas pendidikan.</p></div>
        @endif

        <div class="pill-wrap reveal">
            <a href="/pendidikan" class="pill-cta">Jelajahi Semua Fasilitas Pendidikan <i class="fas fa-arrow-right"></i></a>
        </div>
</section>

<!-- ════════════════════════════════════════════════════
     SECTION 4: KESEHATAN
     ════════════════════════════════════════════════════ -->
<section id="sec-kesehatan" class="hp-section">
        <div class="sec-badge reveal"><i class="fas fa-heartbeat"></i> Layanan Sehat</div>
        <div class="sec-head reveal reveal-delay-1">
            <div class="sec-head-left">
                <div class="gold-line"></div>
                <h2>Fasilitas <span>Kesehatan</span></h2>
                <p>Sarana kesehatan yang melayani kebutuhan medis masyarakat Desa Nekmese dan sekitarnya</p>
            </div>
        </div>
        @if($kesehatanFacilities->count() > 0)
        <div class="preview-grid">
            @foreach($kesehatanFacilities as $f)
            <div class="preview-card reveal reveal-delay-{{ $loop->iteration }}" onclick="openModal({{ $f->id }})">
                <div class="pc-img">
                    @if($f->photos && $f->photos->isNotEmpty())
                        <img src="{{ $f->photos->first()->photo_url }}" alt="{{ $f->nama }}">
                    @else
                        <i class="fas fa-hospital-alt"></i>
                    @endif
                    <span class="pc-badge"><i class="fas fa-landmark"></i> Aset Desa</span>
                </div>
                <div class="pc-body">
                    <h4>{{ $f->nama }}</h4>
                    <div class="pc-sub"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($f->alamat ?? 'Desa Nekmese', 40) }}</div>
                    <div class="pc-desc">{{ $f->deskripsi ?? 'Fasilitas kesehatan di Desa Nekmese.' }}</div>
                    <button class="pc-action" onclick="event.stopPropagation();openModal({{ $f->id }})"><i class="fas fa-info-circle"></i> Detail</button>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align:center;padding:2rem;color:#94a3b8;"><i class="fas fa-inbox" style="font-size:1.5rem;margin-bottom:0.5rem;opacity:0.4;"></i><p style="font-size:0.82rem;">Belum ada data fasilitas kesehatan.</p></div>
        @endif
        <div class="pill-wrap reveal">
            <a href="/kesehatan" class="pill-cta">Lihat Semua Fasilitas Kesehatan <i class="fas fa-arrow-right"></i></a>
        </div>
</section>

<!-- ════════════════════════════════════════════════════
     SECTION 5: TEMPAT IBADAH
     ════════════════════════════════════════════════════ -->
<section id="sec-ibadah" class="hp-section">
        <div class="sec-badge reveal"><i class="fas fa-place-of-worship"></i> Rumah Ibadah</div>
        <div class="sec-head reveal reveal-delay-1">
            <div class="sec-head-left">
                <div class="gold-line"></div>
                <h2>Tempat <span>Ibadah</span></h2>
                <p>Sarana peribadatan yang tersedia untuk masyarakat Desa Nekmese dalam menjalankan ibadah</p>
            </div>
        </div>
        @if($ibadahFacilities->count() > 0)
        <div class="preview-grid">
            @foreach($ibadahFacilities as $f)
            <div class="preview-card reveal reveal-delay-{{ $loop->iteration }}" onclick="openModal({{ $f->id }})">
                <div class="pc-img">
                    @if($f->photos && $f->photos->isNotEmpty())
                        <img src="{{ $f->photos->first()->photo_url }}" alt="{{ $f->nama }}">
                    @else
                        @switch($f->kategori)
                            @case('gereja') <i class="fas fa-church"></i> @break
                            @case('tempat_ibadah') <i class="fas fa-mosque"></i> @break
                            @default <i class="fas fa-place-of-worship"></i>
                        @endswitch
                    @endif
                    <span class="pc-badge"><i class="fas fa-building"></i> Fas. Publik</span>
                </div>
                <div class="pc-body">
                    <h4>{{ $f->nama }}</h4>
                    <div class="pc-sub"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($f->alamat ?? 'Desa Nekmese', 40) }}</div>
                    <div class="pc-desc">{{ $f->deskripsi ?? 'Tempat ibadah di Desa Nekmese.' }}</div>
                    <button class="pc-action" onclick="event.stopPropagation();openModal({{ $f->id }})"><i class="fas fa-info-circle"></i> Detail</button>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align:center;padding:2rem;color:#94a3b8;"><i class="fas fa-inbox" style="font-size:1.5rem;margin-bottom:0.5rem;opacity:0.4;"></i><p style="font-size:0.82rem;">Belum ada data tempat ibadah.</p></div>
        @endif
        <div class="pill-wrap reveal">
            <a href="/ibadah" class="pill-cta teal">Lihat Semua Tempat Ibadah <i class="fas fa-arrow-right"></i></a>
        </div>
</section>


<!-- ════════════════════════════════════════════════════
     SECTION 7: PETA INTERAKTIF DESA (formerly section 6)
     ════════════════════════════════════════════════════ -->
<section id="peta-desa" class="hp-section" style="padding-bottom:0;">
        <div class="sec-badge reveal"><i class="fas fa-map-marked-alt"></i> Peta Interaktif</div>
        <div class="sec-head reveal reveal-delay-1">
            <div class="sec-head-left">
                <div class="gold-line"></div>
                <h2>Peta <span>Desa Nekmese</span></h2>
                <p>Visualisasi interaktif seluruh fasilitas dan aset desa dalam satu peta</p>
            </div>
        </div>
        <div class="map-preview-wrap reveal reveal-delay-2">
            <div id="homeMap"></div>
        </div>
</section>

<div id="facilityModal" class="fmx" onclick="if(event.target===this)closeModal()">
    <div class="fmx-box">

        <!-- Header Modal -->
        <div class="fmx-head">
            <div class="fmx-title-row">
                <span id="modalIcon" class="fmx-ic" style="display:none;"></span>
                <h3 id="modalTitle" class="fmx-title">Detail Fasilitas</h3>
            </div>
            <button type="button" onclick="closeModal()" class="fmx-close" aria-label="Tutup">&times;</button>
        </div>

        <!-- Body -->
        <div class="fmx-scroll">

            <!-- Image Carousel -->
            <div id="carouselContainer" class="carousel-wrap">
                <div id="carouselSlides" class="carousel-track"></div>
                <button id="prevBtn" type="button" onclick="moveSlide(-1)" class="carArrow prev" aria-label="Sebelumnya"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 19l-7-7 7-7"/></svg></button>
                <button id="nextBtn" type="button" onclick="moveSlide(1)" class="carArrow next" aria-label="Berikutnya"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg></button>
                <div id="carouselDots" class="carDots"></div>
            </div>
            <div id="modalPlaceholder" class="placeholderBox"><i class="fas fa-image"></i></div>

            <!-- Informasi -->
            <div class="fmx-body">
                <div class="fmx-tags" id="modalTags"></div>

                <div class="fmx-section">
                    <span class="fmx-label">Alamat</span>
                    <p id="modalAddress" class="fmx-value">-</p>
                </div>

                <div class="fmx-section">
                    <span class="fmx-label">Deskripsi</span>
                    <p id="modalDesc" class="fmx-value">-</p>
                </div>

                <div class="f-grid">
                    <div>
                        <span class="fmx-label">Sektor</span>
                        <span id="modalSektor" class="fmx-value">-</span>
                    </div>
                    <div>
                        <span class="fmx-label">Koordinat</span>
                        <span id="modalKoordinat" class="fmx-value">-</span>
                    </div>
                    <div class="span2">
                        <span class="fmx-label">Jam Layanan</span>
                        <span id="modalJam" class="fmx-value">Senin - Jumat, 08:00 - 16:00 WITA</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="fmx-foot">
            <a id="modalCta" href="#" target="_blank" rel="noopener" class="btnPrimary"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg> Buka Petunjuk Arah</a>
            <button type="button" onclick="closeModal()" class="btnGhost">Tutup</button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .fmx {
        display: none;
        position: fixed; inset: 0; z-index: 3000;
        background: rgba(15,23,42,0.55);
        backdrop-filter: blur(8px);
        padding: 1.25rem;
        overflow-y: auto;
    }
    .fmx.open { display: flex; align-items: center; justify-content: center; }
    .fmx-box {
        background: #fff; border-radius: 20px;
        max-width: 640px; width: 100%;
        max-height: 90vh; overflow: hidden;
        box-shadow: 0 24px 80px rgba(0,0,0,0.25);
        display: flex; flex-direction: column;
    }
    .fmx-head {
        display: flex; align-items: center; justify-content: space-between;
        gap: 10px; padding: 16px 24px;
        border-bottom: 1px solid #eef2f7; flex-shrink: 0;
    }
    .fmx-title-row { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .fmx-ic {
        width: 34px; height: 34px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        color: #fff; font-size: 0.85rem; flex-shrink: 0;
    }
    .fmx-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.12rem; font-weight: 800; color: var(--primary);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .fmx-close {
        border: none; background: transparent; color: #94a3b8;
        font-size: 1.6rem; line-height: 1; cursor: pointer;
        padding: 4px 8px; border-radius: 50%; flex-shrink: 0;
        transition: background 0.2s, color 0.2s;
    }
    .fmx-close:hover { background: #f1f5f9; color: #475569; }
    .fmx-scroll { overflow-y: auto; flex: 1; }

    .carousel-wrap {
        position: relative; width: 100%; height: 260px;
        background: #e2e8f0; display: none;
    }
    .carousel-wrap.show { display: block; }
    .carousel-track { display: flex; height: 100%; transition: transform 0.3s ease; }
    .carousel-track img { width: 100%; height: 100%; object-fit: cover; flex-shrink: 0; }
    .carArrow {
        position: absolute; top: 50%; transform: translateY(-50%);
        width: 34px; height: 34px; border-radius: 50%;
        background: rgba(0,0,0,0.5); border: none; color: #fff;
        display: none; align-items: center; justify-content: center;
        cursor: pointer; transition: background 0.2s; z-index: 5;
    }
    .carArrow.show { display: flex; }
    .carArrow:hover { background: rgba(0,0,0,0.65); }
    .carArrow.prev { left: 12px; }
    .carArrow.next { right: 12px; }
    .carDots { position: absolute; bottom: 12px; left: 50%; transform: translateX(-50%); display: flex; gap: 6px; z-index: 5; }
    .carDot { width: 8px; height: 8px; border-radius: 50%; background: rgba(255,255,255,0.5); border: none; padding: 0; cursor: pointer; }
    .carDot.active { background: #fff; }

    .placeholderBox {
        width: 100%; height: 260px; background: #e2e8f0;
        display: none; align-items: center; justify-content: center;
        font-size: 3rem; color: #cbd5e1;
    }
    .placeholderBox.show { display: flex; }

    .fmx-body { padding: 24px; }
    .fmx-tags { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px; }
    .tagPill { font-size: 0.66rem; font-weight: 700; padding: 4px 12px; border-radius: 100px; letter-spacing: 0.4px; text-transform: uppercase; }
    .tagPill.aset { background: #ccfbf1; color: #0f766e; }
    .tagPill.publik { background: #e2e8f0; color: #475569; }
    .tagPill.kategori { background: rgba(13,148,136,0.08); color: var(--accent); border: 1px solid rgba(13,148,136,0.15); }
    .fmx-section { margin-bottom: 16px; }
    .fmx-label {
        display: block; font-weight: 700; font-size: 0.66rem;
        letter-spacing: 0.08em; text-transform: uppercase;
        color: #94a3b8; margin-bottom: 4px;
    }
    .fmx-value { font-size: 0.9rem; line-height: 1.6; color: #475569; white-space: pre-line; }
    .f-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; border-top: 1px solid #eef2f7; padding-top: 14px; }
    .f-grid .span2 { grid-column: 1 / -1; }

    .fmx-foot {
        padding: 16px 24px; background: #f8fafc;
        border-top: 1px solid #eef2f7;
        display: flex; align-items: center; justify-content: space-between;
        gap: 12px; flex-shrink: 0;
    }
    .btnPrimary {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 8px 16px; border-radius: 12px;
        background: var(--accent); color: #fff; font-weight: 700;
        font-size: 0.8rem; font-family: 'Plus Jakarta Sans', sans-serif;
        text-decoration: none; transition: all 0.25s;
    }
    .btnPrimary:hover { background: #0f766e; box-shadow: 0 4px 20px rgba(13,148,136,0.3); }
    .btnGhost {
        padding: 8px 16px; border-radius: 12px;
        background: #fff; border: 1px solid #dbe1e8;
        color: #64748b; font-weight: 600; font-size: 0.8rem;
        cursor: pointer; transition: all 0.2s;
    }
    .btnGhost:hover { background: #f1f5f9; }
    .popup-detail-btn {
        margin-bottom: 0.75rem;
        padding: 0.3rem 0.8rem;
        background: #0D9488;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 0.68rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    .popup-detail-btn:hover { background: #0f766e; }
    @media (max-width: 600px) {
        .fmx-box { border-radius: 14px; }
        .f-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@push('scripts')
<script>
    var map, facilities, kategoriList, jenisList, markerMap = {};
    var slideIdx = 0, slideTotal = 0;

    function getIconForCategory(kat) {
        var icons = { 'kantor_desa': 'fa-landmark', 'sekolah': 'fa-school', 'gereja': 'fa-church', 'posyandu': 'fa-hospital-alt', 'lapangan': 'fa-futbol', 'balai_desa': 'fa-home', 'tempat_ibadah': 'fa-mosque' };
        return icons[kat] || 'fa-map-pin';
    }

document.addEventListener('DOMContentLoaded', function () {
    map = L.map('homeMap', {
        center: [-10.193000, 123.715000],
        zoom: 15,
        zoomControl: true,
    });

    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community',
        maxZoom: 18,
    }).addTo(map);

    facilities = @json($facilities);
    kategoriList = @json($kategoriList);
    jenisList = @json($jenisList);

    if (typeof facilities !== 'undefined' && Array.isArray(facilities)) {
        facilities.forEach(f => {
            const lat = parseFloat(f.latitude);
            const lng = parseFloat(f.longitude);

            if (isNaN(lat) || isNaN(lng) || lat === 0 || lng === 0) return;

            const bgClass = f.kategori === 'lapangan' ? '#F59E0B' : '#0D9488';

            const customIcon = L.divIcon({
                className: 'custom-pin',
                html: `<div style="width:32px;height:32px;background:${bgClass};color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,0.3);border:2px solid #fff;">` +
                    `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">` +
                    `<path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 16],
            });

            const marker = L.marker([lat, lng], { icon: customIcon })
                .addTo(map)
                .bindPopup(
                    '<div style="padding:0;text-align:center;">' +
                    '<h4 style="margin:0.75rem 0 2px;font-size:0.85rem;font-weight:700;color:#1e293b;">' + f.nama + '</h4>' +
                    (f.alamat ? '<p style="margin:0 0 0.5rem;font-size:0.68rem;color:#64748b;">' + f.alamat + '</p>' : '') +
                    '<button type="button" data-detail-id="' + f.id + '" class="popup-detail-btn" onclick="event.stopPropagation();window.openModal(' + f.id + ')">Lihat Detail</button>' +
                    '</div>',
                    { maxWidth: 260, className: 'custom-popup' }
                );

            marker._facilityId = f.id;
            markerMap[f.id] = marker;
        });
    }

    setTimeout(function () {
        map.invalidateSize();
    }, 300);
});

    function openModal(id) {
        var f = facilities.find(function(item) { return item.id === id; });
        if (!f) return;

        // Title
        document.getElementById('modalTitle').textContent = f.nama || 'Detail Fasilitas';

        // Icon
        var iconEl = document.getElementById('modalIcon');
        var bgClass = f.kategori === 'lapangan' ? '#F59E0B' : '#0D9488';
        iconEl.style.display = 'inline-flex';
        iconEl.style.background = bgClass;
        iconEl.innerHTML = '<i class="fas ' + getIconForCategory(f.kategori) + '"></i>';

        // Tags
        var jenis = jenisList[f.kategori] || 'fasilitas_publik';
        var jenisLabel = jenis === 'aset_desa' ? 'Aset Desa' : 'Fasilitas Publik';
        var kategoriLabel = kategoriList[f.kategori] || f.kategori;
        document.getElementById('modalTags').innerHTML =
            '<span class="tagPill ' + (jenis === 'aset_desa' ? 'aset' : 'publik') + '">' + jenisLabel + '</span>' +
            '<span class="tagPill kategori">' + kategoriLabel + '</span>';

        // Address
        document.getElementById('modalAddress').textContent = f.alamat || 'Desa Nekmese, Kec. Amarasi Selatan';

        // Description
        document.getElementById('modalDesc').textContent = f.deskripsi || 'Belum ada deskripsi untuk fasilitas ini.';

        // Info grid
        var sektor = @json($sektorList);
        var sektorName = sektor[f.kategori] || 'Fasilitas Umum';
        document.getElementById('modalSektor').textContent = sektorName;
        document.getElementById('modalKoordinat').textContent = f.latitude.toFixed(5) + ', ' + f.longitude.toFixed(5);
        document.getElementById('modalJam').textContent = 'Senin - Jumat, 08:00 - 16:00 WITA';

        // Google Maps CTA
        var navLink = 'https://www.google.com/maps/dir/?api=1&destination=' + f.latitude + ',' + f.longitude;
        document.getElementById('modalCta').href = navLink;

        // Carousel
        var container = document.getElementById('carouselContainer');
        var slidesEl = document.getElementById('carouselSlides');
        var dotsEl = document.getElementById('carouselDots');
        var placeholder = document.getElementById('modalPlaceholder');
        var prevBtn = document.getElementById('prevBtn');
        var nextBtn = document.getElementById('nextBtn');

        slidesEl.innerHTML = '';
        dotsEl.innerHTML = '';
        slideIdx = 0;

        var photos = f.photos || [];
        var validPhotos = photos.filter(function(p) { return p.photo_url; });

        if (validPhotos.length > 0) {
            container.classList.add('show');
            placeholder.classList.remove('show');
            slideTotal = validPhotos.length;

            validPhotos.forEach(function(p, idx) {
                var img = document.createElement('img');
                img.src = p.photo_url;
                img.className = '';
                img.alt = '';
                slidesEl.appendChild(img);

                var dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'carDot' + (idx === 0 ? ' active' : '');
                dot.setAttribute('data-index', idx);
                dot.onclick = function() { goToSlide(parseInt(this.getAttribute('data-index'))); };
                dotsEl.appendChild(dot);
            });

            prevBtn.classList.toggle('show', slideTotal > 1);
            nextBtn.classList.toggle('show', slideTotal > 1);
        } else {
            container.classList.remove('show');
            placeholder.classList.add('show');
            placeholder.innerHTML = '<i class="fas ' + getIconForCategory(f.kategori) + '"></i>';
        }

        document.getElementById('facilityModal').classList.add('open');
        document.body.style.overflow = 'hidden';
        focusMarker(id);
    }

    function focusMarker(id) {
        var marker = markerMap[id];
        if (marker) {
            map.setView(marker.getLatLng(), 17);
            marker.openPopup();
        }
    }

    function closeModal() {
        document.getElementById('facilityModal').classList.remove('open');
        document.body.style.overflow = '';
    }

    function moveSlide(dir) {
        goToSlide((slideIdx + dir + slideTotal) % slideTotal);
    }

    function goToSlide(idx) {
        slideIdx = idx;
        document.getElementById('carouselSlides').style.transform = 'translateX(-' + (idx * 100) + '%)';
        var dots = document.getElementById('carouselDots').children;
        Array.from(dots).forEach(function(d, i) {
            d.className = 'carDot' + (i === idx ? ' active' : '');
        });
    }

    window.openModal = function (id) { openModal(id); };
    window.openDetailModal = function (data) {
        if (typeof data === 'string') { try { data = JSON.parse(data); } catch (e) {} }
        if (data && typeof data === 'object' && 'id' in data) { openModal(data.id); }
        else if (typeof data === 'number') { openModal(data); }
    };

    document.addEventListener('click', function (e) {
        var btn = e.target && e.target.closest ? e.target.closest('.popup-detail-btn') : null;
        if (btn && btn.getAttribute('data-detail-id')) {
            e.preventDefault();
            e.stopPropagation();
            openModal(parseInt(btn.getAttribute('data-detail-id'), 10));
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });

    var countersAnimated = false;
    var counterObserver = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting && !countersAnimated) {
                countersAnimated = true;
                animateCounters();
                counterObserver.disconnect();
            }
        });
    }, { threshold: 0.3 });
    var heroStats = document.querySelector('.hero-stat-card');
    if (heroStats) counterObserver.observe(heroStats);

    function animateCounters() {
        var counters = document.querySelectorAll('.stat-counter[data-target]');
        counters.forEach(function(el) {
            var target = parseInt(el.getAttribute('data-target'));
            if (isNaN(target) || target === 0) { el.textContent = '0'; return; }
            var duration = 1600;
            var startTime = null;
            function step(timestamp) {
                if (!startTime) startTime = timestamp;
                var progress = Math.min((timestamp - startTime) / duration, 1);
                var eased = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.round(eased * target);
                if (progress < 1) requestAnimationFrame(step);
                else el.textContent = target;
            }
            requestAnimationFrame(step);
        });
    }

    function showToast(msg) {
        var t = document.getElementById('toast');
        if (!t) return;
        t.textContent = msg;
        t.classList.add('show');
        clearTimeout(t._timer);
        t._timer = setTimeout(function() { t.classList.remove('show'); }, 2500);
    }

    var style = document.createElement('style');
    style.textContent = `
        .custom-pin { background: none !important; border: none !important; }
        .leaflet-popup-content-wrapper { border-radius: 12px !important; overflow: hidden; box-shadow: 0 8px 30px rgba(0,0,0,0.15) !important; }
        .leaflet-popup-content { margin: 0 !important; }
        .custom-popup .leaflet-popup-tip { box-shadow: 0 4px 14px rgba(0,0,0,0.1) !important; }
        .leaflet-popup-close-button { top: 8px !important; right: 8px !important; width: 24px !important; height: 24px !important; background: rgba(0,0,0,0.45) !important; color: #fff !important; border-radius: 50% !important; font-size: 12px !important; line-height: 24px !important; display: flex !important; align-items: center !important; justify-content: center !important; }
        .leaflet-popup-close-button:hover { background: rgba(0,0,0,0.7) !important; }
        .leaflet-marker-icon .custom-pin div { transition: transform 0.2s; }
        .leaflet-marker-icon:hover .custom-pin div { transform: scale(1.15); }
        .marker-pulse { animation: markerPing 0.8s ease-out 2; }
        @keyframes markerPing {
            0% { box-shadow: 0 0 0 0 rgba(13,148,136,0.5); }
            70% { box-shadow: 0 0 0 14px rgba(13,148,136,0); }
            100% { box-shadow: 0 0 0 0 rgba(13,148,136,0); }
        }`;

    document.head.appendChild(style);

    function scrollToPeta() {
        var el = document.getElementById('peta-desa');
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (window.location.hash === '#peta-desa') {
            setTimeout(scrollToPeta, 400);
        }
        setTimeout(function() { map.invalidateSize(); }, 300);
    });

    document.querySelectorAll('a[href="#peta-desa"]').forEach(function(a) {
        a.addEventListener('click', function(e) {
            if (window.location.pathname === '/' || window.location.pathname === '') {
                e.preventDefault();
                scrollToPeta();
            }
        });
    });
</script>
@endpush
