@extends('layouts.app')

@section('title', 'Desa Nekmese - ' . $pageTitle)

@php
    $badgeText = '';
    $badgeIcon = '';
    switch ($currentFilter) {
        case 'aset_desa':
            $badgeText = 'ASET DAN KEKAYAAN DESA';
            $badgeIcon = 'fa-landmark';
            break;
        case 'Pendidikan':
            $badgeText = 'SARANA PENDIDIKAN';
            $badgeIcon = 'fa-school';
            break;
        case 'Kesehatan':
            $badgeText = 'LAYANAN KESEHATAN';
            $badgeIcon = 'fa-heartbeat';
            break;
        case 'Tempat Ibadah':
            $badgeText = 'TEMPAT IBADAH';
            $badgeIcon = 'fa-place-of-worship';
            break;
        default:
            $badgeText = 'FASILITAS UMUM';
            $badgeIcon = 'fa-building';
    }

    $uniqueKategoris = $facilities->pluck('kategori')->unique()->values();
    $statCount1 = $facilities->count();
    $statLabel1 = $currentFilter === 'aset_desa' ? 'ASET DESA' : 'FASILITAS';
    $statCount2 = $uniqueKategoris->count();
    $statLabel2 = 'SUB-KATEGORI';
    $asetCount = $facilities->filter(fn($f) => ($jenisList[$f->kategori] ?? '') === 'aset_desa')->count();
    $publikCount = $statCount1 - $asetCount;
    if ($asetCount > 0 && $publikCount > 0) {
        $statCount2 = $asetCount;
        $statLabel2 = 'ASET DESA';
        $statCount3 = $publikCount;
        $statLabel3 = 'FAS. PUBLIK';
    } else {
        $statCount3 = $uniqueKategoris->count();
        $statLabel3 = 'SUB-KATEGORI';
    }
@endphp

