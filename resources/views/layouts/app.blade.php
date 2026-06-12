<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PinjamIn') PinjamIn</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #f5f6fa; }

        /* ── Sidebar ─────────────────────────────────── */
        .sidebar {
            width: 220px;
            min-height: 100vh;
            background: #1e3a5f;
            position: fixed;
            top: 0; left: 0;
            z-index: 1045;
            transition: transform .25s ease;
        }
        .sidebar a {
            display: block;
            color: rgba(255,255,255,.75);
            padding: 10px 20px;
            text-decoration: none;
            font-size: .9rem;
        }
        .sidebar a:hover, .sidebar a.active { color: #fff; background: rgba(255,255,255,.12); }
        .sidebar .menu-label {
            font-size: .7rem;
            text-transform: uppercase;
            color: rgba(255,255,255,.4);
            padding: 14px 20px 4px;
            letter-spacing: .07em;
        }

        /* ── Main area ───────────────────────────────── */
        .main { margin-left: 220px; min-height: 100vh; }
        .topbar {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 10px 20px;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        /* ── Overlay (tampilan HP) ───────────────────── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 1040;
        }
        .sidebar-overlay.show { display: block; }

        /* ── Breakpoint layar kecil ──────────────────── */
        @media (max-width: 767.98px) {
            .sidebar { transform: translateX(-220px); }
            .sidebar.show { transform: translateX(0); }
            .main { margin-left: 0; }
            .topbar { padding: 10px 14px; }
            .main > .p-4 { padding: 1rem !important; }
        }
    </style>
</head>
<body>

{{-- Overlay (ketuk untuk menutup sidebar di HP) --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>

{{-- SIDEBAR --}}
<div class="sidebar" id="sidebar">
    <div class="p-4 border-bottom border-secondary d-flex align-items-center justify-content-between">
        <span class="text-white fw-bold fs-5"><i class="bi bi-box-seam me-2"></i>PinjamIn</span>
        <button class="btn btn-link p-0 d-md-none" id="sidebarClose" style="color:rgba(255,255,255,.6)">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    @php $role = auth()->user()->role; @endphp

    @if($role === 'admin')
        <div class="menu-label">Menu</div>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard
        </a>
        <a href="{{ route('admin.alat.index') }}" class="{{ request()->routeIs('admin.alat.*') ? 'active' : '' }}">
            <i class="bi bi-tools me-2"></i>Alat
        </a>
        <a href="{{ route('admin.peminjaman.index') }}" class="{{ request()->routeIs('admin.peminjaman.*') ? 'active' : '' }}">
            <i class="bi bi-journal-check me-2"></i>Peminjaman
        </a>
        <a href="{{ route('admin.ruangan.index') }}" class="{{ request()->routeIs('admin.ruangan.*') ? 'active' : '' }}">
            <i class="bi bi-door-open me-2"></i>Ruangan
        </a>
        <a href="{{ route('admin.booking-ruangan.index') }}" class="{{ request()->routeIs('admin.booking-ruangan.*') ? 'active' : '' }}">
            <i class="bi bi-grid-3x3-gap me-2"></i>Booking Ruangan
        </a>
        <a href="{{ route('admin.denda.index') }}" class="{{ request()->routeIs('admin.denda.*') ? 'active' : '' }}">
            <i class="bi bi-cash-coin me-2"></i>Denda
        </a>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people me-2"></i>Users
        </a>

    @else
        <div class="menu-label">Menu</div>
        @if($role === 'dosen')
        <a href="{{ route('dosen.dashboard') }}" class="{{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard
        </a>
        @else
        <a href="{{ route('mahasiswa.dashboard') }}" class="{{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard
        </a>
        @endif
        <a href="{{ route('peminjaman.index') }}" class="{{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">
            <i class="bi bi-journal me-2"></i>Peminjaman Saya
        </a>
        <a href="{{ route('peminjaman.create') }}">
            <i class="bi bi-plus-circle me-2"></i>Ajukan Pinjam
        </a>
        <a href="{{ route('booking-ruangan.index') }}" class="{{ request()->routeIs('booking-ruangan.*') ? 'active' : '' }}">
            <i class="bi bi-door-open me-2"></i>Booking Ruangan
        </a>
        <a href="{{ route('denda.index') }}" class="{{ request()->routeIs('denda.*') ? 'active' : '' }}">
            <i class="bi bi-cash-coin me-2"></i>Denda Saya
        </a>
    @endif

    <div class="menu-label">Akun</div>
    <a href="{{ route('profile.edit') }}"><i class="bi bi-person me-2"></i>Profil</a>
    <form action="{{ route('logout') }}" method="POST" class="px-3 pt-1 pb-3">
        @csrf
        <button class="btn btn-sm btn-outline-light w-100">
            <i class="bi bi-box-arrow-right me-1"></i>Logout
        </button>
    </form>
</div>

{{-- MAIN --}}
<div class="main">
    <div class="topbar d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            {{-- Tombol menu (hanya di HP) --}}
            <button class="btn btn-link p-0 d-md-none me-1 text-dark" id="sidebarToggle">
                <i class="bi bi-list fs-4"></i>
            </button>
            <span class="fw-semibold">@yield('title', 'Dashboard')</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            {{-- Bel notifikasi --}}
            <div class="dropdown">
                <button class="btn btn-link p-0 position-relative text-dark" id="notifBell" type="button"
                        data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <i class="bi bi-bell fs-5"></i>
                    <span id="notifBadge"
                          class="position-absolute translate-middle badge rounded-pill bg-danger"
                          style="top:4px;left:80%;font-size:.58rem;display:none">0</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end shadow border-0 p-0"
                     style="width:330px;max-width:92vw" aria-labelledby="notifBell">
                    <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                        <span class="fw-semibold small">Notifikasi</span>
                        <button class="btn btn-link btn-sm p-0 text-decoration-none small" id="notifMarkAll" type="button">
                            Tandai dibaca
                        </button>
                    </div>
                    <div id="notifList" style="max-height:360px;overflow-y:auto">
                        <div class="text-center text-muted small py-4">Memuat...</div>
                    </div>
                    <a href="{{ route('notifikasi.index') }}"
                       class="d-block text-center small py-2 border-top text-decoration-none">Lihat semua</a>
                </div>
            </div>

            <span class="text-muted small d-none d-sm-inline">
                {{ auth()->user()->name }}
                <span class="badge bg-primary ms-1">{{ ucfirst(auth()->user()->role) }}</span>
            </span>
        </div>
    </div>

    <div class="p-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('sidebarOverlay');
    const toggle   = document.getElementById('sidebarToggle');
    const close    = document.getElementById('sidebarClose');

    function openSidebar()  { sidebar.classList.add('show'); overlay.classList.add('show'); }
    function closeSidebar() { sidebar.classList.remove('show'); overlay.classList.remove('show'); }

    if (toggle)  toggle.addEventListener('click', openSidebar);
    if (close)   close.addEventListener('click', closeSidebar);
    if (overlay) overlay.addEventListener('click', closeSidebar);

    // Tutup sidebar saat menu diklik (di HP)
    sidebar.querySelectorAll('a').forEach(a => {
        a.addEventListener('click', () => { if (window.innerWidth < 768) closeSidebar(); });
    });
})();

// Aktifkan tooltip Bootstrap
document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
    new bootstrap.Tooltip(el, { trigger: 'hover focus' });
});

