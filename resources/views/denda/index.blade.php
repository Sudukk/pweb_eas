@extends('layouts.app')
@section('title', 'Denda Saya')
@section('content')

<h5 class="fw-bold mb-3">Denda Saya</h5>

@if(auth()->user()->is_blacklisted)
<div class="alert alert-danger">
    <i class="bi bi-ban me-2"></i>
    <strong>Akun Anda diblacklist</strong> karena ada denda yang belum lunas.
    Hubungi admin untuk melakukan pembayaran.
</div>
@endif

@if($totalBelumLunas > 0)
<div class="alert alert-warning">
    Total denda belum lunas: <strong>Rp {{ number_format($totalBelumLunas, 0, ',', '.') }}</strong>
</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Kode Pinjam</th>
                        <th class="d-none d-md-table-cell">Keterangan</th>
                        <th>Nominal</th>
                        <th>Status</th>
                        <th class="d-none d-sm-table-cell">Dibayar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($denda as $d)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><code class="small">{{ $d->peminjaman->kode_pinjam }}</code></td>
                        <td class="small d-none d-md-table-cell">{{ $d->keterangan ?? $d->jenis }}</td>
                        <td class="text-nowrap small">Rp {{ number_format($d->nominal, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $d->status == 'lunas' ? 'bg-success':'bg-danger' }}">
                                {{ $d->status == 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                            </span>
                        </td>
                        <td class="small text-muted d-none d-sm-table-cell">{{ $d->dibayar_at?->format('d/m/Y') ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-3">Tidak ada denda. Bagus!</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($denda->hasPages())
    <div class="card-footer bg-white">{{ $denda->links() }}</div>
    @endif
</div>
@endsection