@section('content')
<style>
    .cat-page {
        background: var(--bg);
    }

    /* ── Section Header (Top Banner) ── */
    .cat-top-banner {
        background: #fff;
        border-bottom: 1px solid var(--border);
        padding: calc(80px + 2rem) 2rem 1.75rem;
        position: relative;
    }
    .cat-top-banner[style*="background-image"] .cat-top-left h1,
    .cat-top-banner[style*="background-image"] .cat-top-left h1 span,
    .cat-top-banner[style*="background-image"] .cat-top-left .badge { color: #fff !important; }
    .cat-top-banner[style*="background-image"] .cat-top-left p { color: #e2e8f0 !important; }
    .cat-top-banner[style*="background-image"] .cat-top-left .badge { border-color: rgba(255,255,255,0.2); }
    .cat-top-banner[style*="background-image"] .cat-top-right .stat-box { background: rgba(255,255,255,0.12); backdrop-filter: blur(8px); border-color: rgba(255,255,255,0.1); }
    .cat-top-banner[style*="background-image"] .stat-box .stat-item .num { color: #fff !important; }
    .cat-top-banner[style*="background-image"] .stat-box .stat-item .lbl { color: rgba(255,255,255,0.5) !important; }
    .cat-top-banner[style*="background-image"] .stat-box .stat-item + .stat-item { border-color: rgba(255,255,255,0.1); }
    .cat-top-inner {
        max-width: 1440px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 2rem;
        align-items: center;
    }
    .cat-top-left .badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: 0.5rem;
    }
    .cat-top-left .badge i { font-size: 0.65rem; }
    .cat-top-left h1 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--primary);
        line-height: 1.15;
        margin-bottom: 0.5rem;
        letter-spacing: -0.02em;
    }
    .cat-top-left h1 span { color: var(--accent); }
    .cat-top-left p {
        font-size: 0.88rem;
        color: #64748b;
        max-width: 520px;
        line-height: 1.7;
        margin: 0;
    }
    .cat-top-right .stat-box {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03);
    }
    .stat-box .stat-item {
        text-align: center;
        padding: 0 1rem;
        position: relative;
    }
    .stat-box .stat-item + .stat-item { border-left: 1px solid var(--border); }
    .stat-box .stat-item .num {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--accent);
        line-height: 1;
        margin-bottom: 2px;
    }
    .stat-box .stat-item .num.gold { color: #F59E0B; }
    .stat-box .stat-item .lbl {
        font-size: 0.55rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #94a3b8;
    }

    @media (max-width: 900px) {
        .cat-top-inner { grid-template-columns: 1fr; gap: 1rem; }
        .cat-top-left h1 { font-size: 1.5rem; }
        .cat-top-banner { padding: calc(80px + 1.25rem) 1rem 1.25rem; }
        .cat-top-right .stat-box { padding: 0.85rem 0.5rem; }
        .stat-box .stat-item { padding: 0 0.6rem; }
        .stat-box .stat-item .num { font-size: 1.2rem; }
    }

    /* ── Section Spacing ── */
    .cat-section {
        max-width: 1440px;
        margin: 0 auto;
        padding: 0 1.5rem 3rem;
    }
    .cat-section .dashboard-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.06), 0 1px 4px rgba(0,0,0,0.03);
        padding: 1.5rem;
    }
    @media (max-width: 768px) {
        .cat-section { padding: 0 0.75rem 2rem; }
        .cat-section .dashboard-card { padding: 0.75rem; border-radius: 14px; }
    }

    .cat-section .section-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.6rem;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        color: #94a3b8;
        margin-bottom: 0.1rem;
    }
    .cat-section .section-badge i { color: var(--accent); font-size: 0.65rem; }
    .cat-section .section-head {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1.25rem;
    }
    .cat-section .section-head .sh-left { flex: 1; min-width: 200px; }
    .cat-section .section-head .sh-left .accent-line {
        width: 32px; height: 3px;
        background: var(--gold);
        border-radius: 3px;
        margin: 0 0 0.5rem;
    }
    .cat-section .section-head .sh-left h2 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--primary);
        line-height: 1.2;
        margin: 0;
    }
    .cat-section .section-head .sh-left h2 span { color: var(--accent); }
    .cat-section .section-head .sh-left p {
        margin: 0.1rem 0 0;
        font-size: 0.76rem;
        color: #64748b;
    }

    /* ── Section 1: Facility Grid Cards ── */
    .facility-scroll {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 14px;
    }
    .facility-scroll .f-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 18px;
        overflow: hidden;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .facility-scroll .f-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 36px rgba(0,0,0,0.07);
        border-color: #cbd5e1;
    }
    .f-card .f-card-img {
        width: 100%;
        aspect-ratio: 16 / 10;
        overflow: hidden;
        background: var(--bg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: #cbd5e1;
        position: relative;
    }
    .f-card .f-card-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease; }
    .f-card:hover .f-card-img img { transform: scale(1.04); }
    .f-card .f-card-img .status-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 3px 10px;
        border-radius: 100px;
        font-size: 0.55rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        background: rgba(255,255,255,0.85);
        backdrop-filter: blur(4px);
        color: var(--accent-dark);
    }
    .f-card .f-card-img .status-badge i { font-size: 0.5rem; margin-right: 3px; }

    .f-card .f-card-body { padding: 1rem 1.1rem 1.1rem; }
    .f-card .f-card-body h4 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 3px;
        line-height: 1.3;
    }
    .f-card .f-card-body .f-addr {
        font-size: 0.7rem;
        color: #64748b;
        display: flex;
        align-items: flex-start;
        gap: 4px;
        margin-bottom: 0.5rem;
    }
    .f-card .f-card-body .f-addr i { font-size: 0.6rem; color: var(--accent); margin-top: 2px; }
    .f-card .f-card-body .f-desc {
        font-size: 0.75rem;
        color: #64748b;
        line-height: 1.5;
        margin-bottom: 0.75rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .f-card .f-card-body .f-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .f-card .f-card-body .f-actions .btn-detail {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 0.4rem 1rem;
        border-radius: 100px;
        border: 1.5px solid var(--accent);
        background: #fff;
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--accent);
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .f-card .f-card-body .f-actions .btn-detail:hover {
        background: var(--accent);
        color: #fff;
        box-shadow: 0 2px 10px rgba(13,148,136,0.2);
    }
    .f-card .f-card-body .f-actions .btn-nav {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 1.5px solid var(--border);
        background: #fff;
        color: #94a3b8;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.65rem;
    }
    .f-card .f-card-body .f-actions .btn-nav:hover {
        border-color: var(--accent);
        color: var(--accent);
        background: #f0fdfa;
    }

    @media (max-width: 768px) {
        .facility-scroll { grid-template-columns: 1fr; }
        .f-card .f-card-img { aspect-ratio: 16 / 9; }
    }

    /* ── Section 2: Map ── */
    .cat-map-wrapper {
        width: 100%;
        height: 500px;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid var(--border);
        position: relative;
    }
    .cat-map-wrapper #catMap { width: 100%; height: 100%; position: absolute; inset: 0; }

    .map-filter-row {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }
    .map-filter-row .filter-pill {
        padding: 4px 12px;
        border-radius: 100px;
        border: 1.5px solid var(--border);
        background: #fff;
        font-size: 0.68rem;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .map-filter-row .filter-pill:hover { border-color: #cbd5e1; }
    .map-filter-row .filter-pill.active {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
    }

    @media (max-width: 768px) {
        .cat-map-wrapper { height: 360px; }
    }

    /* ── Section 3: Info / FAQ ── */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }
    .info-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 1.25rem 1.25rem 1.1rem;
        transition: all 0.25s ease;
    }
    .info-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 16px rgba(0,0,0,0.04);
    }
    .info-card .ic-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem;
        margin-bottom: 0.7rem;
    }
    .info-card h4 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 4px;
    }
    .info-card p {
        font-size: 0.75rem;
        color: #64748b;
        line-height: 1.6;
        margin: 0;
    }
    .info-card p i { width: 14px; color: var(--accent); margin-right: 3px; }

    .faq-item {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid var(--border);
        cursor: pointer;
        transition: background 0.15s;
    }
    .faq-item:last-child { border-bottom: none; }
    .faq-item:hover { background: #fafbfc; }
    .faq-item .faq-q {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--primary);
    }
    .faq-item .faq-q i { color: #94a3b8; font-size: 0.6rem; transition: transform 0.3s; }
    .faq-item.open .faq-q i { transform: rotate(180deg); }
    .faq-item .faq-a {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.35s ease, padding 0.35s ease;
        font-size: 0.74rem;
        color: #64748b;
        line-height: 1.6;
    }
    .faq-item.open .faq-a {
        max-height: 200px;
        padding-top: 8px;
    }

    @media (max-width: 768px) {
        .info-grid { grid-template-columns: 1fr; }
    }

    /* ── Modal ── */
    .modal-overlay {
        position: fixed; inset: 0; z-index: 3000;
        background: rgba(15,23,42,0.55);
        backdrop-filter: blur(8px);
        display: flex; align-items: center; justify-content: center;
        padding: 1.5rem;
        opacity: 0; pointer-events: none;
        transition: opacity 0.35s ease;
    }
    .modal-overlay.open { opacity: 1; pointer-events: all; }
    .modal-box {
        background: #fff; border-radius: 20px;
        max-width: 640px; width: 100%;
        max-height: 90vh; overflow-y: auto;
        box-shadow: 0 24px 80px rgba(0,0,0,0.25);
        transform: scale(0.92) translateY(20px);
        transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1);
    }
    .modal-overlay.open .modal-box { transform: scale(1) translateY(0); }
    .modal-box::-webkit-scrollbar { width: 3px; }
    .modal-box::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }

    .modal-header {
        position: relative; width: 100%;
        aspect-ratio: 16 / 9; overflow: hidden;
        background: var(--bg);
        display: flex; align-items: center; justify-content: center;
        font-size: 3rem; color: #cbd5e1;
    }
    .modal-header img { width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0; }
    .modal-header .modal-close {
        position: absolute; top: 12px; right: 12px;
        width: 32px; height: 32px; border-radius: 50%;
        background: rgba(0,0,0,0.5); backdrop-filter: blur(6px);
        border: none; color: #fff; font-size: 0.8rem;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        transition: background 0.2s;
    }
    .modal-header .modal-close:hover { background: rgba(0,0,0,0.7); }

    .modal-body { padding: 1.25rem 1.5rem 1.5rem; }
    .modal-body .m-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.25rem; font-weight: 800;
        color: var(--primary); margin-bottom: 0.4rem;
    }
    .modal-body .m-tags { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 0.8rem; }
    .modal-body .m-tag {
        font-size: 0.6rem; font-weight: 700; padding: 3px 10px;
        border-radius: 100px; text-transform: uppercase; letter-spacing: 0.4px;
    }
    .m-tag.aset_desa { background: #ccfbf1; color: #0f766e; }
    .m-tag.fasilitas_publik { background: #e2e8f0; color: #475569; }
    .m-tag.kategori { background: rgba(13,148,136,0.08); color: var(--accent); border: 1px solid rgba(13,148,136,0.15); }
    .modal-body .m-desc {
        font-size: 0.82rem; color: #475569;
        line-height: 1.7; margin-bottom: 1rem;
    }
    .modal-body .m-info {
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 6px 12px;
        font-size: 0.78rem;
        margin-bottom: 1.25rem;
    }
    .modal-body .m-info .m-label { color: #94a3b8; font-weight: 600; }
    .modal-body .m-info .m-value { color: var(--text); font-weight: 500; }
    .modal-body .m-info .m-value i { color: var(--accent); width: 14px; margin-right: 3px; }
    .modal-body .m-cta {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 0.7rem 1.5rem; border-radius: 12px;
        background: var(--accent); color: #fff; font-weight: 700;
        font-size: 0.8rem; font-family: 'Plus Jakarta Sans', sans-serif;
        transition: all 0.25s; border: none; cursor: pointer;
    }
    .modal-body .m-cta:hover {
        background: #0f766e;
        box-shadow: 0 4px 20px rgba(13,148,136,0.3);
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .modal-box { max-width: 100%; margin: 0.5rem; border-radius: 14px; }
        .modal-body { padding: 1rem 1.25rem 1.25rem; }
        .modal-body .m-title { font-size: 1.05rem; }
    }

    .fade-in { animation: fadeIn 0.4s ease both; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
</style>

@php
    $_has_header = isset($sectionHeader) && $sectionHeader && $sectionHeader->background_image_url;
    $_header_bg = $_has_header ? 'background-image:linear-gradient(to right, rgba(15,23,42,0.85) 0%, rgba(15,23,42,0.6) 50%, rgba(0,0,0,0.4) 100%), url(' . str_replace("'", '%27', $sectionHeader->background_url) . ');background-size:cover;background-position:center;border-bottom:none;' : '';
@endphp
<section class="cat-page">
    <!-- Top Banner -->
    <div class="cat-top-banner"{!! $_has_header ? ' style="' . $_header_bg . '"' : '' !!}>
        <div class="cat-top-inner"{!! $_has_header ? ' style="position:relative;z-index:2;"' : '' !!}>
            <div class="cat-top-left">
                <div class="badge"><i class="fas {{ $badgeIcon }}"></i> {{ $badgeText }}</div>
                <h1>{{ $pageTitle }} <span>Nekmese</span></h1>
                <p>{{ $pageSubtitle }} &mdash; Kecamatan Amarasi Selatan, Kabupaten Kupang, Nusa Tenggara Timur.</p>
            </div>
            <div class="cat-top-right">
                <div class="stat-box">
                    <div class="stat-item">
                        <div class="num">{{ $statCount1 }}+</div>
                        <div class="lbl">{{ $statLabel1 }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="num gold">{{ $statCount2 }}+</div>
                        <div class="lbl">{{ $statLabel2 }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="num">{{ $statCount3 }}+</div>
                        <div class="lbl">{{ $statLabel3 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 1: Grid Kartu Fasilitas -->
    <section class="cat-section" style="padding-top:2rem;">
        <div class="dashboard-card">
            <div class="section-badge"><i class="fas fa-th-list"></i> Daftar {{ $pageTitle }}</div>
            <div class="section-head">
                <div class="sh-left">
                    <div class="accent-line"></div>
                    <h2>Jelajahi <span>{{ $pageTitle }}</span></h2>
                    <p>Pilih fasilitas untuk melihat detail lengkap dan petunjuk arah</p>
                </div>
            </div>
            <div class="facility-scroll" id="facilityScroll">
                @forelse($facilities as $f)
                <div class="f-card fade-in"
                     data-id="{{ $f->id }}"
                     data-kategori="{{ $f->kategori }}"
                     style="animation-delay:{{ $loop->index * 0.05 }}s;">
                    <div class="f-card-img">
                        @if($f->photos && $f->photos->isNotEmpty())
                            <img src="{{ $f->photos->first()->photo_url }}" alt="{{ $f->nama }}">
                        @else
                        @switch($f->kategori)
                            @case('kantor_desa') <i class="fas fa-building"></i> @break
                            @case('sekolah') <i class="fas fa-school"></i> @break
                            @case('gereja') <i class="fas fa-church"></i> @break
                            @case('posyandu') <i class="fas fa-hospital-alt"></i> @break
                            @case('lapangan') <i class="fas fa-futbol"></i> @break
                            @case('balai_desa') <i class="fas fa-home"></i> @break
                            @case('tempat_ibadah') <i class="fas fa-mosque"></i> @break
                            @default <i class="fas fa-map-pin"></i>
                        @endswitch
                        @endif
                        <span class="status-badge">
                            <i class="fas {{ ($jenisList[$f->kategori] ?? 'fasilitas_publik') === 'aset_desa' ? 'fa-landmark' : 'fa-building' }}"></i>
                            {{ ($jenisList[$f->kategori] ?? 'fasilitas_publik') === 'aset_desa' ? 'Aset Desa' : 'Fas. Publik' }}
                        </span>
                    </div>
                    <div class="f-card-body">
                        <h4>{{ $f->nama }}</h4>
                        <div class="f-addr"><i class="fas fa-map-marker-alt"></i> {{ Str::limit($f->alamat ?? 'Desa Nekmese, Kec. Amarasi Selatan', 50) }}</div>
                        <div class="f-desc">{{ $f->deskripsi ?? 'Belum ada deskripsi untuk fasilitas ini.' }}</div>
                        <div class="f-actions">
                            <button class="btn-detail" onclick="openModal({{ $f->id }})"><i class="fas fa-info-circle"></i> Detail</button>
                            <button class="btn-nav" onclick="window.open('https://www.google.com/maps/dir/?api=1&destination={{ $f->latitude }},{{ $f->longitude }}', '_blank')" title="Petunjuk Arah"><i class="fas fa-directions"></i></button>
                        </div>
                    </div>
                </div>
                @empty
                <div style="grid-column:1/-1;text-align:center;padding:3rem;color:#94a3b8;">
                    <i class="fas fa-inbox" style="font-size:2.5rem;margin-bottom:1rem;opacity:0.3;"></i>
                    <p style="font-weight:600;">Belum ada fasilitas di kategori ini.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Section 2: Peta Interaktif Kategori -->
    <section class="cat-section">
        <div class="dashboard-card">
            <div class="section-badge"><i class="fas fa-map-marked-alt"></i> Peta {{ $pageTitle }}</div>
            <div class="section-head">
                <div class="sh-left">
                    <div class="accent-line"></div>
                    <h2>Lokasi <span>{{ $pageTitle }}</span></h2>
                    <p>Visualisasi sebaran fasilitas di wilayah Desa Nekmese</p>
                </div>
                <div class="map-filter-row" id="mapFilterRow">
                    <button class="filter-pill active" data-kat="semua" onclick="filterMapMarkers('semua')">Semua</button>
                    @foreach($uniqueKategoris as $kat)
                    <button class="filter-pill" data-kat="{{ $kat }}" onclick="filterMapMarkers('{{ $kat }}')">{{ $kategoriList[$kat] ?? $kat }}</button>
                    @endforeach
                </div>
            </div>
            <div class="cat-map-wrapper">
                <div id="catMap"></div>
            </div>
        </div>
    </section>

    <!-- Section 3: Informasi Pendukung -->
    <section class="cat-section" style="padding-bottom:4rem;">
        <div class="dashboard-card">
            <div class="section-badge"><i class="fas fa-info-circle"></i> Informasi & Kontak</div>
            <div class="section-head">
                <div class="sh-left">
                    <div class="accent-line"></div>
                    <h2>Butuh <span>Bantuan?</span></h2>
                    <p>Informasi kontak dan pertanyaan umum seputar {{ strtolower($pageTitle) }}</p>
                </div>
            </div>
            <div class="info-grid">
                <div>
                    <div class="info-card">
                        <div class="ic-icon" style="background:#f0fdfa;color:var(--accent);"><i class="fas fa-phone-alt"></i></div>
                        <h4>Kontak Pengelola</h4>
                        <p>
                            <i class="fas fa-user"></i> Kepala Desa Nekmese<br>
                            <i class="fas fa-phone"></i> +62 812-3456-7890<br>
                            <i class="fas fa-envelope"></i> desanekmese@email.com<br>
                            <i class="fas fa-map-marker-alt"></i> Kantor Desa Nekmese, Kec. Amarasi Selatan
                        </p>
                    </div>
                    <div class="info-card" style="margin-top:10px;">
                        <div class="ic-icon" style="background:#fef3c7;color:#D97706;"><i class="fas fa-clock"></i></div>
                        <h4>Jam Layanan</h4>
                        <p>
                            <i class="fas fa-calendar-check"></i> Senin - Jumat: 08:00 - 16:00 WITA<br>
                            <i class="fas fa-calendar-check"></i> Sabtu: 08:00 - 12:00 WITA<br>
                            <i class="fas fa-times-circle"></i> Minggu & Hari Besar: Tutup
                        </p>
                    </div>
                </div>
                <div>
                    <div class="info-card">
                        <div class="ic-icon" style="background:#f1f5f9;color:#475569;"><i class="fas fa-question-circle"></i></div>
                        <h4>Pertanyaan Umum (FAQ)</h4>
                        <div class="faq-item" onclick="toggleFaq(this)">
                            <div class="faq-q">Bagaimana cara menuju ke fasilitas ini? <i class="fas fa-chevron-down"></i></div>
                            <div class="faq-a">Klik tombol "Petunjuk Arah" pada kartu fasilitas atau di detail pop-up untuk membuka rute langsung di Google Maps dari lokasi Anda saat ini.</div>
                        </div>
                        <div class="faq-item" onclick="toggleFaq(this)">
                            <div class="faq-q">Apakah semua fasilitas buka setiap hari? <i class="fas fa-chevron-down"></i></div>
                            <div class="faq-a">Jam operasional bervariasi tergantung jenis fasilitas. Sebagian besar fasilitas pemerintahan buka Senin-Jumat, sementara tempat ibadah dan fasilitas umum memiliki jadwal berbeda.</div>
                        </div>
                        <div class="faq-item" onclick="toggleFaq(this)">
                            <div class="faq-q">Bagaimana jika ada data fasilitas yang perlu diperbarui? <i class="fas fa-chevron-down"></i></div>
                            <div class="faq-a">Silakan menghubungi Kantor Desa Nekmese melalui nomor kontak yang tersedia untuk melaporkan perubahan data atau informasi fasilitas.</div>
                        </div>
                        <div class="faq-item" onclick="toggleFaq(this)">
                            <div class="faq-q">Apakah ada biaya untuk mengakses fasilitas ini? <i class="fas fa-chevron-down"></i></div>
                            <div class="faq-a">Fasilitas umum dan aset desa dapat diakses secara gratis oleh masyarakat. Beberapa fasilitas pendidikan atau kesehatan mungkin memiliki ketentuan administratif tersendiri.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>

<!-- Modal -->
<div class="modal-overlay" id="facilityModal" onclick="if(event.target===this)closeModal()">
    <div class="modal-box">
        <div class="modal-header" id="modalHeader">
            <div class="swiper" id="photoSwiper" style="width:100%;height:100%;position:absolute;inset:0;">
                <div class="swiper-wrapper" id="photoSwiperWrapper"></div>
                <div class="swiper-pagination" style="bottom:8px !important;"></div>
            </div>
            <div id="modalPlaceholder" style="display:flex;align-items:center;justify-content:center;width:100%;height:100%;font-size:3rem;color:#cbd5e1;position:relative;z-index:1;"><i class="fas fa-image"></i></div>
            <button class="modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
            <button class="swiper-btn swiper-btn-prev" id="photoPrev"><i class="fas fa-chevron-left"></i></button>
            <button class="swiper-btn swiper-btn-next" id="photoNext"><i class="fas fa-chevron-right"></i></button>
        </div>
        <div class="modal-body" id="modalBody">
            <div class="m-title" id="modalTitle"></div>
            <div class="m-tags" id="modalTags"></div>
            <div class="m-desc" id="modalDesc"></div>
            <div class="m-info" id="modalInfo"></div>
            <a class="m-cta" id="modalCta" href="#" target="_blank" rel="noopener"><i class="fas fa-directions"></i> Buka Petunjuk Arah di Google Maps</a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<style>
    .modal-header .swiper { z-index: 2; }
    .modal-header .swiper .swiper-slide {
        display: flex; align-items: center; justify-content: center;
        background: var(--bg);
    }
    .modal-header .swiper .swiper-slide img {
        width: 100%; height: 100%; object-fit: cover;
    }
    .modal-header .swiper-pagination-bullet {
        background: rgba(255,255,255,0.5);
        opacity: 1;
    }
    .modal-header .swiper-pagination-bullet-active {
        background: #fff;
    }
    .swiper-btn {
        position: absolute; top: 50%; transform: translateY(-50%);
        z-index: 10; width: 32px; height: 32px; border-radius: 50%;
        background: rgba(0,0,0,0.4); backdrop-filter: blur(4px);
        border: none; color: #fff; font-size: 0.7rem;
        cursor: pointer; display: none; align-items: center; justify-content: center;
        transition: background 0.2s;
    }
    .swiper-btn:hover { background: rgba(0,0,0,0.6); }
    .swiper-btn-prev { left: 10px; }
    .swiper-btn-next { right: 10px; }
    .swiper-btn.show { display: flex; }
    .modal-header .swiper-pagination { display: none; }
    .modal-header .swiper-pagination.show { display: block; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    var map, facilities, kategoriList, jenisList, markerMap, allMarkers;

document.addEventListener('DOMContentLoaded', function () {
    map = L.map('catMap', {
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
    markerMap = {};
    allMarkers = [];

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
                    '<button onclick="openModal(' + f.id + ')" style="margin-bottom:0.75rem;padding:0.3rem 0.9rem;background:#0D9488;color:#fff;border:none;border-radius:8px;font-size:0.7rem;font-weight:600;cursor:pointer;">Lihat Detail</button>' +
                    '</div>',
                    { maxWidth: 260, className: 'custom-popup' }
                );

            marker._facilityId = f.id;
            marker._kategori = f.kategori;
            markerMap[f.id] = marker;
            allMarkers.push(marker);
        });
    }

    setTimeout(function () {
        map.invalidateSize();
    }, 300);
});
    function filterMapMarkers(kat) {
        document.querySelectorAll('#mapFilterRow .filter-pill').forEach(function(b) {
            b.classList.toggle('active', b.getAttribute('data-kat') === kat);
        });
        allMarkers.forEach(function(m) {
            if (kat === 'semua' || m._kategori === kat) {
                if (!map.hasLayer(m)) map.addLayer(m);
            } else {
                if (map.hasLayer(m)) map.removeLayer(m);
            }
        });
    }

    /* ── Modal (Level 3) ── */
    function openModal(id) {
        var f = facilities.find(function(item) { return item.id === id; });
        if (!f) return;

        var header = document.getElementById('modalHeader');
        var placeholder = document.getElementById('modalPlaceholder');
        var swiperEl = document.getElementById('photoSwiper');
        var wrapper = document.getElementById('photoSwiperWrapper');
        if (!header || !placeholder || !swiperEl || !wrapper) return;

        var pagination = swiperEl.querySelector('.swiper-pagination');
        var prevBtn = document.getElementById('photoPrev');
        var nextBtn = document.getElementById('photoNext');

        wrapper.innerHTML = '';

        var photos = f.photos || [];
        var validPhotos = photos.filter(function(p) { return p.photo_url; });

        if (validPhotos.length) {
            placeholder.style.display = 'none';
            swiperEl.style.display = 'block';
            validPhotos.forEach(function(p) {
                var slide = document.createElement('div');
                slide.className = 'swiper-slide';
                slide.innerHTML = '<img src="' + p.photo_url + '" alt="" onerror="this.parentElement.innerHTML=\'\'">';
                wrapper.appendChild(slide);
            });
            if (validPhotos.length > 1) {
                if (pagination) pagination.classList.add('show');
                if (prevBtn) prevBtn.classList.add('show');
                if (nextBtn) nextBtn.classList.add('show');
            } else {
                if (pagination) pagination.classList.remove('show');
                if (prevBtn) prevBtn.classList.remove('show');
                if (nextBtn) nextBtn.classList.remove('show');
            }
            if (window._photoSwiper) {
                try { window._photoSwiper.destroy(true, true); } catch(e) {}
                window._photoSwiper = null;
            }
            if (typeof Swiper !== 'undefined') {
                try {
                    window._photoSwiper = new Swiper(swiperEl, {
                        loop: validPhotos.length > 1,
                        pagination: pagination ? { el: pagination, clickable: true } : false,
                        navigation: { prevEl: prevBtn, nextEl: nextBtn },
                    });
                } catch(e) { console.warn('Swiper init failed', e); }
            }
        } else {
            swiperEl.style.display = 'none';
            if (pagination) pagination.classList.remove('show');
            if (prevBtn) prevBtn.classList.remove('show');
            if (nextBtn) nextBtn.classList.remove('show');
            placeholder.style.display = 'flex';
            placeholder.innerHTML = '<i class="fas ' + getIconForCategory(f.kategori) + '"></i>';
        }

        document.getElementById('modalTitle').textContent = f.nama;

        var jenis = jenisList[f.kategori] || 'fasilitas_publik';
        var jenisLabel = jenis === 'aset_desa' ? 'Aset Desa' : 'Fasilitas Publik';
        var kategoriLabel = kategoriList[f.kategori] || f.kategori;

        document.getElementById('modalTags').innerHTML =
            '<span class="m-tag ' + jenis + '">' + jenisLabel + '</span>' +
            '<span class="m-tag kategori">' + kategoriLabel + '</span>';

        document.getElementById('modalDesc').textContent = f.deskripsi || 'Belum ada deskripsi untuk fasilitas ini.';

        document.getElementById('modalInfo').innerHTML =
            '<span class="m-label">Alamat</span><span class="m-value"><i class="fas fa-map-marker-alt"></i>' + (f.alamat || 'Desa Nekmese, Kec. Amarasi Selatan') + '</span>' +
            '<span class="m-label">Jam Layanan</span><span class="m-value"><i class="fas fa-clock"></i>Senin - Jumat, 08:00 - 16:00 WITA</span>' +
            '<span class="m-label">Koordinat</span><span class="m-value"><i class="fas fa-globe"></i>' + f.latitude.toFixed(5) + ', ' + f.longitude.toFixed(5) + '</span>';

        var navLink = 'https://www.google.com/maps/dir/?api=1&destination=' + f.latitude + ',' + f.longitude;
        document.getElementById('modalCta').href = navLink;

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

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });

    /* ── FAQ Toggle ── */
    function toggleFaq(el) {
        el.classList.toggle('open');
    }

    /* ── Leaflet Styles ── */
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

    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() { map.invalidateSize(); }, 300);
    });
</script>
@endpush
