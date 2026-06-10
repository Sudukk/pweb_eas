@extends('layouts.app')
@section('title', 'Proses Pengembalian')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.peminjaman.pengembalian') }}">Pengembalian</a></li>
    <li class="breadcrumb-item active">{{ $peminjaman->kode_pinjam }}</li>
@endsection
@section('content')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3"><h6 class="fw-bold mb-0">Info Peminjaman</h6></div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between">
                    <span class="text-muted small">Kode</span>
                    <code>{{ $peminjaman->kode_pinjam }}</code>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span class="text-muted small">Mahasiswa</span>
                    <span class="small fw-semibold">{{ $peminjaman->user->name }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span class="text-muted small">Tgl Pinjam</span>
                    <span class="small">{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span class="text-muted small">Rencana Kembali</span>
                    <span class="small {{ now()->gt($peminjaman->tanggal_kembali_rencana) ? 'text-danger fw-bold' : '' }}">
                        {{ $peminjaman->tanggal_kembali_rencana->format('d/m/Y') }}
                    </span>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3"><h6 class="fw-bold mb-0">Form Pengembalian</h6></div>
            <div class="card-body">
                <form action="{{ route('admin.peminjaman.prosesKembali', $peminjaman) }}" method="POST">
                    @csrf
                    @if($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                    @endif
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Tanggal Kembali Aktual <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_kembali_aktual" class="form-control"
                            value="{{ old('tanggal_kembali_aktual', now()->format('Y-m-d')) }}" required>
                    </div>

                    <h6 class="fw-bold mb-3">Kondisi Alat Saat Dikembalikan</h6>
                    @foreach($peminjaman->detail as $detail)
                    <div class="card border mb-3">
                        <div class="card-header bg-light py-2">
                            <strong>{{ $detail->alat->nama }}</strong>
                            <span class="text-muted ms-2">(<code>{{ $detail->alat->kode_alat }}</code>)</span>
                            <span class="badge bg-secondary ms-2">Jumlah: {{ $detail->jumlah }}</span>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Kondisi <span class="text-danger">*</span></label>
                                    <select name="kondisi[{{ $detail->id }}][kondisi]" class="form-select kondisi-select" required
                                        data-detail="{{ $detail->id }}">
                                        <option value="baik">Baik</option>
                                        <option value="rusak">Rusak</option>
                                        <option value="hilang">Hilang</option>
                                    </select>
                                </div>
                                <div class="col-md-4 tingkat-wrapper d-none" id="tingkat-{{ $detail->id }}">
                                    <label class="form-label fw-semibold">Tingkat Kerusakan</label>
                                    <select name="kondisi[{{ $detail->id }}][tingkat]" class="form-select">
                                        <option value="ringan">Ringan</option>
                                        <option value="berat">Berat</option>
                                    </select>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold">Catatan</label>
                                    <input type="text" name="kondisi[{{ $detail->id }}][catatan]"
                                        class="form-control" placeholder="Catatan kondisi (opsional)">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary"
                            onclick="return confirm('Konfirmasi proses pengembalian ini?')">
                            <i class="bi bi-check2-circle me-1"></i>Konfirmasi Pengembalian
                        </button>
                        <a href="{{ route('admin.peminjaman.pengembalian') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.querySelectorAll('.kondisi-select').forEach(sel => {
    sel.addEventListener('change', function() {
        const id = this.dataset.detail;
        const wrapper = document.getElementById('tingkat-' + id);
        if (this.value === 'rusak') {
            wrapper.classList.remove('d-none');
        } else {
            wrapper.classList.add('d-none');
        }
    });
});
</script>
@endpush
