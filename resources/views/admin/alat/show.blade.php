@extends('layouts.app')
@section('title', 'Detail Alat')
@section('content')

<a href="{{ route('admin.alat.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
    <i class="bi bi-arrow-left me-1"></i>Kembali
</a>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                @if($alat->foto)
                <img src="{{ $alat->foto_url }}" class="img-fluid rounded mb-3" style="max-height:200px">
                @else
                <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" style="height:200px">
                    <i class="bi bi-tools text-muted fs-1"></i>
                </div>
                @endif
                <h5 class="fw-bold">{{ $alat->nama }}</h5>
                <code class="text-muted">{{ $alat->kode_alat }}</code>
                <div class="mt-2">
                    @php $kMap = ['baik'=>['success','Baik'],'rusak_ringan'=>['warning','Rusak Ringan'],'maintenance'=>['danger','Maintenance']]; @endphp
                    <span class="badge bg-{{ $kMap[$alat->kondisi][0] }}">{{ $kMap[$alat->kondisi][1] }}</span>
                    @if($alat->jumlah_tersedia > 0)
                        <span class="badge bg-success ms-1">Tersedia</span>
                    @else
                        <span class="badge bg-danger ms-1">Habis</span>
                    @endif
                </div>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Total</span><span>{{ $alat->jumlah_total }}</span></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Tersedia</span><span class="fw-bold text-success">{{ $alat->jumlah_tersedia }}</span></li>
                <li class="list-group-item d-flex justify-content-between"><span class="text-muted">Dipinjam</span><span class="fw-bold">{{ $alat->jumlah_total - $alat->jumlah_tersedia }}</span></li>
            </ul>
            <div class="card-footer bg-white">
                <a href="{{ route('admin.alat.edit', $alat) }}" class="btn btn-primary w-100">
                    <i class="bi bi-pencil me-1"></i>Edit
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        @if($alat->deskripsi)
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <h6 class="fw-bold">Deskripsi</h6>
                <p class="text-muted mb-0">{{ $alat->deskripsi }}</p>
            </div>
        </div>
        @endif
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3">
                <h6 class="fw-bold mb-0">Riwayat Peminjaman</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Peminjam</th>
                                <th class="d-none d-sm-table-cell">Jml</th>
                                <th class="d-none d-md-table-cell">Tgl Pinjam</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($alat->peminjamanDetail->take(20) as $d)
                            <tr>
                                <td><code class="small">{{ $d->peminjaman->kode_pinjam }}</code></td>
                                <td class="small">{{ $d->peminjaman->user->name }}</td>
                                <td class="d-none d-sm-table-cell">{{ $d->jumlah }}</td>
                                <td class="small d-none d-md-table-cell">{{ $d->peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
                                <td>@include('components.status-badge', ['status' => $d->peminjaman->status])</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-3">Belum ada riwayat peminjaman.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
