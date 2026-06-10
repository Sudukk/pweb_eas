<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Dosen;
use App\Http\Controllers\Mahasiswa;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\BookingRuanganController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Redirect root ke login
Route::get('/', fn() => redirect()->route('login'));

// ── AUTH (dari routes/auth.php) ───────────────────────────────────────────────
require __DIR__ . '/auth.php';

// ── SEMUA ROUTE BUTUH LOGIN ───────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Redirect /dashboard sesuai role
    Route::get('/dashboard', function () {
        return match (auth()->user()->role) {
            'admin'  => redirect()->route('admin.dashboard'),
            'dosen'  => redirect()->route('dosen.dashboard'),
            default  => redirect()->route('mahasiswa.dashboard'),
        };
    })->name('dashboard');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── ADMIN ─────────────────────────────────────────────────────────────────
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // CRUD Alat
        Route::resource('alat', Admin\AlatController::class);

        // Peminjaman
        Route::get('/peminjaman', [Admin\PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::get('/peminjaman/{peminjaman}', [Admin\PeminjamanController::class, 'show'])->name('peminjaman.show');
        Route::post('/peminjaman/{peminjaman}/approve', [Admin\PeminjamanController::class, 'approve'])->name('peminjaman.approve');
        Route::post('/peminjaman/{peminjaman}/reject', [Admin\PeminjamanController::class, 'reject'])->name('peminjaman.reject');
        Route::get('/peminjaman/{peminjaman}/kembali', [Admin\PeminjamanController::class, 'formKembali'])->name('peminjaman.kembali');
        Route::post('/peminjaman/{peminjaman}/kembali', [Admin\PeminjamanController::class, 'prosesKembali'])->name('peminjaman.prosesKembali');

        // Denda
        Route::get('/denda', [Admin\DendaController::class, 'index'])->name('denda.index');
        Route::post('/denda/{denda}/lunas', [Admin\DendaController::class, 'lunaskan'])->name('denda.lunas');

        // Users
        Route::get('/users', [Admin\UserController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/unblacklist', [Admin\UserController::class, 'unblacklist'])->name('users.unblacklist');

        // CRUD Ruangan
        Route::resource('ruangan', Admin\RuanganController::class)->except(['show']);

        // Booking Ruangan (kursi)
        Route::get('/booking-ruangan', [Admin\BookingRuanganController::class, 'index'])->name('booking-ruangan.index');
        Route::post('/booking-ruangan/pengaturan', [Admin\BookingRuanganController::class, 'simpanPengaturan'])->name('booking-ruangan.pengaturan');
        Route::get('/booking-ruangan/{bookingRuangan}', [Admin\BookingRuanganController::class, 'show'])->name('booking-ruangan.show');
        Route::post('/booking-ruangan/{bookingRuangan}/approve', [Admin\BookingRuanganController::class, 'approve'])->name('booking-ruangan.approve');
        Route::post('/booking-ruangan/{bookingRuangan}/reject', [Admin\BookingRuanganController::class, 'reject'])->name('booking-ruangan.reject');
    });

    // ── DOSEN ─────────────────────────────────────────────────────────────────
    Route::middleware('role:dosen')->group(function () {
        Route::get('/dosen/dashboard', [Dosen\DashboardController::class, 'index'])->name('dosen.dashboard');
    });

    // ── MAHASISWA ─────────────────────────────────────────────────────────────
    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('/mahasiswa/dashboard', [Mahasiswa\DashboardController::class, 'index'])->name('mahasiswa.dashboard');
    });

    // ── PEMINJAMAN & DENDA (mahasiswa + dosen) ────────────────────────────────
    Route::middleware('role:mahasiswa,dosen')->group(function () {
        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::get('/peminjaman/buat', [PeminjamanController::class, 'create'])->name('peminjaman.create');
        Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
        Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');

        // Booking ruangan per kursi
        Route::get('/booking-ruangan', [BookingRuanganController::class, 'index'])->name('booking-ruangan.index');
        Route::get('/booking-ruangan/buat', [BookingRuanganController::class, 'create'])->name('booking-ruangan.create');
        Route::post('/booking-ruangan', [BookingRuanganController::class, 'store'])->name('booking-ruangan.store');
        Route::get('/booking-ruangan/kursi-terpakai', [BookingRuanganController::class, 'kursiTerpakai'])->name('booking-ruangan.kursi-terpakai');
        Route::get('/booking-ruangan/{bookingRuangan}', [BookingRuanganController::class, 'show'])->name('booking-ruangan.show');
        Route::post('/booking-ruangan/{bookingRuangan}/batal', [BookingRuanganController::class, 'batal'])->name('booking-ruangan.batal');

        Route::get('/denda-saya', [DendaController::class, 'index'])->name('denda.index');
    });
});
