@extends('admin.layouts.admin')

@section('title', 'Fasilitas')
@section('breadcrumb', 'Fasilitas')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;flex-wrap:wrap;gap:0.75rem;">
    <p style="color:var(--text-muted);font-size:0.85rem;">{{ $facilities->total() }} fasilitas terdaftar</p>
    <a href="{{ route('admin.facilities.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Fasilitas</a>
</div>

<div style="display:flex;gap:0.75rem;margin-bottom:1rem;flex-wrap:wrap;">
    <form method="GET" action="{{ route('admin.facilities.index') }}" style="display:flex;gap:0.5rem;flex:1;flex-wrap:wrap;">
        <div style="position:relative;flex:1;min-width:180px;">
            <input type="text" name="search" placeholder="Cari fasilitas..." value="{{ request('search') }}" class="form-control" style="padding-left:2rem;">
            <i class="fas fa-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:0.75rem;"></i>
        </div>
        <select name="kategori" class="form-control" style="width:auto;min-width:150px;">
            <option value="">Semua Kategori</option>
            @foreach($kategoriList as $key => $label)
            <option value="{{ $key }}" {{ request('kategori') === $key ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <button class="btn btn-outline btn-sm" type="submit"><i class="fas fa-filter"></i> Filter</button>
        @if(request('search') || request('kategori'))
        <a href="{{ route('admin.facilities.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-times"></i> Reset</a>
        @endif
    </form>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Koordinat</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facilities as $f)
                    <tr>
                        <td><strong>{{ $f->nama }}</strong></td>
                        <td><span class="badge badge-success">{{ $kategoriList[$f->kategori] ?? $f->kategori }}</span></td>
                        <td style="font-size:0.75rem;color:var(--text-muted);">{{ $f->latitude }}, {{ $f->longitude }}</td>
                        <td>
                            @if($f->active)
                            <span class="badge badge-success">Aktif</span>
                            @else
                            <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('admin.facilities.edit', $f) }}" class="btn btn-primary btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="{{ route('admin.facilities.destroy', $f) }}" onsubmit="return confirm('Hapus fasilitas &quot;{{ $f->nama }}&quot;?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--text-muted);">Belum ada fasilitas. <a href="{{ route('admin.facilities.create') }}" style="color:var(--accent);">Tambah sekarang</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination">{{ $facilities->links() }}</div>
    </div>
</div>
@endsection
