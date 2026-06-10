@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('content')

<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-primary">{{ $totalAlat }}</div>
            <div class="text-muted small">Total Alat</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-warning">{{ $menunggu }}</div>
            <div class="text-muted small">Menunggu</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-success">{{ $dipinjam }}</div>
            <div class="text-muted small">Dipinjam</div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold">{{ $totalUser }}</div>
            <div class="text-muted small">Users</div>
        </div>
    </div>
    <div class="col-12 col-md-8 col-lg">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-5 fw-bold text-danger">Rp {{ number_format($dendaBelumLunas, 0, ',', '.') }}</div>
            <div class="text-muted small">Denda Belum Lunas</div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Peminjaman Terbaru</span>
        <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Kode</th><th>Peminjam</th><th class="d-none d-sm-table-cell">Tgl Pinjam</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($peminjamanTerbaru as $p)
                    <tr>
                        <td><code class="small">{{ $p->kode_pinjam }}</code></td>
                        <td class="small">{{ $p->user->name }}</td>
                        <td class="small d-none d-sm-table-cell">{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td>@include('components.status-badge', ['status' => $p->status])</td>
                        <td><a href="{{ route('admin.peminjaman.show', $p) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-3">Belum ada peminjaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
