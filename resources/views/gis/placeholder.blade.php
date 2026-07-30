@extends('layouts.app')

@section('title', 'Desa Nekmese - ' . $pageTitle)

@section('content')
<style>
    .doc-page {
        min-height: 100vh;
        background: var(--bg);
    }
    .doc-header {
        background: #fff;
        border-bottom: 1px solid var(--border);
        padding: calc(80px + 1.5rem) 2rem 1.25rem;
    }
    .doc-header .breadcrumb {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.68rem;
        font-weight: 600;
        color: #94a3b8;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .doc-header .breadcrumb a { color: var(--accent); }
    .doc-header .breadcrumb .sep { color: #cbd5e1; }
    .doc-header h1 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 0.25rem;
    }
    .doc-header h1 span { color: var(--accent); }
    .doc-header p {
        font-size: 0.78rem;
        color: #64748b;
        margin: 0;
    }
    .doc-controls {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 1rem;
        flex-wrap: wrap;
    }
    .doc-controls .search-box {
        flex: 1;
        min-width: 200px;
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--bg);
        border-radius: 10px;
        padding: 0.45rem 0.85rem;
        border: 1.5px solid var(--border);
        transition: border 0.2s;
    }
    .doc-controls .search-box:focus-within { border-color: var(--accent); background: #fff; }
    .doc-controls .search-box i { color: #94a3b8; font-size: 0.78rem; }
    .doc-controls .search-box input {
        border: none;
        background: none;
        outline: none;
        width: 100%;
        font-size: 0.8rem;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 500;
        color: var(--text);
    }
    .doc-controls .search-box input::placeholder { color: #94a3b8; }
    .doc-controls .filter-select {
        font-size: 0.72rem;
        font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        padding: 0.4rem 0.6rem;
        background: #fff;
        color: var(--text);
        outline: none;
        cursor: pointer;
    }
    .doc-controls .filter-select:focus { border-color: var(--accent); }

    .doc-list {
        max-width: 960px;
        margin: 0 auto;
        padding: 1.5rem 2rem 3rem;
    }
    .doc-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 1.1rem 1.25rem;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        margin-bottom: 10px;
        transition: all 0.2s ease;
    }
    .doc-item:hover {
        border-color: #cbd5e1;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    }
    .doc-item .doc-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1rem;
    }
    .doc-item .doc-body { flex: 1; min-width: 0; }
    .doc-item .doc-body h4 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 2px;
        line-height: 1.3;
    }
    .doc-item .doc-body .doc-meta {
        font-size: 0.68rem;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 4px;
    }
    .doc-item .doc-body .doc-meta i { margin-right: 3px; }
    .doc-item .doc-body .doc-excerpt {
        font-size: 0.76rem;
        color: #64748b;
        line-height: 1.5;
    }
    .doc-item .doc-action {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 0.4rem 0.9rem;
        border-radius: 8px;
        border: 1.5px solid var(--border);
        background: #fff;
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--accent);
        cursor: pointer;
        transition: all 0.2s;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .doc-item .doc-action:hover {
        border-color: var(--accent);
        background: #f0fdfa;
    }
    .doc-item .doc-action i { font-size: 0.6rem; }

    .doc-empty {
        text-align: center;
        padding: 3rem 2rem;
        color: #94a3b8;
    }
    .doc-empty i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.3; }
    .doc-empty h3 { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 1.1rem; color: var(--text); margin-bottom: 0.25rem; }

    @media (max-width: 768px) {
        .doc-header { padding: calc(80px + 1rem) 1rem 0.85rem; }
        .doc-header h1 { font-size: 1.15rem; }
        .doc-list { padding: 1rem; }
        .doc-item { flex-direction: column; gap: 10px; }
        .doc-item .doc-action { align-self: flex-start; }
        .doc-controls { flex-direction: column; align-items: stretch; }
    }
</style>

<section class="doc-page">
    <div class="doc-header">
        <div class="breadcrumb">
            <a href="/">Beranda</a>
            <span class="sep">/</span>
            <span style="color:var(--primary);font-weight:700;">{{ $pageTitle }}</span>
        </div>
        <h1>{{ $pageTitle }} <span>Desa Nekmese</span></h1>
        <p>{{ $pageSubtitle }}</p>
        <div class="doc-controls">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="docSearch" placeholder="Cari {{ strtolower($pageTitle) }}..." oninput="filterDocs()">
            </div>
            <select class="filter-select" id="docFilter" onchange="filterDocs()">
                <option value="semua">Semua</option>
                @if(str_contains($pageTitle, 'Pengumuman'))
                <option value="Pengumuman">Pengumuman</option>
                <option value="Kegiatan">Kegiatan</option>
                @else
                <option value="Peraturan Desa">Peraturan Desa</option>
                <option value="Keputusan">Keputusan</option>
                @endif
            </select>
        </div>
    </div>

    <div class="doc-list" id="docList">
        @forelse($items as $item)
        <div class="doc-item" data-tag="{{ $item->tipe ?? 'Peraturan Desa' }}">
            <div class="doc-icon" style="background:{{ str_contains($pageTitle, 'Pengumuman') ? '#dbeafe;color:#1d4ed8' : '#fef3c7;color:#b45309' }};">
                <i class="{{ str_contains($pageTitle, 'Pengumuman') ? 'fas fa-bullhorn' : 'fas fa-file-contract' }}"></i>
            </div>
            <div class="doc-body">
                <h4>{{ $item->judul }}</h4>
                <div class="doc-meta">
                    <span><i class="far fa-calendar-alt"></i> {{ $item->tanggal?->format('d F Y') ?? '-' }}</span>
                    <span><i class="fas fa-tag"></i> {{ $item->tipe ?? 'Peraturan Desa' }}</span>
                </div>
                <div class="doc-excerpt">{{ Str::limit($item->deskripsi ?? $item->isi ?? '', 150) }}</div>
            </div>
            @if(!str_contains($pageTitle, 'Pengumuman') && $item->file_path)
            <a href="/storage/{{ ltrim($item->file_path, '/') }}" target="_blank" class="doc-action"><i class="fas fa-download"></i> PDF</a>
            @elseif(str_contains($pageTitle, 'Pengumuman'))
            <button class="doc-action" onclick="showToast('Fitur unduh akan segera tersedia')"><i class="fas fa-download"></i> PDF</button>
            @endif
        </div>
        @empty
        <div class="doc-empty">
            <i class="fas fa-inbox"></i>
            <h3>Belum Ada {{ $pageTitle }}</h3>
            <p style="font-size:0.82rem;color:var(--text-muted);">Data akan ditambahkan oleh admin desa.</p>
        </div>
        @endforelse
        @if(method_exists($items, 'links'))
        <div class="pagination" style="display:flex;justify-content:center;gap:4px;margin-top:1.5rem;">
            {{ $items->links() }}
        </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
    function filterDocs() {
        var q = document.getElementById('docSearch').value.toLowerCase();
        var filter = document.getElementById('docFilter').value;
        document.querySelectorAll('.doc-item').forEach(function(item) {
            var text = item.textContent.toLowerCase();
            var tag = item.getAttribute('data-tag');
            var matchSearch = !q || text.includes(q);
            var matchFilter = filter === 'semua' || tag === filter;
            item.style.display = (matchSearch && matchFilter) ? '' : 'none';
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
</script>
@endpush
