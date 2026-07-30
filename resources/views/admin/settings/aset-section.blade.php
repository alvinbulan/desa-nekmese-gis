@extends('admin.layouts.admin')

@section('title', 'Section Aset Beranda')
@section('breadcrumb', 'Pengaturan / Section Aset Beranda')

@section('content')
<div class="card" style="max-width:720px;">
    <div class="card-header">Kelola Gambar Section Aset Pemerintah</div>
    <div class="card-body">
        <p style="font-size:0.82rem;color:#64748b;margin-bottom:1.25rem;">
            Atur foto yang tampil di section <strong>Aset Pemerintah</strong> pada halaman beranda.
            Foto utama ditampilkan di kotak besar (belakang), foto sekunder di kotak kecil (depan).
        </p>

        <form method="POST" action="{{ route('admin.section-assets.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Foto Utama --}}
            <div class="form-group">
                <label>Foto Utama <span style="font-weight:400;color:#94a3b8;">(Kotak Besar Belakang)</span></label>

                <div id="mainPreview" style="width:100%;aspect-ratio:4/3;border-radius:18px;overflow:hidden;border:1px solid var(--border);background:linear-gradient(145deg,#0D9488,#0F766E);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.3);font-size:3rem;margin-bottom:0.75rem;background-size:cover;background-position:center;{{ $mainImage ? 'background-image:url(' . '/images/' . ltrim($mainImage, '/') . ')' : '' }}">
                    @if(!$mainImage)
                    <i class="fas fa-building"></i>
                    @endif
                </div>

                <input type="file" name="main_image" class="form-control" accept="image/jpeg,image/png,image/webp" onchange="previewAsetImage(this, 'mainPreview', 'mainUploadArea')">
                <div class="form-hint">Format: JPG, PNG, WebP. Maksimal 3MB.</div>

                @if($mainImage)
                <label class="form-check" style="margin-top:0.5rem;">
                    <input type="checkbox" name="remove_main_image" value="1">
                    Hapus foto utama
                </label>
                @endif
            </div>

            <hr style="border:none;border-top:1px solid var(--border);margin:1.25rem 0;">

            {{-- Foto Sekunder --}}
            <div class="form-group">
                <label>Foto Sekunder <span style="font-weight:400;color:#94a3b8;">(Kotak Kecil Depan)</span></label>

                <div id="subPreview" style="width:180px;aspect-ratio:1;border-radius:14px;overflow:hidden;border:3px solid #fff;box-shadow:0 8px 24px rgba(0,0,0,0.12);background:linear-gradient(145deg,#14B8A6,#0F766E);display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.3);font-size:2rem;margin-bottom:0.75rem;background-size:cover;background-position:center;{{ $subImage ? 'background-image:url(' . '/images/' . ltrim($subImage, '/') . ')' : '' }}">
                    @if(!$subImage)
                    <i class="fas fa-truck"></i>
                    @endif
                </div>

                <input type="file" name="sub_image" class="form-control" accept="image/jpeg,image/png,image/webp" onchange="previewAsetImage(this, 'subPreview', 'subUploadArea')">
                <div class="form-hint">Format: JPG, PNG, WebP. Maksimal 3MB.</div>

                @if($subImage)
                <label class="form-check" style="margin-top:0.5rem;">
                    <input type="checkbox" name="remove_sub_image" value="1">
                    Hapus foto sekunder
                </label>
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
function previewAsetImage(input, previewId) {
    var preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.style.backgroundImage = 'url(' + e.target.result + ')';
            preview.style.backgroundSize = 'cover';
            preview.style.backgroundPosition = 'center';
            // remove icon placeholder
            var icon = preview.querySelector('i');
            if (icon) icon.remove();
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
