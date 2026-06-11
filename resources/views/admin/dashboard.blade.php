@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('content')

{{-- ── Statistik Peminjaman Alat ─────────────────────────────────── --}}
<h6 class="fw-bold text-muted small text-uppercase mb-2"><i class="bi bi-tools me-1"></i>Peminjaman Alat</h6>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3 h-100 justify-content-center">
            <div class="fs-2 fw-bold text-primary">{{ $totalAlat }}</div>
            <div class="text-muted small">Total Alat</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3 h-100 justify-content-center">
            <div class="fs-2 fw-bold">{{ $totalPeminjaman }}</div>
            <div class="text-muted small">Total Peminjaman</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3 h-100 justify-content-center">
            <div class="fs-2 fw-bold text-warning">{{ $menunggu }}</div>
            <div class="text-muted small">Menunggu</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3 h-100 justify-content-center">
            <div class="fs-2 fw-bold text-success">{{ $dipinjam }}</div>
            <div class="text-muted small">Dipinjam</div>
        </div>
    </div>
</div>

{{-- ── Statistik Booking Ruangan ─────────────────────────────────── --}}
<h6 class="fw-bold text-muted small text-uppercase mb-2"><i class="bi bi-door-open me-1"></i>Booking Ruangan</h6>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4">
        <div class="card border-0 shadow-sm text-center p-3 h-100 justify-content-center">
            <div class="fs-2 fw-bold text-info">{{ $totalRuangan }}</div>
            <div class="text-muted small">Total Ruangan</div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card border-0 shadow-sm text-center p-3 h-100 justify-content-center">
            <div class="fs-2 fw-bold">{{ $totalBooking }}</div>
            <div class="text-muted small">Total Booking</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card border-0 shadow-sm text-center p-3 h-100 justify-content-center">
            <div class="fs-2 fw-bold text-warning">{{ $bookingMenunggu }}</div>
            <div class="text-muted small">Booking Pending</div>
        </div>
    </div>
</div>

{{-- ── Statistik Umum ────────────────────────────────────────────── --}}
<h6 class="fw-bold text-muted small text-uppercase mb-2"><i class="bi bi-people me-1"></i>Umum</h6>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-6">
        <div class="card border-0 shadow-sm text-center p-3 h-100 justify-content-center">
            <div class="fs-2 fw-bold">{{ $totalUser }}</div>
            <div class="text-muted small">Users</div>
        </div>
    </div>
    <div class="col-6 col-md-6">
        <div class="card border-0 shadow-sm text-center p-3 h-100 justify-content-center">
            <div class="fs-4 fw-bold text-danger">Rp {{ number_format($dendaBelumLunas, 0, ',', '.') }}</div>
            <div class="text-muted small">Denda Belum Lunas</div>
        </div>
    </div>
</div>