// ── Bel notifikasi ────────────────────────────────────────────────
(function () {
    const badge   = document.getElementById('notifBadge');
    const list    = document.getElementById('notifList');
    const markAll = document.getElementById('notifMarkAll');
    if (!badge || !list) return;

    const RECENT_URL   = "{{ route('notifikasi.recent') }}";
    const MARKALL_URL  = "{{ route('notifikasi.baca-semua') }}";
    const CSRF         = document.querySelector('meta[name="csrf-token"]').content;
    const ALL_URL      = "{{ route('notifikasi.index') }}";

    const ICON = {
        peminjaman:   ['bi-journal-text', '#0d6efd'],
        approval:     ['bi-check-circle',  '#198754'],
        pengembalian: ['bi-box-arrow-in-left', '#0dcaf0'],
        denda:        ['bi-cash-coin', '#dc3545'],
    };

    function escapeHtml(s) {
        const d = document.createElement('div'); d.textContent = s; return d.innerHTML;
    }

    function render(data) {
        // Angka notifikasi belum dibaca
        if (data.unread > 0) {
            badge.textContent = data.unread > 99 ? '99+' : data.unread;
            badge.style.display = '';
        } else {
            badge.style.display = 'none';
        }

        // Daftar notifikasi
        if (!data.items.length) {
            list.innerHTML = '<div class="text-center text-muted small py-4">'
                + '<i class="bi bi-bell-slash d-block mb-1 fs-5"></i>Belum ada notifikasi</div>';
            return;
        }
        list.innerHTML = data.items.map(n => {
            const [ic, col] = ICON[n.tipe] || ['bi-bell', '#6c757d'];
            const bg = n.is_read ? '#fff' : '#eef4ff';
            return '<a href="' + ALL_URL + '" class="d-flex gap-2 px-3 py-2 text-decoration-none text-dark border-bottom"'
                + ' style="background:' + bg + '">'
                + '<i class="bi ' + ic + ' mt-1 flex-shrink-0" style="color:' + col + '"></i>'
                + '<div class="flex-grow-1" style="min-width:0">'
                + '<div class="small fw-semibold text-truncate">' + escapeHtml(n.judul) + '</div>'
                + '<div class="small text-muted" style="font-size:.78rem">' + escapeHtml(n.pesan) + '</div>'
                + '<div class="text-muted" style="font-size:.68rem">' + escapeHtml(n.waktu || '') + '</div>'
                + '</div></a>';
        }).join('');
    }

    function load() {
        fetch(RECENT_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
            .then(r => r.json()).then(render).catch(() => {});
    }

    markAll.addEventListener('click', function () {
        fetch(MARKALL_URL, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin',
        }).then(() => load()).catch(() => {});
    });

    load();
    setInterval(load, 30000);   // poll tiap 30 detik
})();
</script>
@stack('scripts')
</body>
</html>
