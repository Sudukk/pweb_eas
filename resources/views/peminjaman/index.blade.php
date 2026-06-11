@extends('layouts.app')
@section('title', 'Peminjaman Saya')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Peminjaman Saya</h5>
    <a href="{{ route('peminjaman.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i><span class="d-none d-sm-inline">Ajukan </span>Baru
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Barang Dipinjam</th>
                        <th class="d-none d-sm-table-cell">Tgl Pinjam</th>
                        <th class="d-none d-md-table-cell">Tgl Kembali</th>
                        <th class="d-none d-lg-table-cell">Keperluan</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjaman as $p)
                    <tr>
                        <td>
                            <code class="small">{{ $p->kode_pinjam }}</code>
                            <div class="text-muted d-sm-none" style="font-size:.72rem">{{ $p->tanggal_pinjam->format('d/m/Y') }}</div>
                        </td>
                        <td style="min-width:160px">@include('partials.barang-pinjam', ['detail' => $p->detail])</td>
                        <td class="small d-none d-sm-table-cell">{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td class="small d-none d-md-table-cell">{{ $p->tanggal_kembali_rencana->format('d/m/Y') }}</td>
                        <td class="small d-none d-lg-table-cell">{{ Str::limit($p->keperluan, 40) }}</td>
                        <td>@include('components.status-badge', ['status' => $p->status])</td>
                        <td>
                            <a href="{{ route('peminjaman.show', $p) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-3">Belum ada peminjaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($peminjaman->hasPages())
    <div class="card-footer bg-white">{{ $peminjaman->links() }}</div>
    @endif
</div>
@endsection
