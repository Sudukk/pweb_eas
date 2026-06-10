@extends('layouts.app')
@section('title', 'Proses Pengembalian')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">Info Peminjaman</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th width="30%">Kode</th><td><code>{{ $peminjaman->kode_pinjam }}</code></td></tr>
                    <tr><th>Peminjam</th><td>{{ $peminjaman->user->name }}</td></tr>
                    <tr><th>Rencana Kembali</th>
                        <td class="{{ now()->gt($peminjaman->tanggal_kembali_rencana) ? 'text-danger fw-bold':'' }}">
                            {{ $peminjaman->tanggal_kembali_rencana->format('d M Y') }}
                            @if(now()->gt($peminjaman->tanggal_kembali_rencana))
                                (Terlambat {{ now()->diffInDays($peminjaman->tanggal_kembali_rencana) }} hari)
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Form Pengembalian</div>
            <div class="card-body">
                <form action="{{ route('admin.peminjaman.prosesKembali', $peminjaman) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal Kembali Aktual <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_kembali_aktual" class="form-control"
                               value="{{ old('tanggal_kembali_aktual', now()->format('Y-m-d')) }}" required>
                    </div>

                    <h6 class="fw-semibold mt-4 mb-3">Kondisi Alat Saat Dikembalikan</h6>
                    @foreach($peminjaman->detail as $detail)
                    <div class="border rounded p-3 mb-3">
                        <div class="fw-semibold mb-2">
                            {{ $detail->alat->nama }}
                            <span class="badge bg-secondary ms-1">{{ $detail->jumlah }} unit</span>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label small">Kondisi <span class="text-danger">*</span></label>
                                <select name="kondisi[{{ $detail->id }}][kondisi_kembali]" class="form-select form-select-sm" required>
                                    <option value="baik">Baik</option>
                                    <option value="rusak">Rusak</option>
                                    <option value="hilang">Hilang</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label small">Catatan</label>
                                <input type="text" name="kondisi[{{ $detail->id }}][catatan]" class="form-control form-control-sm"
                                       placeholder="Catatan kondisi (opsional)">
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <div class="border rounded p-3 bg-light mt-3">
                        <h6 class="fw-semibold mb-3">Denda <small class="text-muted fw-normal">(isi jika ada)</small></h6>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label small">Nominal Denda (Rp)</label>
                                <input type="number" name="denda_nominal" class="form-control form-control-sm"
                                       value="{{ old('denda_nominal') }}" min="0" placeholder="0">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label small">Keterangan Denda</label>
                                <input type="text" name="denda_keterangan" class="form-control form-control-sm"
                                       value="{{ old('denda_keterangan') }}" placeholder="Contoh: Keterlambatan 3 hari, kerusakan kamera">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-success"
                                onclick="return confirm('Konfirmasi proses pengembalian?')">
                            Proses Pengembalian
                        </button>
                        <a href="{{ route('admin.peminjaman.show', $peminjaman) }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
