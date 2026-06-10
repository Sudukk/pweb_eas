@extends('layouts.app')
@section('title', 'Pengembalian Alat')
@section('breadcrumb')
    <li class="breadcrumb-item active">Pengembalian</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Pengembalian Alat</h4>
</div>
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control form-control-sm"
                    placeholder="Cari kode pinjam / nama mahasiswa..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary">Cari</button>
                <a href="{{ route('admin.peminjaman.pengembalian') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-light">
                    <tr><th>Kode</th><th>Mahasiswa</th><th>Alat</th><th>Tgl Kembali Rencana</th><th>Keterlambatan</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $p)
                    @php $terlambat = now()->gt($p->tanggal_kembali_rencana); @endphp
                    <tr class="{{ $terlambat ? 'table-warning' : '' }}">
                        <td><code class="small">{{ $p->kode_pinjam }}</code></td>
                        <td class="small">{{ $p->user->name }}</td>
                        <td class="small">{{ $p->detail->pluck('alat.nama')->implode(', ') }}</td>
                        <td class="small">{{ $p->tanggal_kembali_rencana->format('d/m/Y') }}</td>
                        <td>
                            @if($terlambat)
                            <span class="badge bg-danger">
                                {{ now()->diffInDays($p->tanggal_kembali_rencana) }} hari
                            </span>
                            @else
                            <span class="badge bg-success">Tepat waktu</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.peminjaman.formKembali', $p) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-arrow-return-left me-1"></i>Proses
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada peminjaman yang sedang dipinjam.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($peminjaman->hasPages())
    <div class="card-footer bg-white">{{ $peminjaman->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
@endsection
