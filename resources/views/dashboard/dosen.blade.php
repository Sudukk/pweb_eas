@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-primary">{{ $aktif }}</div>
            <div class="text-muted small">Peminjaman Aktif</div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-success">{{ $selesai }}</div>
            <div class="text-muted small">Selesai</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold {{ $denda > 0 ? 'text-danger' : 'text-success' }}">
                Rp {{ number_format($denda, 0, ',', '.') }}
            </div>
            <div class="text-muted small">Denda Belum Lunas</div>
        </div>
    </div>
</div>

@if(auth()->user()->is_blacklisted)
<div class="alert alert-danger">
    <i class="bi bi-exclamation-triangle me-2"></i>
    <strong>Akun diblacklist!</strong> Lunasi denda Anda terlebih dahulu.
    <a href="{{ route('denda.index') }}" class="alert-link">Lihat denda →</a>
</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-semibold">Peminjaman Terakhir</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th class="d-none d-sm-table-cell">Tgl Pinjam</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $p)
                    <tr>
                        <td><code class="small">{{ $p->kode_pinjam }}</code></td>
                        <td class="small d-none d-sm-table-cell">{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td>@include('components.status-badge', ['status' => $p->status])</td>
                        <td><a href="{{ route('peminjaman.show', $p) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">Belum ada peminjaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white d-flex flex-wrap gap-2">
        <a href="{{ route('peminjaman.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Ajukan Peminjaman Baru
        </a>
        <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary btn-sm">Lihat Semua</a>
    </div>
</div>
@endsection
