@extends('layouts.app')
@section('title', 'Approval Peminjaman')
@section('breadcrumb')
    <li class="breadcrumb-item active">Approval</li>
@endsection
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Approval Peminjaman</h4>
    <span class="badge bg-warning text-dark fs-6">{{ $peminjaman->total() }} menunggu</span>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Peminjam</th>
                        <th class="d-none d-md-table-cell">Alat</th>
                        <th class="d-none d-lg-table-cell">Tgl Pinjam</th>
                        <th class="d-none d-lg-table-cell">Waktu Pengajuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $p)
                    <tr>
                        <td><code class="small">{{ $p->kode_pinjam }}</code></td>
                        <td class="small">{{ $p->user->name }}</td>
                        <td class="small d-none d-md-table-cell">{{ $p->detail->pluck('alat.nama')->implode(', ') }}</td>
                        <td class="small d-none d-lg-table-cell">{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td class="small text-muted d-none d-lg-table-cell">{{ $p->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.peminjaman.show', $p) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-check2-circle me-1"></i>Review
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada peminjaman yang menunggu approval admin.</td></tr>
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
