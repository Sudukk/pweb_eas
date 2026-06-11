@extends('layouts.app')
@section('title', 'Detail Peminjaman')
@section('content')

<a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
    <i class="bi bi-arrow-left me-1"></i>Kembali
</a>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="fw-semibold">Detail Peminjaman</span>
                @include('components.status-badge', ['status' => $peminjaman->status])
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0">
                        <tr><th width="40%">Kode Pinjam</th><td><code>{{ $peminjaman->kode_pinjam }}</code></td></tr>
                        <tr><th>Tanggal Pinjam</th><td>{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</td></tr>
                        <tr><th>Rencana Kembali</th><td>{{ $peminjaman->tanggal_kembali_rencana->format('d M Y') }}</td></tr>
                        @if($peminjaman->tanggal_kembali_aktual)
                        <tr><th>Kembali Aktual</th><td>{{ $peminjaman->tanggal_kembali_aktual->format('d M Y') }}</td></tr>
                        @endif
                        <tr><th>Keperluan</th><td>{{ $peminjaman->keperluan }}</td></tr>
                        @if($peminjaman->catatan_penolakan)
                        <tr><th>Alasan Ditolak</th><td class="text-danger">{{ $peminjaman->catatan_penolakan }}</td></tr>
                        @endif
                        @if($peminjaman->adminReviewer)
                        <tr><th>Diproses Oleh</th><td>{{ $peminjaman->adminReviewer->name }}</td></tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Daftar Alat</div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr><th>Alat</th><th>Jumlah</th><th>Kondisi Kembali</th></tr>
                    </thead>
                    <tbody>
                        @foreach($peminjaman->detail as $d)
                        <tr>
                            <td>{{ $d->alat->nama }}</td>
                            <td>{{ $d->jumlah }}</td>
                            <td>{{ $d->kondisi_saat_kembali ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($peminjaman->denda->isNotEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-danger text-white fw-semibold">Denda</div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr><th>Keterangan</th><th>Nominal</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @foreach($peminjaman->denda as $d)
                        <tr>
                            <td>{{ $d->keterangan ?? $d->jenis }}</td>
                            <td class="text-nowrap">Rp {{ number_format($d->nominal, 0, ',', '.') }}</td>
                            <td><span class="badge {{ $d->status == 'lunas' ? 'bg-success':'bg-danger' }}">{{ $d->status }}</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Status Approval</div>
            <div class="card-body">
                @if($peminjaman->adminReviewer)
                    <p class="text-success mb-1"><i class="bi bi-check-circle me-1"></i>Diproses oleh admin</p>
                    <small class="text-muted">{{ $peminjaman->admin_reviewed_at?->format('d/m/Y H:i') }}</small>
                @else
                    <p class="text-warning mb-0"><i class="bi bi-hourglass me-1"></i>Menunggu persetujuan admin</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
