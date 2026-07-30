@extends('admin.layouts.admin')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="icon"><i class="fas fa-building"></i></div>
        <div class="num">{{ $totalFacilities }}</div>
        <div class="label">Total Fasilitas</div>
    </div>
</div>

<div class="card">
    <div class="card-header">Fasilitas Terbaru</div>
    <div class="card-body">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Koordinat</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentFacilities as $f)
                    <tr>
                        <td>{{ $f->nama }}</td>
                        <td>{{ $f->kategori }}</td>
                        <td>{{ $f->latitude }}, {{ $f->longitude }}</td>
                        <td><span class="badge badge-success">Aktif</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;color:var(--text-muted);padding:2rem;">Belum ada data fasilitas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
