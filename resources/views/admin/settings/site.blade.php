@extends('admin.layouts.admin')

@section('title', 'Identitas Desa')
@section('breadcrumb', 'Pengaturan / Identitas Desa')

@section('content')
<div class="card" style="max-width:700px;">
    <div class="card-header">Pengaturan Identitas Desa</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.settings.site.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Logo Utama --}}
            <div class="form-group">
                <label>Logo Utama Desa</label>
                <p class="form-hint" style="margin-bottom:0.5rem;">Format: PNG transparan, SVG, JPG, WebP. Maks. 3MB.</p>

                <div class="drop-area" id="logoDropArea" style="border:2px dashed var(--border);border-radius:10px;padding:1.5rem;text-align:center;cursor:pointer;transition:border-color 0.2s;background:var(--bg);">
                    <input type="file" name="logo" id="logoInput" accept="image/png,image/svg+xml,image/jpeg,image/webp" style="display:none;" onchange="previewSiteImage(this, 'logoPreview', 'logoDropArea')">
                    <div id="logoPlaceholder">
                        <i class="fas fa-cloud-upload-alt" style="font-size:2rem;color:#94a3b8;margin-bottom:0.5rem;display:block;"></i>
                        <span style="font-size:0.78rem;color:#94a3b8;">Seret logo ke sini atau klik untuk memilih</span>
                    </div>
                </div>

                <div style="display:flex;align-items:center;gap:1rem;margin-top:0.75rem;flex-wrap:wrap;">
                    <div style="flex-shrink:0;">
                        <strong style="font-size:0.7rem;color:var(--text-muted);display:block;margin-bottom:4px;">Pratinjau</strong>
                        <div id="logoPreviewWrap" style="width:48px;height:48px;border-radius:10px;border:1px solid var(--border);display:flex;align-items:center;justify-content:center;overflow:hidden;background:#fff;">
                            @if($logoUrl)
                            <img id="logoPreview" src="{{ str_starts_with($logoUrl, 'http') ? $logoUrl : '/images/' . ltrim($logoUrl, '/') }}" style="max-width:100%;max-height:100%;object-fit:contain;">
                            @else
                            <span id="logoPreview" style="font-size:0.7rem;font-weight:800;color:var(--accent);">N</span>
                            @endif
                        </div>
                    </div>
                    <div style="flex-shrink:0;">
                        <strong style="font-size:0.7rem;color:var(--text-muted);display:block;margin-bottom:4px;">Di Navbar</strong>
                        <div id="navbarSim" style="display:flex;align-items:center;gap:6px;padding:0.4rem 0.8rem;border-radius:8px;background:var(--primary);">
                            <div id="navbarLogoPreview" style="width:22px;height:22px;border-radius:5px;display:flex;align-items:center;justify-content:center;overflow:hidden;background:var(--accent);font-size:0.55rem;font-weight:800;color:#fff;">
                                @if($logoUrl)
                                <img src="{{ str_starts_with($logoUrl, 'http') ? $logoUrl : '/images/' . ltrim($logoUrl, '/') }}" style="max-width:100%;max-height:100%;object-fit:contain;">
                                @else
                                N
                                @endif
                            </div>
                            <span style="font-size:0.72rem;font-weight:700;color:#fff;">Desa Nekmese</span>
                        </div>
                    </div>
                </div>

                @if($logoUrl)
                <div class="form-group" style="margin-top:0.5rem;margin-bottom:0;">
                    <label class="form-check">
                        <input type="checkbox" name="remove_logo" value="1">
                        Hapus logo dan gunakan logo default (inisial "N")
                    </label>
                </div>
                @endif
            </div>

            <hr style="border:none;border-top:1px solid var(--border);margin:1.25rem 0;">

            {{-- Favicon --}}
            <div class="form-group">
                <label>Favicon (Logo Tab Browser)</label>
                <p class="form-hint" style="margin-bottom:0.5rem;">Format: PNG, ICO, WebP. Maks. 1MB. Rekomendasi: 32x32px atau 512x512px (rasio 1:1).</p>

                <div class="drop-area" id="faviconDropArea" style="border:2px dashed var(--border);border-radius:10px;padding:1.5rem;text-align:center;cursor:pointer;transition:border-color 0.2s;background:var(--bg);">
                    <input type="file" name="favicon" id="faviconInput" accept="image/png,image/x-icon,image/webp" style="display:none;" onchange="previewSiteImage(this, 'faviconPreview', 'faviconDropArea')">
                    <div id="faviconPlaceholder">
                        <i class="fas fa-cloud-upload-alt" style="font-size:2rem;color:#94a3b8;margin-bottom:0.5rem;display:block;"></i>
                        <span style="font-size:0.78rem;color:#94a3b8;">Seret favicon ke sini atau klik untuk memilih</span>
                    </div>
                </div>

                <div style="display:flex;align-items:center;gap:1rem;margin-top:0.75rem;flex-wrap:wrap;">
                    <div style="flex-shrink:0;">
                        <strong style="font-size:0.7rem;color:var(--text-muted);display:block;margin-bottom:4px;">Pratinjau</strong>
                        <div style="width:32px;height:32px;border-radius:6px;border:1px solid var(--border);display:flex;align-items:center;justify-content:center;overflow:hidden;background:#fff;">
                            @if($faviconUrl)
                            <img id="faviconPreview" src="{{ str_starts_with($faviconUrl, 'http') ? $faviconUrl : '/images/' . ltrim($faviconUrl, '/') }}" style="width:100%;height:100%;object-fit:contain;">
                            @else
                            <span id="faviconPreview" style="font-size:0.6rem;font-weight:800;color:var(--accent);">N</span>
                            @endif
                        </div>
                    </div>
                    <div style="flex-shrink:0;">
                        <strong style="font-size:0.7rem;color:var(--text-muted);display:block;margin-bottom:4px;">Simulasi Tab</strong>
                        <div style="display:flex;align-items:center;gap:5px;padding:0.3rem 0.7rem;border-radius:6px 6px 0 0;background:#e2e8f0;">
                            <div id="tabFaviconPreview" style="width:14px;height:14px;border-radius:2px;display:flex;align-items:center;justify-content:center;overflow:hidden;font-size:0.45rem;font-weight:800;color:var(--accent);background:#fff;">
                                @if($faviconUrl)
                                <img src="{{ str_starts_with($faviconUrl, 'http') ? $faviconUrl : '/images/' . ltrim($faviconUrl, '/') }}" style="width:100%;height:100%;object-fit:contain;">
                                @else
                                N
                                @endif
                            </div>
                            <span style="font-size:0.62rem;font-weight:600;color:#475569;">Desa Nekmese</span>
                        </div>
                    </div>
                </div>

                @if($faviconUrl)
                <div class="form-group" style="margin-top:0.5rem;margin-bottom:0;">
                    <label class="form-check">
                        <input type="checkbox" name="remove_favicon" value="1">
                        Hapus favicon dan gunakan default (inisial "N")
                    </label>
                </div>
                @endif
            </div>

            <div style="display:flex;gap:0.5rem;margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">Kembali</a>
            </div>
        </form>
    </div>
