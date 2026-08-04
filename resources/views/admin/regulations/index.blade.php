@extends('admin.layouts.admin')

@section('title', 'Peraturan Desa')
@section('breadcrumb', 'Peraturan')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
    <p style="color:var(--text-muted);font-size:0.85rem;">{{ $regulations->total() }} peraturan</p>
    <a href="{{ route('admin.regulations.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Peraturan</a>
</div>
<div class="card">
    <div class="card-body">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Tipe</th>
                        <th>Tanggal</th>
                        <th>File</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($regulations as $r)
                    <tr>
                        <td>{{ $r->judul }}</td>
                        <td><span class="badge badge-warning">{{ $r->tipe }}</span></td>
                        <td style="font-size:0.75rem;">{{ $r->tanggal?->format('d M Y') ?? '-' }}</td>
                        <td>
                            @if($r->file_path)
                            <a href="{{ asset('storage/' . ltrim($r->file_path, '/')) }}" target="_blank" style="color:var(--accent);font-size:0.75rem;"><i class="fas fa-file-pdf"></i> Lihat</a>
                            @else
                            <span style="color:var(--text-muted);font-size:0.7rem;">-</span>
                            @endif
                        </td>
                        <td><span class="badge {{ $r->active ? 'badge-success' : 'badge-danger' }}">{{ $r->active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('admin.regulations.edit', $r) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="{{ route('admin.regulations.destroy', $r) }}" onsubmit="return confirm('Hapus peraturan ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:2rem;color:var(--text-muted);">Belum ada peraturan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $regulations->links() }}</div>
    </div>
</div>
@endsection
