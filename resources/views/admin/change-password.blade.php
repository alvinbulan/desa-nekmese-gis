@extends('admin.layouts.admin')

@section('title', 'Ganti Password')
@section('breadcrumb', 'Pengaturan / Ganti Password')

@section('content')
<div class="card" style="max-width:560px;">
    <div class="card-header">
        <span><i class="fas fa-key" style="margin-right:6px;"></i> Ganti Password Admin</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.password.update') }}">
            @csrf

            {{-- Password Saat Ini --}}
            <div class="form-group">
                <label for="current_password">Password Saat Ini</label>
                <input type="password" name="current_password" id="current_password"
                       class="form-control @error('current_password') alert-error @enderror"
                       placeholder="Masukkan password lama" required autofocus>
                @error('current_password')
                    <span style="display:block;color:#dc2626;font-size:0.7rem;margin-top:0.35rem;">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </span>
                @enderror
            </div>

            {{-- Password Baru --}}
            <div class="form-group">
                <label for="new_password">Password Baru</label>
                <input type="password" name="new_password" id="new_password"
                       class="form-control" placeholder="Minimal 8 karakter" required>
                <span class="form-hint">Gunakan minimal 8 karakter dengan kombinasi huruf, angka, dan simbol agar lebih aman.</span>
                @error('new_password')
                    <span style="display:block;color:#dc2626;font-size:0.7rem;margin-top:0.35rem;">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </span>
                @enderror
            </div>

            {{-- Konfirmasi Password Baru --}}
            <div class="form-group">
                <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                       class="form-control" placeholder="Ulangi password baru" required>
                @error('new_password_confirmation')
                    <span style="display:block;color:#dc2626;font-size:0.7rem;margin-top:0.35rem;">
                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </span>
                @enderror
            </div>

            <div style="display:flex;gap:0.5rem;margin-top:0.25rem;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Password Baru
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    .form-group .form-control.alert-error { border-color: #fecaca; background: #fff5f5; }
</style>
@endsection