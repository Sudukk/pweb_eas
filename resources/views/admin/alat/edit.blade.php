@extends('layouts.app')
@section('title', 'Edit Alat')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Edit Alat | {{ $alat->nama }}</div>
            <div class="card-body">
                <form action="{{ route('admin.alat.update', $alat) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">

                        {{-- Foto upload area --}}
                        <div class="col-12">
                            <label class="form-label">Foto Alat <small class="text-muted">(biarkan kosong jika tidak ingin mengubah)</small></label>
                            <div class="d-flex gap-3 align-items-start">
                                <div id="previewWrap" class="border rounded bg-light d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width:120px;height:120px;cursor:pointer;overflow:hidden;position:relative"
                                     onclick="document.getElementById('fotoInput').click()">
                                    @if($alat->foto)
                                        <img id="previewImg" src="{{ Storage::url($alat->foto) }}" alt="{{ $alat->nama }}"
                                             style="width:120px;height:120px;object-fit:cover">
                                        <div id="previewPlaceholder" class="d-none text-center text-muted small p-2">
                                            <i class="bi bi-camera fs-3 d-block mb-1"></i>
                                            Klik untuk pilih foto
                                        </div>
                                    @else
                                        <div id="previewPlaceholder" class="text-center text-muted small p-2">
                                            <i class="bi bi-camera fs-3 d-block mb-1"></i>
                                            Klik untuk pilih foto
                                        </div>
                                        <img id="previewImg" src="" alt="Preview" class="d-none"
                                             style="width:120px;height:120px;object-fit:cover">
                                    @endif
                                    <div style="position:absolute;bottom:4px;right:4px">
                                        <span class="badge bg-dark bg-opacity-50" style="font-size:10px">
                                            <i class="bi bi-pencil"></i> Ganti
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" name="foto" id="fotoInput"
                                           class="form-control @error('foto') is-invalid @enderror"
                                           accept=".jpg,.jpeg,.png">
                                    @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <div class="form-text">Format: JPG, JPEG, PNG. Ukuran maksimal 2MB.</div>
                                    @if($alat->foto)
                                        <div class="form-text text-success">
                                            <i class="bi bi-check-circle me-1"></i>Sudah ada foto tersimpan.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kode Alat <span class="text-danger">*</span></label>
                            <input type="text" name="kode_alat" class="form-control @error('kode_alat') is-invalid @enderror"
                                   value="{{ old('kode_alat', $alat->kode_alat) }}" required>
                            @error('kode_alat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nama Alat <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                   value="{{ old('nama', $alat->nama) }}" required>
                            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="2">{{ old('deskripsi', $alat->deskripsi) }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jumlah Total</label>
                            <input type="number" name="jumlah_total" class="form-control"
                                   value="{{ old('jumlah_total', $alat->jumlah_total) }}" min="1" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jumlah Tersedia</label>
                            <input type="number" name="jumlah_tersedia" class="form-control"
                                   value="{{ old('jumlah_tersedia', $alat->jumlah_tersedia) }}" min="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kondisi</label>
                            <select name="kondisi" class="form-select">
                                <option value="baik" {{ old('kondisi', $alat->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="rusak_ringan" {{ old('kondisi', $alat->kondisi) == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                <option value="maintenance" {{ old('kondisi', $alat->kondisi) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('admin.alat.show', $alat) }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('fotoInput').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('previewImg').classList.remove('d-none');
        document.getElementById('previewPlaceholder').classList.add('d-none');
    };
    reader.readAsDataURL(file);
});
</script>
@endpush
@endsection
