@csrf
<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Kode Ruangan</label>
        <input type="text" name="kode_ruangan" class="form-control"
               value="{{ old('kode_ruangan', $ruangan->kode_ruangan ?? '') }}" required>
    </div>
    <div class="col-md-8 mb-3">
        <label class="form-label">Nama Ruangan</label>
        <input type="text" name="nama" class="form-control"
               value="{{ old('nama', $ruangan->nama ?? '') }}" required>
    </div>
    <div class="col-md-8 mb-3">
        <label class="form-label">Lokasi</label>
        <input type="text" name="lokasi" class="form-control"
               value="{{ old('lokasi', $ruangan->lokasi ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Kapasitas Kursi</label>
        <input type="number" name="kapasitas_kursi" class="form-control" min="1"
               value="{{ old('kapasitas_kursi', $ruangan->kapasitas_kursi ?? '') }}" required>
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="2" maxlength="500">{{ old('deskripsi', $ruangan->deskripsi ?? '') }}</textarea>
    </div>
    <div class="col-12 mb-3">
        <label class="form-label">Foto Ruangan</label>
        <input type="file" name="foto" id="fotoInput" class="form-control" accept="image/*">
        <div class="form-text">Format: JPG, PNG, WebP. Maks 2 MB. Kosongkan jika tidak ingin mengubah foto.</div>
        <div id="fotoPreviewWrap" class="mt-2" style="{{ ($ruangan->foto_url ?? null) ? '' : 'display:none' }}">
            <img id="fotoPreview" src="{{ $ruangan->foto_url ?? '' }}" alt="Preview"
                 class="rounded" style="height:120px;object-fit:cover;border:1px solid #dee2e6">
        </div>
    </div>
    <div class="col-12 mb-3 form-check ms-2">
        <input type="checkbox" name="aktif" value="1" class="form-check-input" id="aktif"
               {{ old('aktif', $ruangan->aktif ?? true) ? 'checked' : '' }}>
        <label class="form-check-label" for="aktif">Ruangan aktif (bisa dibooking)</label>
    </div>
</div>
<div class="d-flex justify-content-end gap-2">
    <a href="{{ route('admin.ruangan.index') }}" class="btn btn-light">Batal</a>
    <button class="btn btn-primary">Simpan</button>
</div>

<script>
document.getElementById('fotoInput').addEventListener('change', function () {
    const wrap = document.getElementById('fotoPreviewWrap');
    const img  = document.getElementById('fotoPreview');
    if (this.files && this.files[0]) {
        img.src = URL.createObjectURL(this.files[0]);
        wrap.style.display = '';
    }
});
</script>
