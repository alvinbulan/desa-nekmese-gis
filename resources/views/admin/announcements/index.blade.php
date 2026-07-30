@extends('admin.layouts.admin')

@section('title', 'Pengumuman')
@section('breadcrumb', 'Pengumuman')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
    <p style="color:var(--text-muted);font-size:0.85rem;">{{ $announcements->total() }} pengumuman</p>
    <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Pengumuman</a>
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
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($announcements as $a)
                    <tr>
                        <td>{{ $a->judul }}</td>
                        <td><span class="badge {{ $a->tipe === 'pengumuman' ? 'badge-warning' : 'badge-success' }}">{{ $a->tipe }}</span></td>
                        <td style="font-size:0.75rem;">{{ $a->tanggal?->format('d M Y') ?? '-' }}</td>
                        <td><span class="badge {{ $a->active ? 'badge-success' : 'badge-danger' }}">{{ $a->active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('admin.announcements.edit', $a) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="{{ route('admin.announcements.destroy', $a) }}" onsubmit="return confirm('Hapus pengumuman ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--text-muted);">Belum ada pengumuman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $announcements->links() }}</div>
    </div>
</div>
@endsection
