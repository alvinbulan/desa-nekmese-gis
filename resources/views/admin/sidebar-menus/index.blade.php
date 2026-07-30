@extends('admin.layouts.admin')

@section('title', 'Menu Sidebar')
@section('breadcrumb', 'Pengaturan / Menu Sidebar')

@section('content')
<div class="card">
    <div class="card-header">
        <span>Kelola Banner Menu Mobile Sidebar</span>
        <span style="font-size:0.7rem;color:var(--text-muted);font-weight:400;">Klik menu untuk mengubah gambar banner</span>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1rem;">
            @foreach($menus as $m)
            <a href="{{ route('admin.sidebar-menus.edit', $m) }}" style="text-decoration:none;color:inherit;display:block;">
                <div style="border:1px solid var(--border);border-radius:12px;overflow:hidden;transition:box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.06)'" onmouseout="this.style.boxShadow=''">
                    <div style="height:80px;background-size:cover;background-position:center;position:relative;{{ $m->banner_image_url ? 'background-image:url(' . $m->banner_url . ')' : $m->default_gradient }}">
                        <div style="position:absolute;inset:0;background:linear-gradient(to right,rgba(15,23,42,0.7),transparent);"></div>
                        <div style="position:relative;z-index:1;display:flex;align-items:center;gap:8px;height:100%;padding:0 1rem;">
                            <i class="{{ $m->icon_name }}" style="color:#fff;font-size:0.9rem;width:20px;text-align:center;"></i>
                            <span style="color:#fff;font-weight:600;font-size:0.82rem;">{{ $m->menu_name }}</span>
                            @if(!$m->active)
                            <span style="margin-left:auto;font-size:0.55rem;background:#ef4444;color:#fff;padding:2px 8px;border-radius:4px;">Nonaktif</span>
                            @endif
                            @if($m->banner_image_url)
                            <span style="margin-left:auto;font-size:0.55rem;background:var(--accent);color:#fff;padding:2px 8px;border-radius:4px;"><i class="fas fa-image"></i> Ada</span>
                            @endif
                        </div>
                    </div>
                    <div style="padding:0.65rem 0.85rem;display:flex;justify-content:space-between;align-items:center;gap:4px;">
                        <span style="font-size:0.72rem;color:var(--text-muted);display:flex;align-items:center;gap:6px;">
                            <i class="fas fa-link"></i> {{ $m->target_link }}
                            @if($m->menu_name === 'Beranda')
                            <span style="font-size:0.55rem;color:#94a3b8;background:#f1f5f9;padding:1px 6px;border-radius:3px;">Hero BG</span>
                            @endif
                        </span>
                        <span style="display:flex;align-items:center;gap:6px;">
                            @if($m->background_image_url)
                            <span style="font-size:0.55rem;background:#f0fdfa;color:var(--accent);padding:2px 6px;border-radius:4px;"><i class="fas fa-image"></i> Header</span>
                            @endif
                            <span style="font-size:0.65rem;color:var(--accent);">Edit <i class="fas fa-chevron-right"></i></span>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
