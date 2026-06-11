@extends('layouts.app')
@section('title', 'Ajukan Peminjaman')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Form Pengajuan Peminjaman</div>
            <div class="card-body">
                <form action="{{ route('peminjaman.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Pinjam <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pinjam" class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                                   value="{{ old('tanggal_pinjam', now()->format('Y-m-d')) }}"
                                   min="{{ now()->format('Y-m-d') }}" required>
                            @error('tanggal_pinjam')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tanggal Kembali <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_kembali_rencana" class="form-control @error('tanggal_kembali_rencana') is-invalid @enderror"
                                   value="{{ old('tanggal_kembali_rencana') }}" required>
                            @error('tanggal_kembali_rencana')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Keperluan <span class="text-danger">*</span></label>
                            <textarea name="keperluan" class="form-control @error('keperluan') is-invalid @enderror"
                                      rows="3" required>{{ old('keperluan') }}</textarea>
                            @error('keperluan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Dokumen Pendukung <small class="text-muted">(opsional, PDF/JPG, maks 2MB)</small></label>
                            <input type="file" name="dokumen_pendukung" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>

                    <hr>
                    <h6 class="fw-semibold mb-3">Pilih Alat yang Dipinjam <span class="text-danger">*</span></h6>
                    @error('alat')<div class="alert alert-danger py-1 small">{{ $message }}</div>@enderror

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="40">Pilih</th>
                                    <th>Nama Alat</th>
                                    <th>Stok</th>
                                    <th width="100">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($alat as $item)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" name="alat[]" value="{{ $item->id }}"
                                               class="form-check-input alat-check"
                                               data-id="{{ $item->id }}"
                                               {{ in_array($item->id, old('alat', [])) ? 'checked' : '' }}>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($item->foto)
                                                <img src="{{ $item->foto_url }}"
                                                     style="width:36px;height:36px;object-fit:cover"
                                                     class="rounded flex-shrink-0">
                                            @else
                                                <div class="rounded bg-light d-flex align-items-center justify-content-center flex-shrink-0"
                                                     style="width:36px;height:36px">
                                                    <i class="bi bi-tools text-muted small"></i>
                                                </div>
                                            @endif
                                            <div>
                                                {{ $item->nama }}
                                                <div class="text-muted" style="font-size:.75rem">{{ $item->kode_alat }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="small">{{ $item->jumlah_tersedia }}</td>
                                    <td>
                                        <input type="number" name="jumlah[{{ $item->id }}]"
                                               class="form-control form-control-sm"
                                               value="{{ old('jumlah.' . $item->id, 1) }}"
                                               min="1" max="{{ $item->jumlah_tersedia }}">
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="text-center text-muted">Tidak ada alat tersedia.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                        <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
