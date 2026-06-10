@extends('layouts.app')
@section('title', 'Detail Peminjaman')
@section('content')

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="fw-semibold">Detail Peminjaman</span>
                @include('components.status-badge', ['status' => $peminjaman->status])
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0">
                        <tr><th width="40%">Kode</th><td><code>{{ $peminjaman->kode_pinjam }}</code></td></tr>
                        <tr><th>Peminjam</th><td>{{ $peminjaman->user->name }} <span class="badge bg-secondary">{{ $peminjaman->user->role }}</span></td></tr>
                        <tr><th>Tgl Pinjam</th><td>{{ $peminjaman->tanggal_pinjam->format('d M Y') }}</td></tr>
                        <tr><th>Rencana Kembali</th><td>{{ $peminjaman->tanggal_kembali_rencana->format('d M Y') }}</td></tr>
                        @if($peminjaman->tanggal_kembali_aktual)
                        <tr><th>Kembali Aktual</th><td>{{ $peminjaman->tanggal_kembali_aktual->format('d M Y') }}</td></tr>
                        @endif
                        <tr><th>Keperluan</th><td>{{ $peminjaman->keperluan }}</td></tr>
                        @if($peminjaman->catatan_penolakan)
                        <tr><th>Alasan Tolak</th><td class="text-danger">{{ $peminjaman->catatan_penolakan }}</td></tr>
                        @endif
                        @if($peminjaman->dokumen_pendukung)
                        <tr><th>Dokumen</th><td><a href="{{ Storage::url($peminjaman->dokumen_pendukung) }}" target="_blank">Lihat Dokumen</a></td></tr>
                        @endif
                        @if($peminjaman->adminReviewer)
                        <tr><th>Diproses oleh</th><td>{{ $peminjaman->adminReviewer->name }}</td></tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Daftar Alat</div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light"><tr><th>Alat</th><th>Jml</th><th>Kondisi Kembali</th></tr></thead>
                    <tbody>
                        @foreach($peminjaman->detail as $d)
                        <tr>
                            <td>{{ $d->alat->nama }} <small class="text-muted d-block d-sm-inline">({{ $d->alat->kode_alat }})</small></td>
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
            <div class="card-header bg-white fw-semibold">Denda</div>
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light"><tr><th>Keterangan</th><th>Nominal</th><th>Status</th></tr></thead>
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

    <div class="col-lg-5">
        @if($peminjaman->status === 'menunggu')
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Aksi</div>
            <div class="card-body d-grid gap-2">
                <form action="{{ route('admin.peminjaman.approve', $peminjaman) }}" method="POST">
                    @csrf
                    <button class="btn btn-success w-100" onclick="return confirm('Setujui peminjaman ini?')">
                        <i class="bi bi-check-lg me-1"></i>Setujui
                    </button>
                </form>
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalTolak">
                    <i class="bi bi-x-lg me-1"></i>Tolak
                </button>
            </div>
        </div>

        <div class="modal fade" id="modalTolak" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tolak Peminjaman</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.peminjaman.reject', $peminjaman) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="catatan_penolakan" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Tolak</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @elseif($peminjaman->status === 'dipinjam')
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <a href="{{ route('admin.peminjaman.kembali', $peminjaman) }}" class="btn btn-warning w-100">
                    <i class="bi bi-arrow-return-left me-1"></i>Proses Pengembalian
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<a href="{{ route('admin.peminjaman.index') }}" class="btn btn-outline-secondary btn-sm mt-3">
    <i class="bi bi-arrow-left me-1"></i>Kembali
</a>
@endsection
