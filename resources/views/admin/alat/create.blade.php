@extends('layouts.app')
@section('title', 'Tambah Alat')
@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Tambah Alat</div>
            <div class="card-body">
                <form action="{{ route('admin.alat.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">

                        {{-- Area unggah foto --}}
                        <div class="col-12">
                            <label class="form-label">Foto Alat <small class="text-muted">(opsional, jpg/png maks 2MB)</small></label>
                            <div class="d-flex gap-3 align-items-start">
                                <div id="previewWrap" class="border rounded bg-light d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width:120px;height:120px;cursor:pointer;overflow:hidden"
                                     onclick="document.getElementById('fotoInput').click()">
                                    <div id="previewPlaceholder" class="text-center text-muted small p-2">
                                        <i class="bi bi-camera fs-3 d-block mb-1"></i>
                                        Klik untuk pilih foto
                                    </div>
                                    <img id="previewImg" src="" alt="Preview" class="d-none"
                                         style="width:120px;height:120px;object-fit:cover">
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" name="foto" id="fotoInput"
                                           class="form-control @error('foto') is-invalid @enderror"
                                           accept=".jpg,.jpeg,.png">
                                    @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <div class="form-text">Format: JPG, JPEG, PNG. Ukuran maksimal 2MB.</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Kode Alat <span class="text-danger">*</span></label>
                            <input type="text" name="kode_alat" class="form-control @error('kode_alat') is-invalid @enderror"
                                   value="{{ old('kode_alat') }}" required placeholder="Contoh: ALT-001">
                            @error('kode_alat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nama Alat <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                   value="{{ old('nama') }}" required>
                            @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="2">{{ old('deskripsi') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_total" class="form-control @error('jumlah_total') is-invalid @enderror"
                                   value="{{ old('jumlah_total', 1) }}" min="1" required>
                            @error('jumlah_total')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kondisi <span class="text-danger">*</span></label>
                            <select name="kondisi" class="form-select" required>
                                <option value="baik" {{ old('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="rusak_ringan" {{ old('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                <option value="maintenance" {{ old('kondisi') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('admin.alat.index') }}" class="btn btn-outline-secondary">Batal</a>
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
