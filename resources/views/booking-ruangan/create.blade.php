@extends('layouts.app')
@section('title', 'Booking Ruangan Baru')
@section('content')

@php $role = auth()->user()->role; @endphp

<style>
/* ── Indikator langkah ──────────────────────────────────── */
.step-nav { display:flex; align-items:center; }
.step-node { display:flex; flex-direction:column; align-items:center; gap:4px; }
.step-circle {
    width:36px; height:36px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-weight:700; font-size:.85rem;
    border:2px solid #dee2e6; background:#fff; color:#adb5bd;
    transition:all .2s;
}
.step-circle.active { border-color:#1e3a5f; background:#1e3a5f; color:#fff; }
.step-circle.done   { border-color:#198754; background:#198754; color:#fff; }
.step-label { font-size:.72rem; color:#6c757d; white-space:nowrap; }
.step-label.active { color:#1e3a5f; font-weight:600; }
.step-line { flex:1; height:2px; background:#dee2e6; margin:0 10px 18px; }
.step-line.done { background:#198754; }

/* ── Kartu pratinjau ruangan ────────────────────────────── */
.room-preview {
    border-radius:8px;
    overflow:hidden;
    border:1px solid #dee2e6;
    margin-top:10px;
    display:flex;
    align-items:center;
    gap:14px;
    padding:10px 14px;
    background:#f8f9fc;
}
.room-preview img {
    width:80px; height:56px;
    object-fit:cover;
    border-radius:6px;
    flex-shrink:0;
}
.room-preview-placeholder {
    width:80px; height:56px;
    background:#e9ecef;
    border-radius:6px;
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0;
}

/* ── Pemilih kursi ──────────────────────────────────────── */
.seat-picker {
    background:#fff;
    padding:24px 16px 20px;
    overflow-x:auto;
}
.sp-board {
    width:60%; max-width:360px;
    margin:0 auto 24px;
    text-align:center;
    padding:8px 0;
    border-radius:6px;
    font-size:.78rem; font-weight:600; letter-spacing:.05em;
    background:#e8f5e9; border:2px solid #a5d6a7; color:#2e7d32;
}
.sp-grid {
    display:flex; flex-direction:column;
    gap:8px;
    width:fit-content;
    margin:0 auto;   /* biar grid-nya ke tengah */
}
.sp-row { display:flex; align-items:center; gap:5px; }
.sp-row-lbl {
    width:20px; text-align:center;
    font-size:.7rem; font-weight:700; color:#adb5bd; flex-shrink:0;
}
.sp-aisle { width:18px; flex-shrink:0; }

/* Tombol kursi */
.sp-seat {
    width:38px; height:34px;
    border-radius:6px 6px 3px 3px;
    border:2px solid transparent;
    font-size:.68rem; font-weight:700;
    cursor:pointer;
    display:flex; align-items:center; justify-content:center;
    position:relative; flex-shrink:0;
    transition:transform .1s, box-shadow .1s, background .1s;
    user-select:none;
}
.sp-seat::after {
    content:''; position:absolute;
    bottom:-4px; left:5px; right:5px; height:4px;
    border-radius:0 0 3px 3px;
    background:inherit; filter:brightness(.68);
}
.sp-seat.available { background:#e9ecef; border-color:#ced4da; color:#868e96; }
.sp-seat.available:hover {
    background:#dbeafe; border-color:#93c5fd; color:#1e3a5f;
    transform:translateY(-2px);
    box-shadow:0 4px 10px rgba(30,58,95,.14);
}
.sp-seat.selected {
    background:#1e3a5f; border-color:#1e3a5f; color:#fff;
    transform:translateY(-2px);
    box-shadow:0 4px 12px rgba(30,58,95,.35);
}
.sp-seat.taken {
    background:#fee2e2; border-color:#fca5a5; color:#991b1b;
    cursor:not-allowed; opacity:.75;
}

/* Keterangan warna */
.sp-legend {
    display:flex; gap:20px; flex-wrap:wrap;
    justify-content:center;
    margin-top:18px; padding-top:16px;
    border-top:1px solid #f0f0f0;
}
.sp-legend-item { display:flex; align-items:center; gap:6px; font-size:.78rem; color:#6c757d; }
.sp-legend-dot {
    width:22px; height:17px;
    border-radius:4px 4px 2px 2px;
    border:2px solid transparent;
}

/* Bar ringkasan */
.sp-summary {
    margin-top:16px; padding:10px 16px;
    border-radius:8px; font-size:.84rem;
    background:#f0f4fb; color:#1e3a5f;
    border:1px solid #d0dcf0; min-height:42px;
}

/* Chip info slot */
.slot-chips {
    display:flex; flex-wrap:wrap; gap:8px;
    padding:12px 20px;
    background:#f8f9fc; border-bottom:1px solid #eef0f5;
}
.slot-chip {
    display:flex; align-items:center; gap:5px;
    padding:4px 10px; border-radius:20px;
    background:#fff; border:1px solid #dde1ea;
    font-size:.8rem; color:#1e3a5f; font-weight:500;
}

/* Animasi loading */
.sp-spinner { text-align:center; padding:48px; color:#adb5bd; display:none; }

/* Toolbar pilih semua */
.sp-toolbar { display:flex; justify-content:center; margin-bottom:16px; }
</style>

<div class="row justify-content-center">
<div class="col-lg-9 col-xl-8">

{{-- Indikator langkah --}}
<div class="step-nav mb-4 px-1">
    <div class="step-node">
        <div class="step-circle active" id="dot1">1</div>
        <div class="step-label active" id="lbl1">Detail</div>
    </div>
    <div class="step-line" id="line1"></div>
    <div class="step-node">
        <div class="step-circle" id="dot2">2</div>
        <div class="step-label" id="lbl2">Pilih Kursi</div>
    </div>
</div>

<form action="{{ route('booking-ruangan.store') }}" method="POST" id="formBooking">
@csrf

{{-- ══ LANGKAH 1 ═══════════════════════════════════════════════ --}}
<div id="step1">
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom">
        <h6 class="fw-bold mb-0">Detail Booking</h6>
    </div>
    <div class="card-body">

        {{-- Ruangan --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small text-uppercase text-muted ls-1">Ruangan</label>
            <select name="ruangan_id" id="ruangan_id"
                    class="form-select @error('ruangan_id') is-invalid @enderror" required>
                <option value="">Pilih ruangan...</option>
                @foreach($ruangan as $r)
                <option value="{{ $r->id }}"
                    data-kapasitas="{{ $r->kapasitas_kursi }}"
                    data-nama="{{ $r->nama }}"
                    data-lokasi="{{ $r->lokasi }}"
                    data-foto="{{ $r->foto_url }}"
                    {{ old('ruangan_id') == $r->id ? 'selected' : '' }}>
                    {{ $r->nama }} ({{ $r->kapasitas_kursi }} kursi){{ $r->lokasi ? ' · '.$r->lokasi : '' }}
                </option>
                @endforeach
            </select>
            @error('ruangan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror

            {{-- Kartu pratinjau ruangan --}}
            <div id="ruanganPreview" class="room-preview mt-2" style="display:none">
                <div id="ruanganPreviewImgWrap" class="room-preview-placeholder">
                    <i class="bi bi-building text-secondary"></i>
                </div>
                <div>
                    <div class="fw-semibold" id="previewNama"></div>
                    <div class="small text-muted" id="previewLokasi"></div>
                    <div class="small text-muted" id="previewKapasitas"></div>
                </div>
            </div>
        </div>

        {{-- Tipe --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small text-uppercase text-muted">Tipe Booking</label>
            <select name="tipe" id="tipe"
                    class="form-select @error('tipe') is-invalid @enderror" required>
                @if($role === 'dosen')
                    <option value="dosen"  {{ old('tipe','dosen') === 'dosen' ? 'selected' : '' }}>Dosen (pribadi)</option>
                    <option value="kelas"  {{ old('tipe') === 'kelas'  ? 'selected' : '' }}>Kelas / Mata Kuliah</option>
                @else
                    <option value="mahasiswa" selected>Mahasiswa</option>
                @endif
            </select>
            @error('tipe')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Mata kuliah (khusus tipe kelas) --}}
        <div class="mb-3" id="mkWrap" style="display:none">
            <label class="form-label fw-semibold small text-uppercase text-muted">Mata Kuliah / Nama Kelas</label>
            <input type="text" name="mata_kuliah"
                   class="form-control @error('mata_kuliah') is-invalid @enderror"
                   value="{{ old('mata_kuliah') }}"
                   placeholder="mis. Pemrograman Web - Kelas A">
            @error('mata_kuliah')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Tanggal & Waktu --}}
        <div class="mb-3">
            <label class="form-label fw-semibold small text-uppercase text-muted">Tanggal & Waktu</label>
            <div class="row g-2">
                {{-- Tanggal --}}
                <div class="col-12 col-sm-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-calendar3 text-muted"></i>
                        </span>
                        <input type="date" name="tanggal" id="tanggal"
                               class="form-control border-start-0 @error('tanggal') is-invalid @enderror"
                               value="{{ old('tanggal') }}" min="{{ $minTanggal }}" required>
                    </div>
                    @error('tanggal')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                {{-- Jam mulai s/d jam selesai --}}
                <div class="col-12 col-sm-7">
                    <div class="d-flex align-items-center gap-2">
                        <div class="input-group flex-grow-1">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="bi bi-clock text-muted"></i>
                            </span>
                            <input type="time" name="jam_mulai" id="jam_mulai"
                                   class="form-control border-start-0 @error('jam_mulai') is-invalid @enderror"
                                   value="{{ old('jam_mulai') }}" required>
                        </div>
                        <span class="text-muted small flex-shrink-0 fw-semibold">s/d</span>
                        <input type="time" name="jam_selesai" id="jam_selesai"
                               class="form-control flex-grow-1 @error('jam_selesai') is-invalid @enderror"
                               value="{{ old('jam_selesai') }}" required>
                    </div>
                    @if($errors->has('jam_mulai') || $errors->has('jam_selesai'))
                    <div class="text-danger small mt-1">
                        {{ $errors->first('jam_mulai') ?: $errors->first('jam_selesai') }}
                    </div>
                    @endif
                </div>
            </div>
            <div class="form-text">Minimal H+1</div>
        </div>

        {{-- Keperluan --}}
        <div class="mb-0">
            <label class="form-label fw-semibold small text-uppercase text-muted">Keperluan</label>
            <textarea name="keperluan"
                      class="form-control @error('keperluan') is-invalid @enderror"
                      rows="3" maxlength="500"
                      placeholder="Jelaskan keperluan penggunaan ruangan...">{{ old('keperluan') }}</textarea>
            @error('keperluan')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        @error('kursi_dipilih')
        <div class="alert alert-danger mt-3 py-2 mb-0 small">
            <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
        </div>
        @enderror
    </div>

    <div class="card-footer bg-white d-flex justify-content-between align-items-center">
        <a href="{{ route('booking-ruangan.index') }}" class="btn btn-light">Batal</a>
        <button type="button" class="btn btn-primary px-4" id="btnNext">
            Pilih Kursi
        </button>
    </div>
</div>
</div>

{{-- ══ LANGKAH 2: Pilih Kursi ═════════════════════════════════════ --}}
<div id="step2" style="display:none">
<div class="card border-0 shadow-sm overflow-hidden">

    {{-- Bagian atas --}}
    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0">Pilih Kursi</h6>
        <button type="button" class="btn btn-sm btn-outline-secondary" id="btnBack">
            <i class="bi bi-arrow-left me-1"></i>Ubah Detail
        </button>
    </div>

    {{-- Chip info slot --}}
    <div class="slot-chips" id="slotBar">
        <div class="slot-chip"><i class="bi bi-building" style="color:#1e3a5f"></i><span id="infoRuangan">...</span></div>
        <div class="slot-chip"><i class="bi bi-calendar3" style="color:#1e3a5f"></i><span id="infoTanggal">...</span></div>
        <div class="slot-chip"><i class="bi bi-clock" style="color:#1e3a5f"></i><span id="infoSlot">...</span></div>
    </div>

    {{-- Isi pemilih kursi --}}
    <div class="seat-picker">
        {{-- Papan tulis --}}
        <div class="sp-board"><i class="bi bi-easel2 me-1"></i>PAPAN TULIS / DEPAN KELAS</div>

        {{-- Toolbar pilih semua --}}
        <div class="sp-toolbar" id="spToolbar" style="display:none">
            <button type="button" class="btn btn-sm btn-outline-primary" id="btnSelectAll">
                <i class="bi bi-grid-3x3-gap me-1"></i>Pilih Semua Kursi
            </button>
        </div>

        {{-- Animasi loading --}}
        <div class="sp-spinner" id="spSpinner">
            <div class="spinner-border text-secondary mb-2" style="width:1.8rem;height:1.8rem"></div>
            <div class="small">Memuat denah kursi...</div>
        </div>

        {{-- Denah kursi (ditengahkan lewat margin:0 auto di .sp-grid) --}}
        <div class="sp-grid" id="spGrid"></div>

        {{-- Keterangan warna --}}
        <div class="sp-legend">
            <div class="sp-legend-item">
                <div class="sp-legend-dot" style="background:#e9ecef;border-color:#ced4da"></div>Tersedia
            </div>
            <div class="sp-legend-item">
                <div class="sp-legend-dot" style="background:#1e3a5f;border-color:#1e3a5f"></div>Anda pilih
            </div>
            <div class="sp-legend-item">
                <div class="sp-legend-dot" style="background:#fee2e2;border-color:#fca5a5"></div>Sudah terisi
            </div>
        </div>

        {{-- Ringkasan --}}
        <div class="sp-summary" id="spSummary">
            <i class="bi bi-hand-index me-1 text-secondary"></i>Klik kursi untuk memilih.
        </div>
    </div>

    {{-- Bagian bawah --}}
    <div class="card-footer bg-white d-flex justify-content-end">
        <button type="submit" class="btn btn-primary px-4" id="btnSubmit" disabled>
            <i class="bi bi-send me-1"></i>Ajukan Booking
        </button>
    </div>
</div>
</div>

{{-- Input tersembunyi --}}
<div id="kursiInputs"></div>
</form>

</div>
</div>

@push('scripts')
<script>
(function () {
    const tipe    = document.getElementById('tipe');
    const mkWrap  = document.getElementById('mkWrap');
    const btnNext = document.getElementById('btnNext');
    const btnBack = document.getElementById('btnBack');
    const step1   = document.getElementById('step1');
    const step2   = document.getElementById('step2');

    const dot1  = document.getElementById('dot1');
    const dot2  = document.getElementById('dot2');
    const lbl1  = document.getElementById('lbl1');
    const lbl2  = document.getElementById('lbl2');
    const line1 = document.getElementById('line1');

    const spGrid    = document.getElementById('spGrid');
    const spSpinner = document.getElementById('spSpinner');
    const spSummary = document.getElementById('spSummary');
    const submitBtn = document.getElementById('btnSubmit');
    const kursiBox  = document.getElementById('kursiInputs');
    const spToolbar = document.getElementById('spToolbar');
    const btnSelectAll = document.getElementById('btnSelectAll');

    const SEATS_PER_ROW = 8;
    const AISLE_AFTER   = 4;
    const REFRESH_EVERY = 30;
    const JAM_ALOKASI   = '{{ $jamAlokasi }}';

    let selected    = new Set();
    let takenSeats  = new Set();
    let kapasitas   = 0;
    let lastSlotKey = null;
    let currentSlot = null;

    let seatInterval     = null;
    let refreshCountdown = REFRESH_EVERY;

    /* ── Pratinjau ruangan ──────────────────────────────── */
    const ruanganSel = document.getElementById('ruangan_id');
    const preview    = document.getElementById('ruanganPreview');
    const previewImg = document.getElementById('ruanganPreviewImgWrap');

    function updateRuanganPreview() {
        const opt = ruanganSel.options[ruanganSel.selectedIndex];
        if (!opt || !opt.value) { preview.style.display = 'none'; return; }

        document.getElementById('previewNama').textContent     = opt.dataset.nama || '';
        document.getElementById('previewLokasi').textContent   = opt.dataset.lokasi || '';
        document.getElementById('previewKapasitas').textContent = opt.dataset.kapasitas ? opt.dataset.kapasitas + ' kursi kapasitas' : '';

        const foto = opt.dataset.foto;
        if (foto) {
            previewImg.innerHTML = '<img src="' + foto + '" alt="">';
        } else {
            previewImg.innerHTML = '<i class="bi bi-building text-secondary" style="font-size:1.4rem"></i>';
        }
        preview.style.display = 'flex';
    }
    ruanganSel.addEventListener('change', updateRuanganPreview);
    updateRuanganPreview();

    /* ── Tampilkan/sembunyikan field mata kuliah ────────── */
    function toggleMk() {
        mkWrap.style.display = tipe && tipe.value === 'kelas' ? 'block' : 'none';
    }
    if (tipe) { tipe.addEventListener('change', toggleMk); toggleMk(); }

    /* ── Validasi langkah 1 ────────────────────────────── */
    function validateStep1() {
        const checks = [
            [document.getElementById('ruangan_id'),       'Pilih ruangan terlebih dahulu.'],
            [document.getElementById('tanggal'),           'Isi tanggal terlebih dahulu.'],
            [document.getElementById('jam_mulai'),         'Isi jam mulai terlebih dahulu.'],
            [document.getElementById('jam_selesai'),       'Isi jam selesai terlebih dahulu.'],
            [document.querySelector('[name=keperluan]'),   'Isi keperluan terlebih dahulu.'],
        ];
        for (const [el, msg] of checks) {
            if (!el || !el.value.trim()) { alert(msg); el && el.focus(); return false; }
        }
        if (document.getElementById('jam_selesai').value <= document.getElementById('jam_mulai').value) {
            alert('Jam selesai harus lebih besar dari jam mulai.'); return false;
        }
        return true;
    }

    /* ── Lanjut ke langkah 2 ───────────────────────────── */
    btnNext.addEventListener('click', function () {
        if (!validateStep1()) return;

        const ruangEl  = document.getElementById('ruangan_id');
        const opt      = ruangEl.options[ruangEl.selectedIndex];
        kapasitas      = parseInt(opt.dataset.kapasitas);

        const tanggal  = document.getElementById('tanggal').value;
        const mulai    = document.getElementById('jam_mulai').value;
        const selesai  = document.getElementById('jam_selesai').value;
        const slotKey  = ruangEl.value + '|' + tanggal + '|' + mulai + '|' + selesai;
        const slotSama = slotKey === lastSlotKey;

        currentSlot = { ruanganId: ruangEl.value, tanggal, mulai, selesai };

        document.getElementById('infoRuangan').textContent = opt.dataset.nama;
        document.getElementById('infoTanggal').textContent = new Date(tanggal + 'T00:00').toLocaleDateString('id-ID', {day:'2-digit',month:'short',year:'numeric'});
        document.getElementById('infoSlot').textContent    = mulai + ' – ' + selesai;

        step1.style.display = 'none';
        step2.style.display = '';
        btnNext.innerHTML   = 'Lanjut Pilih Kursi <i class="bi bi-arrow-right ms-1"></i>';

        dot1.className  = 'step-circle done';
        dot1.innerHTML  = '<i class="bi bi-check-lg" style="font-size:.75rem"></i>';
        lbl1.className  = 'step-label';
        dot2.className  = 'step-circle active';
        lbl2.className  = 'step-label active';
        line1.className = 'step-line done';

        if (slotSama) {
            renderGrid(kapasitas);
            renderSummary();
        } else {
            lastSlotKey = slotKey;
            selected.clear();
            syncHiddenInputs();
            renderSummary();
            fetchTaken(ruangEl.value, tanggal, mulai, selesai);
        }

        startLiveBars(tanggal);
    });

    /* ── Kembali ke langkah 1 ──────────────────────────── */
    btnBack.addEventListener('click', function () {
        stopLiveBars();
        step2.style.display = 'none';
        step1.style.display = '';

        dot1.className  = 'step-circle active';
        dot1.innerHTML  = '1';
        lbl1.className  = 'step-label active';
        dot2.className  = 'step-circle';
        lbl2.className  = 'step-label';
        line1.className = 'step-line';

        btnNext.innerHTML = selected.size > 0
            ? 'Lanjut Pilih Kursi <i class="bi bi-arrow-right ms-1"></i> <span class="badge bg-light text-dark border ms-1">' + selected.size + ' kursi dipilih</span>'
            : 'Lanjut Pilih Kursi <i class="bi bi-arrow-right ms-1"></i>';
    });

    /* ── Ambil daftar kursi yang sudah terisi ──────────── */
    function fetchTaken(ruanganId, tanggal, mulai, selesai, silent = false) {
        if (!silent) { spSpinner.style.display = ''; spGrid.innerHTML = ''; }

        const url = "{{ route('booking-ruangan.kursi-terpakai') }}"
            + '?ruangan_id='  + encodeURIComponent(ruanganId)
            + '&tanggal='     + encodeURIComponent(tanggal)
            + '&jam_mulai='   + encodeURIComponent(mulai)
            + '&jam_selesai=' + encodeURIComponent(selesai);

        return fetch(url, {
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(data => {
            takenSeats = new Set((data.terpakai || []).map(Number));
            spSpinner.style.display = 'none';
            renderGrid(kapasitas);
            markRefreshed();
        })
        .catch(() => {
            spSpinner.style.display = 'none';
            if (!silent) {
                spGrid.innerHTML = '<p class="text-danger small text-center">Gagal memuat denah. Coba kembali dan ulangi.</p>';
            }
        });
    }

    /* ── Refresh kursi terpakai otomatis (tanpa loading) ─ */
    function startLiveBars(tanggal) {
        stopLiveBars();
        refreshCountdown = REFRESH_EVERY;
        seatInterval = setInterval(() => {
            refreshCountdown--;
            if (refreshCountdown <= 0 && currentSlot) {
                refreshCountdown = REFRESH_EVERY;
                fetchTaken(currentSlot.ruanganId, currentSlot.tanggal, currentSlot.mulai, currentSlot.selesai, true);
            }
        }, 1000);
    }

    function stopLiveBars() {
        clearInterval(seatInterval);
        seatInterval = null;
    }

    function markRefreshed() {
        refreshCountdown = REFRESH_EVERY;
    }

    /* ── Gambar denah kursi ────────────────────────────── */
    function renderGrid(n) {
        const rows = Math.ceil(n / SEATS_PER_ROW);
        spGrid.innerHTML = '';
        for (let r = 0; r < rows; r++) {
            const rowEl = document.createElement('div');
            rowEl.className = 'sp-row';
            const lbl = document.createElement('span');
            lbl.className = 'sp-row-lbl';
            lbl.textContent = String.fromCharCode(65 + r);
            rowEl.appendChild(lbl);
            for (let c = 1; c <= SEATS_PER_ROW; c++) {
                const no = r * SEATS_PER_ROW + c;
                if (no > n) break;
                if (c === AISLE_AFTER + 1) {
                    const aisle = document.createElement('div');
                    aisle.className = 'sp-aisle';
                    rowEl.appendChild(aisle);
                }
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.dataset.no = no;
                btn.textContent = c;
                btn.title = 'Kursi ' + String.fromCharCode(65 + r) + c;
                btn.className = takenSeats.has(no) ? 'sp-seat taken' : selected.has(no) ? 'sp-seat selected' : 'sp-seat available';
                if (takenSeats.has(no)) btn.disabled = true;
                btn.addEventListener('click', () => toggleSeat(no, btn));
                rowEl.appendChild(btn);
            }
            spGrid.appendChild(rowEl);
        }
        spToolbar.style.display = n > 0 ? 'flex' : 'none';
        updateSelectAllBtn();
    }

    /* Kursi yang bisa dipilih = semua kursi kecuali yang sudah terisi */
    function availableSeats() {
        const list = [];
        for (let no = 1; no <= kapasitas; no++) {
            if (!takenSeats.has(no)) list.push(no);
        }
        return list;
    }

    /* Apakah semua kursi yang tersedia sudah dipilih */
    function allSelected() {
        const avail = availableSeats();
        return avail.length > 0 && avail.every(no => selected.has(no));
    }

    function updateSelectAllBtn() {
        if (!btnSelectAll) return;
        if (allSelected()) {
            btnSelectAll.innerHTML = '<i class="bi bi-x-square me-1"></i>Batalkan Semua';
        } else {
            btnSelectAll.innerHTML = '<i class="bi bi-grid-3x3-gap me-1"></i>Pilih Semua Kursi';
        }
    }

    btnSelectAll.addEventListener('click', function () {
        if (allSelected()) {
            availableSeats().forEach(no => selected.delete(no));
        } else {
            availableSeats().forEach(no => selected.add(no));
        }
        renderGrid(kapasitas);
        syncHiddenInputs();
        renderSummary();
    });

    function toggleSeat(no, btn) {
        if (selected.has(no)) { selected.delete(no); btn.className = 'sp-seat available'; }
        else                  { selected.add(no);    btn.className = 'sp-seat selected'; }
        syncHiddenInputs();
        renderSummary();
        updateSelectAllBtn();
    }

    function syncHiddenInputs() {
        kursiBox.innerHTML = '';
        selected.forEach(no => {
            const inp = document.createElement('input');
            inp.type = 'hidden'; inp.name = 'kursi_dipilih[]'; inp.value = no;
            kursiBox.appendChild(inp);
        });
        submitBtn.disabled = selected.size === 0;
    }

    function renderSummary() {
        if (selected.size === 0) {
            spSummary.innerHTML = '<i class="bi bi-hand-index me-1 text-secondary"></i>Klik kursi untuk memilih.';
            return;
        }
        const labels = [...selected].sort((a,b) => a-b).map(no => {
            const ri = Math.floor((no-1)/SEATS_PER_ROW);
            const c  = ((no-1)%SEATS_PER_ROW)+1;
            return String.fromCharCode(65+ri)+c;
        });
        spSummary.innerHTML =
            '<i class="bi bi-check2-circle me-1"></i>' +
            '<strong>' + selected.size + ' kursi dipilih:</strong> ' +
            labels.map(l => '<span class="badge" style="background:#1e3a5f">' + l + '</span>').join(' ');
    }

    /* ── Pulihkan pilihan kalau validasi server gagal ──── */
    @if(old('kursi_dipilih'))
    (function () {
        @json(old('kursi_dipilih', [])).forEach(no => {
            selected.add(parseInt(no));
            const inp = document.createElement('input');
            inp.type = 'hidden'; inp.name = 'kursi_dipilih[]'; inp.value = no;
            kursiBox.appendChild(inp);
        });
        submitBtn.disabled = false;
    })();
    @endif
})();
</script>
@endpush
@endsection
