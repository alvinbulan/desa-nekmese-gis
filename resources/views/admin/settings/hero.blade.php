@extends('admin.layouts.admin')

@section('title', 'Hero Background')
@section('breadcrumb', 'Pengaturan / Hero Background')

@section('content')
<div class="card" style="max-width:640px;">
    <div class="card-header">Ubah Gambar Latar Hero Section</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.settings.hero.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Gambar Latar Saat Ini</label>
                <div id="currentPreview" style="width:100%;aspect-ratio:21/9;border-radius:10px;overflow:hidden;border:1px solid var(--border);background:var(--bg);display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:0.78rem;background-size:cover;background-position:center;{{ $heroBg ? 'background-image:url(' . (str_starts_with($heroBg, 'http') ? $heroBg : '/images/' . ltrim($heroBg, '/')) . ')' : '' }}">
                    @if(!$heroBg)
                    <span><i class="fas fa-image" style="margin-right:6px;"></i>Belum ada gambar (akan menggunakan warna default)</span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <label>Upload Gambar Baru</label>
                <input type="file" name="hero_image" class="form-control" accept="image/jpeg,image/png,image/webp" onchange="previewHero(this)">
                <div class="form-hint">Format: JPG, PNG, WebP. Maksimal 3MB.</div>
                <img id="livePreview" style="display:none;margin-top:0.5rem;width:100%;aspect-ratio:21/9;object-fit:cover;border-radius:10px;border:2px dashed var(--accent);">
            </div>

            @if($heroBg)
            <div class="form-group" style="margin-bottom:0.5rem;">
                <label class="form-check">
                    <input type="checkbox" name="remove_image" value="1">
                    Hapus gambar dan gunakan warna default
                </label>
            </div>
            @endif

            <div style="display:flex;gap:0.5rem;margin-top:1rem;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">Kembali</a>
            </div>
        </form>
    </div>
</div>

<script>
function previewHero(input) {
    var live = document.getElementById('livePreview');
    var current = document.getElementById('currentPreview');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            live.src = e.target.result;
            live.style.display = 'block';
            // also update current preview
            current.style.backgroundImage = 'url(' + e.target.result + ')';
            current.innerHTML = '';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
