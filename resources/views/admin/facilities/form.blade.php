@extends('admin.layouts.admin')

@section('title', isset($facility) ? 'Edit Fasilitas' : 'Tambah Fasilitas')
@section('breadcrumb', '<a href="' . route('admin.facilities.index') . '" style="color:var(--accent);text-decoration:none;">Fasilitas</a> / ' . (isset($facility) ? 'Edit' : 'Tambah'))

@section('content')
<div class="card" style="max-width:720px;">
    <div class="card-header">{{ isset($facility) ? 'Edit Fasilitas' : 'Tambah Fasilitas Baru' }}</div>
    <div class="card-body">
        <form method="POST" action="{{ isset($facility) ? route('admin.facilities.update', $facility) : route('admin.facilities.store') }}" enctype="multipart/form-data">
            @csrf
            @if(isset($facility)) @method('PUT') @endif

            @if($errors->any())
            <div class="alert alert-error" style="margin-bottom:1rem;">
                <strong><i class="fas fa-exclamation-triangle"></i> Terdapat kesalahan:</strong>
                <ul style="margin:0.5rem 0 0 1.25rem;font-size:0.78rem;">
                    @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="form-group">
                <label>Nama Fasilitas</label>
                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $facility->nama ?? '') }}" required>
                @error('nama')<div class="form-hint" style="color:#ef4444;">{{ $message }}</div>@enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" class="form-control" required>
                        @foreach($kategoriList as $key => $label)
                        <option value="{{ $key }}" {{ (old('kategori', $facility->kategori ?? '') === $key) ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <label class="form-check" style="margin-top:0.5rem;">
                        <input type="hidden" name="active" value="0">
                        <input type="checkbox" name="active" value="1" {{ old('active', $facility->active ?? true) ? 'checked' : '' }}> Aktif
                    </label>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Latitude</label>
                    <input type="number" step="any" name="latitude" class="form-control @error('latitude') is-invalid @enderror" value="{{ old('latitude', $facility->latitude ?? '') }}" required>
                    @error('latitude')<div class="form-hint" style="color:#ef4444;">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Longitude</label>
                    <input type="number" step="any" name="longitude" class="form-control @error('longitude') is-invalid @enderror" value="{{ old('longitude', $facility->longitude ?? '') }}" required>
                    @error('longitude')<div class="form-hint" style="color:#ef4444;">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control">{{ old('alamat', $facility->alamat ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control">{{ old('deskripsi', $facility->deskripsi ?? '') }}</textarea>
            </div>

            <hr style="border:none;border-top:1px solid var(--border);margin:1.25rem 0;">
            <div class="form-group">
                <label>Galeri Foto Fasilitas</label>

                <label for="photos-input" style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:100%;min-height:180px;padding:1.5rem;border:2px dashed var(--border);border-radius:16px;cursor:pointer;background:var(--bg);transition:all 0.2s;text-align:center;margin-top:0.5rem;" onmouseover="this.style.borderColor='var(--accent)';this.style.background='#f0fdfa'" onmouseout="this.style.borderColor='var(--border)';this.style.background='var(--bg)'">

                    <svg style="width:48px;height:48px;margin-bottom:0.75rem;color:var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>

                    <p style="margin-bottom:0.25rem;font-size:0.82rem;color:var(--text);font-weight:500;">
                        <span style="font-weight:700;color:var(--accent);text-decoration:underline;">Klik di sini untuk pilih foto</span> atau seret foto ke area ini
                    </p>
                    <p style="font-size:0.7rem;color:#94a3b8;">Format: JPG, PNG, WebP (Maks. 5MB per foto, bisa pilih banyak sekaligus)</p>

                    <input id="photos-input" type="file" name="photos[]" multiple accept="image/jpeg,image/png,image/webp" style="display:none;" onchange="previewGalleryPhotos(event)">
                </label>

                <div id="preview-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:12px;margin-top:1rem;"></div>

                @if(isset($facility))
                <div style="margin-top:1rem;border-top:1px solid var(--border);padding-top:1rem;">
                    <label style="font-size:0.78rem;font-weight:600;display:block;margin-bottom:0.5rem;">Foto Tersimpan</label>
                    <div id="galleryGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:10px;">
                        @foreach($facility->photos as $photo)
                        <div class="gallery-item" data-id="{{ $photo->id }}" data-path="{{ $photo->photo_path }}" style="position:relative;aspect-ratio:4/3;border-radius:10px;overflow:hidden;border:1px solid var(--border);background:var(--bg);">
                            <img src="{{ $photo->photo_url }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                            <button type="button" class="gallery-del" data-id="{{ $photo->id }}" data-path="{{ $photo->photo_path }}" title="Hapus foto" style="position:absolute;top:5px;right:5px;width:24px;height:24px;border-radius:50%;background:rgba(0,0,0,0.55);border:none;color:#fff;font-size:0.65rem;cursor:pointer;display:flex;align-items:center;justify-content:center;opacity:0;transition:opacity 0.2s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'"><i class="fas fa-times"></i></button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div style="display:flex;gap:0.5rem;margin-top:1rem;">
                <button type="submit" class="btn btn-primary">{{ isset($facility) ? 'Simpan Perubahan' : 'Simpan' }}</button>
                <a href="{{ route('admin.facilities.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    .gallery-item:hover .gallery-del { opacity: 1 !important; }
    .gallery-item .gallery-del:hover { background: #ef4444 !important; }
</style>
@endpush

@push('scripts')
<script>
function previewGalleryPhotos(event) {
    var container = document.getElementById('preview-grid');
    container.innerHTML = '';
    var files = event.target.files;

    if (files && files.length > 0) {
        Array.from(files).forEach(function(file, index) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var card = document.createElement('div');
                card.style.cssText = 'position:relative;border-radius:12px;overflow:hidden;border:1px solid var(--border);aspect-ratio:4/3;background:var(--bg);box-shadow:0 1px 4px rgba(0,0,0,0.04);';
                card.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;">';
                container.appendChild(card);
            };
            reader.readAsDataURL(file);
        });
    }
}

(function() {
    var grid = document.getElementById('galleryGrid');
    if (!grid) return;

    var facilityId = {{ isset($facility) ? $facility->id : 'null' }};
    var csrf = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

    grid.addEventListener('click', function(e) {
        var btn = e.target.closest('.gallery-del');
        if (!btn) return;
        e.stopPropagation();
        e.preventDefault();
        var id = btn.dataset.id;
        var path = btn.dataset.path;
        var item = btn.closest('.gallery-item');

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus foto ini?',
                text: 'Gambar akan dihapus permanen dari fasilitas ini.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (!result.isConfirmed) return;
                deletePhoto(item, id, path);
            });
        } else {
            if (!confirm('Hapus foto ini?')) return;
            deletePhoto(item, id, path);
        }
    });

    function deletePhoto(item, photoId, imagePath) {
        fetch('{{ route('admin.facilities.delete-image', ':id') }}'.replace(':id', facilityId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ photo_id: photoId, image_path: imagePath })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                item.remove();
                if (typeof Swal !== 'undefined') Swal.fire({ title: 'Terhapus!', text: data.message, icon: 'success', timer: 1500, showConfirmButton: false });
            } else {
                alert(data.message || 'Gagal menghapus foto.');
            }
        })
        .catch(function(err) {
            console.error(err);
            alert('Gagal menghapus foto.');
        });
    }
})();

// Load SweetAlert2 jika belum tersedia
(function() {
    if (window.Swal) return;
    var s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
    document.head.appendChild(s);
})();
</script>
@endpush