{{-- ── Visualisasi data (Chart.js) ─────────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
    <h6 class="fw-bold mb-0">Tren 6 Bulan / Periode</h6>
    <form method="GET" id="rangeForm" class="d-flex align-items-center gap-1">
        <i class="bi bi-funnel text-muted small"></i>
        <select name="range" class="form-select form-select-sm" style="width:auto"
                onchange="document.getElementById('rangeForm').submit()">
            @foreach($rangeOptions as $val => $label)
            <option value="{{ $val }}" {{ $range === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </form>
</div>

<div class="row g-3 mb-4">
    {{-- Grafik 1: Tren Peminjaman Alat --}}
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-tools text-primary me-1"></i>Tren Peminjaman Alat
            </div>
            <div class="card-body">
                <canvas id="chartTrenAlat" height="140"></canvas>
            </div>
        </div>
    </div>
    {{-- Grafik 2: Tren Booking Ruangan --}}
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-door-open text-info me-1"></i>Tren Booking Ruangan
            </div>
            <div class="card-body">
                <canvas id="chartTrenRuangan" height="140"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold">Status Peminjaman Alat</div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="chartStatus" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold">Status Booking Ruangan</div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="chartBookingStatus" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-semibold">Kondisi Alat</div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="chartKondisi" height="220"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">
                <i class="bi bi-trophy text-warning me-1"></i>Top 5 Alat Paling Sering Dipinjam
            </div>
            <div class="card-body">
                @if($topAlatData->isEmpty() || $topAlatData->sum() === 0)
                    <p class="text-center text-muted small mb-0 py-4">Belum ada data peminjaman alat.</p>
                @else
                    <canvas id="chartTopAlat" height="90"></canvas>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Peminjaman Terbaru</span>
        <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Kode</th><th>Peminjam</th><th>Barang Dipinjam</th><th class="d-none d-sm-table-cell">Tgl Pinjam</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($peminjamanTerbaru as $p)
                    <tr>
                        <td><code class="small">{{ $p->kode_pinjam }}</code></td>
                        <td class="small">{{ $p->user->name }}</td>
                        <td style="min-width:160px">@include('partials.barang-pinjam', ['detail' => $p->detail])</td>
                        <td class="small d-none d-sm-table-cell">{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td>@include('components.status-badge', ['status' => $p->status])</td>
                        <td><a href="{{ route('admin.peminjaman.show', $p) }}" class="btn btn-sm btn-outline-primary">Detail</a></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-3">Belum ada peminjaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
(function () {
    const trenLabels = @json($trenLabels);
    const trenOptions = {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    };

    // Grafik 1: Tren Peminjaman Alat
    new Chart(document.getElementById('chartTrenAlat'), {
        type: 'bar',
        data: {
            labels: trenLabels,
            datasets: [{
                label: 'Peminjaman Alat',
                data: @json($trenAlat),
                backgroundColor: '#1e3a5f',
                borderRadius: 6,
                maxBarThickness: 36,
            }]
        },
        options: trenOptions
    });

    // Grafik 2: Tren Booking Ruangan
    new Chart(document.getElementById('chartTrenRuangan'), {
        type: 'bar',
        data: {
            labels: trenLabels,
            datasets: [{
                label: 'Booking Ruangan',
                data: @json($trenRuangan),
                backgroundColor: '#0dcaf0',
                borderRadius: 6,
                maxBarThickness: 36,
            }]
        },
        options: trenOptions
    });

    const doughnutOptions = {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } }
    };

    // Distribusi status peminjaman alat (doughnut)
    new Chart(document.getElementById('chartStatus'), {
        type: 'doughnut',
        data: {
            labels: ['Menunggu', 'Dipinjam', 'Selesai', 'Ditolak'],
            datasets: [{
                data: [
                    {{ $chartStatus['menunggu'] }},
                    {{ $chartStatus['dipinjam'] }},
                    {{ $chartStatus['selesai'] }},
                    {{ $chartStatus['ditolak'] }}
                ],
                backgroundColor: ['#ffc107', '#198754', '#0d6efd', '#dc3545'],
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: doughnutOptions
    });

    // Distribusi status booking ruangan (doughnut)
    new Chart(document.getElementById('chartBookingStatus'), {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Disetujui', 'Ditolak', 'Dibatalkan'],
            datasets: [{
                data: [
                    {{ $chartBookingStatus['pending'] }},
                    {{ $chartBookingStatus['disetujui'] }},
                    {{ $chartBookingStatus['ditolak'] }},
                    {{ $chartBookingStatus['dibatalkan'] }}
                ],
                backgroundColor: ['#ffc107', '#198754', '#dc3545', '#6c757d'],
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: doughnutOptions
    });

    // Kondisi alat (doughnut)
    new Chart(document.getElementById('chartKondisi'), {
        type: 'doughnut',
        data: {
            labels: ['Baik', 'Rusak Ringan', 'Maintenance'],
            datasets: [{
                data: [
                    {{ $chartKondisi['baik'] }},
                    {{ $chartKondisi['rusak_ringan'] }},
                    {{ $chartKondisi['maintenance'] }}
                ],
                backgroundColor: ['#198754', '#ffc107', '#dc3545'],
                borderWidth: 2,
                borderColor: '#fff',
            }]
        },
        options: doughnutOptions
    });

    // Top 5 alat paling sering dipinjam (horizontal bar)
    const topAlatEl = document.getElementById('chartTopAlat');
    if (topAlatEl) {
        new Chart(topAlatEl, {
            type: 'bar',
            data: {
                labels: @json($topAlatLabels),
                datasets: [{
                    label: 'Total Dipinjam',
                    data: @json($topAlatData),
                    backgroundColor: '#1e3a5f',
                    borderRadius: 6,
                    maxBarThickness: 30,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
            }
        });
    }
})();
</script>
@endpush
@endsection
