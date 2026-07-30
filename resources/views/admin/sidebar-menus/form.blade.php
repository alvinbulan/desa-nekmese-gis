@extends('admin.layouts.admin')

@section('title', 'Edit Menu: ' . $sidebarMenu->menu_name)
@section('breadcrumb', '<a href="' . route('admin.sidebar-menus.index') . '" style="color:var(--accent);text-decoration:none;">Menu Sidebar</a> / ' . $sidebarMenu->menu_name)

@section('content')
<div class="card" style="max-width:640px;">
    <div class="card-header">Edit Banner — {{ $sidebarMenu->menu_name }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.sidebar-menus.update', $sidebarMenu) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Preview banner --}}
            <div class="form-group">
                <label>Pratinjau Banner Saat Ini</label>
                <div id="bannerPreview" style="width:100%;height:80px;border-radius:12px;overflow:hidden;border:1px solid var(--border);background-size:cover;background-position:center;position:relative;{{ $sidebarMenu->banner_image_url ? 'background-image:url(' . $sidebarMenu->banner_url . ')' : $sidebarMenu->default_gradient }}">
                    <div style="position:absolute;inset:0;background:linear-gradient(to right,rgba(15,23,42,0.65),transparent);"></div>
                    <div style="position:relative;z-index:1;display:flex;align-items:center;gap:8px;height:100%;padding:0 1rem;">
                        <i class="{{ $sidebarMenu->icon_name }}" style="color:#fff;font-size:0.85rem;width:18px;text-align:center;"></i>
                        <span style="color:#fff;font-weight:600;font-size:0.78rem;">{{ $sidebarMenu->menu_name }}</span>
                    </div>
                </div>
            </div>

            {{-- Upload --}}
            <div class="form-group">
                <label>Upload Gambar Banner Baru</label>
                <div class="drop-area" id="bannerDropArea" style="border:2px dashed var(--border);border-radius:10px;padding:1.2rem;text-align:center;cursor:pointer;transition:border-color 0.2s;background:var(--bg);">
                    <input type="file" name="banner_image" id="bannerInput" accept="image/jpeg,image/png,image/webp" style="display:none;" onchange="previewBanner(this)">
                    <div id="bannerPlaceholder">
                        <i class="fas fa-cloud-upload-alt" style="font-size:1.8rem;color:#94a3b8;margin-bottom:0.3rem;display:block;"></i>
                        <span style="font-size:0.75rem;color:#94a3b8;">Seret gambar ke sini atau klik untuk memilih</span>
                    </div>
                </div>
                <div class="form-hint">Format: JPG, PNG, WebP. Maks. 5MB. Rasio landscape 3:1 — 4:1.</div>
                <img id="livePreview" style="display:none;margin-top:0.5rem;width:100%;height:80px;object-fit:cover;border-radius:12px;border:2px dashed var(--accent);">
            </div>

            {{-- Hidden meta fields --}}
            <input type="hidden" name="menu_name" value="{{ $sidebarMenu->menu_name }}">
            <input type="hidden" name="icon_name" value="{{ $sidebarMenu->icon_name }}">
            <input type="hidden" name="target_link" value="{{ $sidebarMenu->target_link }}">
            <input type="hidden" name="sort_order" value="{{ $sidebarMenu->sort_order }}">

            <div class="form-group">
                <label class="form-check">
                    <input type="hidden" name="active" value="0">
                    <input type="checkbox" name="active" value="1" {{ $sidebarMenu->active ? 'checked' : '' }}> Menu Aktif
                </label>
            </div>

            @if($sidebarMenu->banner_image_url)
            <div class="form-group">
                <label class="form-check">
                    <input type="checkbox" name="remove_banner" value="1">
                    Hapus banner dan gunakan <strong>gradient default</strong>
                </label>
            </div>
            @endif

            <hr style="border:none;border-top:1px solid var(--border);margin:1.25rem 0;">

            {{-- Background Image for Section Header (non-Beranda only) --}}
            @if($sidebarMenu->menu_name === 'Beranda')
            <div class="form-group">
                <div style="padding:0.75rem 1rem;background:#f1f5f9;border-radius:8px;font-size:0.75rem;color:#64748b;">
                    <i class="fas fa-info-circle" style="color:var(--accent);margin-right:4px;"></i>
                    Header <strong>Beranda</strong> menggunakan pengaturan <strong>Hero Background</strong> terpisah.
                    Unggah gambar latar Hero melalui menu <a href="{{ route('admin.settings.hero') }}" style="color:var(--accent);font-weight:600;">Hero Background</a>.
                </div>
            </div>
            @else
            <div class="form-group">
                <label>Background Header Section <span style="font-weight:400;color:var(--text-muted);">(tampilan halaman kategori)</span></label>
                <div id="bgPreview" style="width:100%;height:140px;border-radius:12px;overflow:hidden;border:1px solid var(--border);background-size:cover;background-position:center;position:relative;{{ $sidebarMenu->background_image_url ? 'background-image:url(' . $sidebarMenu->background_url . ')' : 'background:var(--bg)' }}">
                    <div style="position:absolute;inset:0;background:linear-gradient(to right, rgba(15,23,42,0.85) 0%, rgba(15,23,42,0.6) 50%, rgba(0,0,0,0.4) 100%);"></div>
                    <div style="position:relative;z-index:1;display:flex;flex-direction:column;justify-content:center;height:100%;padding:0 1.5rem;">
                        <span style="font-size:0.6rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:rgba(255,255,255,0.6);margin-bottom:0.25rem;"><i class="{{ $sidebarMenu->icon_name }}"></i> {{ strtoupper($sidebarMenu->menu_name) }}</span>
                        <span style="font-size:1.2rem;font-weight:800;color:#fff;">{{ $sidebarMenu->menu_name }} <span style="color:#0D9488;">Nekmese</span></span>
                        <span style="font-size:0.7rem;color:#e2e8f0;margin-top:2px;">{{ $sidebarMenu->heading_text ?? 'Deskripsi section' }}</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Upload Background Header</label>
                <div class="drop-area" id="bgDropArea" style="border:2px dashed var(--border);border-radius:10px;padding:1.2rem;text-align:center;cursor:pointer;transition:border-color 0.2s;background:var(--bg);">
                    <input type="file" name="background_image" id="bgInput" accept="image/jpeg,image/png,image/webp" style="display:none;" onchange="previewBg(this)">
                    <div id="bgPlaceholder">
                        <i class="fas fa-cloud-upload-alt" style="font-size:1.8rem;color:#94a3b8;margin-bottom:0.3rem;display:block;"></i>
                        <span style="font-size:0.75rem;color:#94a3b8;">Seret gambar latar ke sini</span>
                    </div>
                </div>
                <div class="form-hint">Format: JPG, PNG, WebP. Maks. 5MB. Akan tampil sebagai banner header halaman kategori.</div>
                <img id="bgLivePreview" style="display:none;margin-top:0.5rem;width:100%;height:140px;object-fit:cover;border-radius:12px;border:2px dashed var(--accent);">
            </div>

            <div class="form-group">
                <label>Heading Text <span style="font-weight:400;color:var(--text-muted);">(subtitle deskripsi)</span></label>
                <input type="text" name="heading_text" class="form-control" value="{{ old('heading_text', $sidebarMenu->heading_text ?? '') }}" placeholder="Contoh: Sarana pendidikan di Desa Nekmese">
            </div>

            @if($sidebarMenu->background_image_url)
            <div class="form-group">
                <label class="form-check">
                    <input type="checkbox" name="remove_background" value="1">
                    Hapus background dan gunakan <strong>warna default</strong>
                </label>
            </div>
            @endif
            @endif

            <div style="display:flex;gap:0.5rem;margin-top:1rem;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a href="{{ route('admin.sidebar-menus.index') }}" class="btn btn-outline">Kembali</a>
            </div>
        </form>
    </div>
</div>

<script>
function previewBanner(input) {
    var live = document.getElementById('livePreview');
    var current = document.getElementById('bannerPreview');
    var area = document.getElementById('bannerDropArea');
    var placeholder = area.querySelector('div');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var src = e.target.result;
            live.src = src;
            live.style.display = 'block';
            current.style.backgroundImage = 'url(' + src + ')';
            area.style.borderColor = 'var(--accent)';
            area.style.background = '#f0fdfa';
            if (placeholder) placeholder.innerHTML = '<i class="fas fa-check-circle" style="font-size:1.3rem;color:var(--accent);"></i><br><span style="font-size:0.75rem;color:var(--accent);">Gambar siap</span>';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function previewBg(input) {
    var live = document.getElementById('bgLivePreview');
    var current = document.getElementById('bgPreview');
    var area = document.getElementById('bgDropArea');
    var placeholder = area.querySelector('div');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var src = e.target.result;
            live.src = src;
            live.style.display = 'block';
            current.style.backgroundImage = 'url(' + src + ')';
            area.style.borderColor = 'var(--accent)';
            area.style.background = '#f0fdfa';
            if (placeholder) placeholder.innerHTML = '<i class="fas fa-check-circle" style="font-size:1.3rem;color:var(--accent);"></i><br><span style="font-size:0.75rem;color:var(--accent);">Gambar siap</span>';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
document.querySelectorAll('.drop-area').forEach(function(a) {
    a.addEventListener('dragover', function(e) { e.preventDefault(); this.style.borderColor = 'var(--accent)'; this.style.background = '#f0fdfa'; });
    a.addEventListener('dragleave', function(e) { e.preventDefault(); this.style.borderColor = 'var(--border)'; this.style.background = 'var(--bg)'; });
    a.addEventListener('drop', function(e) {
        e.preventDefault(); this.style.borderColor = 'var(--border)'; this.style.background = 'var(--bg)';
        var inp = this.querySelector('input[type="file"]');
        if (inp && e.dataTransfer.files.length) { inp.files = e.dataTransfer.files; inp.dispatchEvent(new Event('change')); }
    });
    a.addEventListener('click', function() { this.querySelector('input[type="file"]').click(); });
});
</script>
@endsection