</div>

<script>
function previewSiteImage(input, previewId, dropAreaId) {
    var area = document.getElementById(dropAreaId);
    var placeholder = area.querySelector('div:first-child');

    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var src = e.target.result;

            // Update preview
            var preview = document.getElementById(previewId);
            if (preview.tagName === 'IMG') {
                preview.src = src;
            } else {
                var img = document.createElement('img');
                img.id = previewId;
                img.src = src;
                img.style.maxWidth = '100%';
                img.style.maxHeight = '100%';
                img.style.objectFit = 'contain';
                preview.parentNode.replaceChild(img, preview);
            }

            // Update navbar/tab simulation
            if (previewId === 'logoPreview') {
                var navPreview = document.getElementById('navbarLogoPreview');
                navPreview.innerHTML = '<img src="' + src + '" style="max-width:100%;max-height:100%;object-fit:contain;">';
            } else if (previewId === 'faviconPreview') {
                var tabPreview = document.getElementById('tabFaviconPreview');
                tabPreview.innerHTML = '<img src="' + src + '" style="width:100%;height:100%;object-fit:contain;">';
            }

            // Visual feedback
            area.style.borderColor = 'var(--accent)';
            area.style.background = '#f0fdfa';
            if (placeholder) placeholder.innerHTML = '<i class="fas fa-check-circle" style="font-size:1.5rem;color:var(--accent);"></i><br><span style="font-size:0.78rem;color:var(--accent);">Gambar dipilih</span>';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Drag-and-drop support
document.querySelectorAll('.drop-area').forEach(function(area) {
    area.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.style.borderColor = 'var(--accent)';
        this.style.background = '#f0fdfa';
    });
    area.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.style.borderColor = 'var(--border)';
        this.style.background = 'var(--bg)';
    });
    area.addEventListener('drop', function(e) {
        e.preventDefault();
        this.style.borderColor = 'var(--border)';
        this.style.background = 'var(--bg)';
        var input = this.querySelector('input[type="file"]');
        if (input && e.dataTransfer.files.length) {
            input.files = e.dataTransfer.files;
            input.dispatchEvent(new Event('change'));
        }
    });
    area.addEventListener('click', function() {
        this.querySelector('input[type="file"]').click();
    });
});
</script>
@endsection
