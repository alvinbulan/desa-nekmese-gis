@extends('admin.layouts.admin')

@section('title', isset($announcement) ? 'Edit Pengumuman' : 'Tambah Pengumuman')
@section('breadcrumb', '<a href="' . route('admin.announcements.index') . '" style="color:var(--accent);text-decoration:none;">Pengumuman</a> / ' . (isset($announcement) ? 'Edit' : 'Tambah'))

@section('content')
<div class="card" style="max-width:640px;">
    <div class="card-header">{{ isset($announcement) ? 'Edit Pengumuman' : 'Tambah Pengumuman Baru' }}</div>
    <div class="card-body">
        <form method="POST" action="{{ isset($announcement) ? route('admin.announcements.update', $announcement) : route('admin.announcements.store') }}" enctype="multipart/form-data">
            @csrf
            @if(isset($announcement)) @method('PUT') @endif

            <div class="form-group">
                <label>Judul</label>
                <input type="text" name="judul" class="form-control" value="{{ old('judul', $announcement->judul ?? '') }}" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Tipe</label>
                    <select name="tipe" class="form-control" required>
                        <option value="pengumuman" {{ (old('tipe', $announcement->tipe ?? '') === 'pengumuman') ? 'selected' : '' }}>Pengumuman</option>
                        <option value="kegiatan" {{ (old('tipe', $announcement->tipe ?? '') === 'kegiatan') ? 'selected' : '' }}>Kegiatan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $announcement->tanggal ?? '') }}">
                </div>
            </div>

            <div class="form-group">
                <label>Isi</label>
                <textarea name="isi" class="form-control" rows="5">{{ old('isi', $announcement->isi ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label>Gambar</label>
                <input type="file" name="gambar" class="form-control" accept="image/*" onchange="preview(this, 'preview')">
                @if(isset($announcement) && $announcement->gambar)
                 <img src="{{ $announcement->gambar_url }}" class="preview-img" id="preview">
                @else
                <img style="display:none;" class="preview-img" id="preview">
                @endif
            </div>

            <div class="form-group">
                <label class="form-check">
                    <input type="checkbox" name="active" value="1" {{ old('active', $announcement->active ?? true) ? 'checked' : '' }}> Aktif
                </label>
            </div>

            <div style="display:flex;gap:0.5rem;">
                <button type="submit" class="btn btn-primary">{{ isset($announcement) ? 'Simpan Perubahan' : 'Simpan' }}</button>
                <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
<script>
function preview(input, id) {
    var img = document.getElementById(id);
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) { img.src = e.target.result; img.style.display = 'block'; };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
