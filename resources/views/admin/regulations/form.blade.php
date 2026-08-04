@extends('admin.layouts.admin')

@section('title', isset($regulation) ? 'Edit Peraturan' : 'Tambah Peraturan')
@section('breadcrumb', '<a href="' . route('admin.regulations.index') . '" style="color:var(--accent);text-decoration:none;">Peraturan</a> / ' . (isset($regulation) ? 'Edit' : 'Tambah'))

@section('content')
<div class="card" style="max-width:640px;">
    <div class="card-header">{{ isset($regulation) ? 'Edit Peraturan' : 'Tambah Peraturan Baru' }}</div>
    <div class="card-body">
        <form method="POST" action="{{ isset($regulation) ? route('admin.regulations.update', $regulation) : route('admin.regulations.store') }}" enctype="multipart/form-data">
            @csrf
            @if(isset($regulation)) @method('PUT') @endif

            <div class="form-group">
                <label>Judul</label>
                <input type="text" name="judul" class="form-control" value="{{ old('judul', $regulation->judul ?? '') }}" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Tipe</label>
                    <select name="tipe" class="form-control" required>
                        <option value="peraturan" {{ (old('tipe', $regulation->tipe ?? '') === 'peraturan') ? 'selected' : '' }}>Peraturan</option>
                        <option value="keputusan" {{ (old('tipe', $regulation->tipe ?? '') === 'keputusan') ? 'selected' : '' }}>Keputusan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $regulation->tanggal ?? '') }}">
                </div>
            </div>

            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="4">{{ old('deskripsi', $regulation->deskripsi ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label>File (PDF)</label>
                <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx">
                @if(isset($regulation) && $regulation->file_path)
                <div style="margin-top:0.5rem;font-size:0.75rem;">
                    <a href="{{ asset('storage/' . ltrim($regulation->file_path, '/')) }}" target="_blank" style="color:var(--accent);"><i class="fas fa-file-pdf"></i> File saat ini</a>
                </div>
                @endif
                <div class="form-hint">Maks. 10MB. PDF, DOC, DOCX.</div>
            </div>

            <div class="form-group">
                <label class="form-check">
                    <input type="checkbox" name="active" value="1" {{ old('active', $regulation->active ?? true) ? 'checked' : '' }}> Aktif
                </label>
            </div>

            <div style="display:flex;gap:0.5rem;">
                <button type="submit" class="btn btn-primary">{{ isset($regulation) ? 'Simpan Perubahan' : 'Simpan' }}</button>
                <a href="{{ route('admin.regulations.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
