<?php

namespace Database\Seeders;

use App\Models\Alat;
use App\Models\BookingRuangan;
use App\Models\Denda;
use App\Models\Notifikasi;
use App\Models\PeminjamanDetail;
use App\Models\PengaturanDenda;
use App\Models\Ruangan;
use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Salin gambar bawaan alat ke storage/app/public/alat agar foto selalu tersedia
        // setelah migrate:fresh --seed (tidak bergantung file yang mungkin terhapus).
        $this->salinGambarAlat();

        // Admin
        $admin = User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@lab.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'jurusan'  => 'Sistem Informasi',
            'no_hp'    => '081234567890',
        ]);

        // Dosen
        $dosen1 = User::create([
            'name'     => 'Dr. Budi Santoso',
            'email'    => 'budi@lab.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'dosen',
            'nip'      => '198501012010011001',
            'jurusan'  => 'Teknik Informatika',
            'no_hp'    => '081234567891',
        ]);

        $dosen2 = User::create([
            'name'     => 'Dr. Siti Rahayu',
            'email'    => 'siti@lab.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'dosen',
            'nip'      => '197803152005012002',
            'jurusan'  => 'Sistem Informasi',
            'no_hp'    => '081234567892',
        ]);

        // Mahasiswa
        $mahasiswaData = [
            ['name' => 'Andi Prasetyo',   'nim' => '20210001', 'email' => 'andi@student.ac.id'],
            ['name' => 'Bella Kusuma',    'nim' => '20210002', 'email' => 'bella@student.ac.id'],
            ['name' => 'Candra Wijaya',   'nim' => '20210003', 'email' => 'candra@student.ac.id'],
            ['name' => 'Dewi Lestari',    'nim' => '20210004', 'email' => 'dewi@student.ac.id'],
            ['name' => 'Eko Hartanto',    'nim' => '20210005', 'email' => 'eko@student.ac.id'],
            ['name' => 'Fani Susanti',    'nim' => '20210006', 'email' => 'fani@student.ac.id'],
            ['name' => 'Gilang Ramadhan', 'nim' => '20210007', 'email' => 'gilang@student.ac.id'],
            ['name' => 'Hana Putri',      'nim' => '20210008', 'email' => 'hana@student.ac.id'],
            ['name' => 'Ivan Setiawan',   'nim' => '20210009', 'email' => 'ivan@student.ac.id'],
            ['name' => 'Julia Andriani',  'nim' => '20210010', 'email' => 'julia@student.ac.id'],
        ];

        $mahasiswaUsers = [];
        foreach ($mahasiswaData as $data) {
            $mahasiswaUsers[] = User::create([
                'name'     => $data['name'],
                'nim'      => $data['nim'],
                'email'    => $data['email'],
                'password' => Hash::make('password'),
                'role'     => 'mahasiswa',
                'jurusan'  => 'Teknik Informatika',
                'no_hp'    => '0812345678' . rand(10, 99),
            ]);
        }

        // Alat
        $alatData = [
            // Laboratorium & Riset (Sisfor)
            ['kode_alat' => 'LAB-001', 'nama' => 'Akses Server Deployment', 'jumlah_total' => 4,  'foto' => 'alat/LAB-001.jpg', 'deskripsi' => 'Laboratorium & Riset (Sisfor) - Akses server untuk deployment arsitektur frontend dan backend.'],
            ['kode_alat' => 'LAB-002', 'nama' => 'Perangkat Testing UI/UX', 'jumlah_total' => 6,  'foto' => 'alat/LAB-002.jpg', 'deskripsi' => 'Laboratorium & Riset (Sisfor) - Perangkat untuk pengujian dan evaluasi UI/UX aplikasi.'],
            ['kode_alat' => 'LAB-003', 'nama' => 'Modul IoT',              'jumlah_total' => 12, 'foto' => 'alat/LAB-003.jpg', 'deskripsi' => 'Laboratorium & Riset (Sisfor) - Modul IoT untuk prototipe dan riset perangkat pintar.'],

            // Sekretariat Himpunan & Kepanitiaan
            ['kode_alat' => 'SEK-001', 'nama' => 'Handy Talky (HT)',        'jumlah_total' => 10, 'foto' => 'alat/SEK-001.jpg', 'deskripsi' => 'Sekretariat Himpunan & Kepanitiaan - Handy Talky (HT) untuk koordinasi operasional event skala besar atau expo.'],
            ['kode_alat' => 'SEK-002', 'nama' => 'Proyektor',              'jumlah_total' => 5,  'foto' => 'alat/SEK-002.jpg', 'deskripsi' => 'Sekretariat Himpunan & Kepanitiaan - Proyektor untuk presentasi dan kebutuhan operasional event.'],
            ['kode_alat' => 'SEK-003', 'nama' => 'Sound System',           'jumlah_total' => 3,  'foto' => 'alat/SEK-003.jpg', 'deskripsi' => 'Sekretariat Himpunan & Kepanitiaan - Sound system untuk kebutuhan event skala besar atau expo.'],

            // Fasilitas Multimedia
            ['kode_alat' => 'MUL-001', 'nama' => 'Kamera',                 'jumlah_total' => 6,  'foto' => 'alat/MUL-001.jpg', 'deskripsi' => 'Fasilitas Multimedia - Kamera untuk dokumentasi visual, editing video, atau motion graphics.'],
            ['kode_alat' => 'MUL-002', 'nama' => 'Tripod',                 'jumlah_total' => 8,  'foto' => 'alat/MUL-002.jpg', 'deskripsi' => 'Fasilitas Multimedia - Tripod penyangga kamera untuk pengambilan gambar yang stabil.'],
            ['kode_alat' => 'MUL-003', 'nama' => 'Perlengkapan Lighting',  'jumlah_total' => 5,  'foto' => 'alat/MUL-003.jpg', 'deskripsi' => 'Fasilitas Multimedia - Perlengkapan lighting untuk dokumentasi visual dan motion graphics.'],
        ];

        $alatRecords = [];
        foreach ($alatData as $data) {
            $alatRecords[] = Alat::create(array_merge($data, [
                'jumlah_tersedia' => $data['jumlah_total'],
                'kondisi'         => 'baik',
            ]));
        }

        // Pengaturan denda default
        PengaturanDenda::create([
            'tarif_per_hari'          => 5000,
            'denda_kerusakan_ringan'  => 50000,
            'denda_kerusakan_berat'   => 200000,
            'denda_kehilangan'        => 500000,
            'updated_by'              => $admin->id,
            'updated_at'              => now(),
        ]);

        // Peminjaman: selesai
        $p1 = Peminjaman::create([
            'kode_pinjam'             => 'PJM-' . now()->subDays(10)->format('Ymd') . '-001',
            'user_id'                 => $mahasiswaUsers[0]->id,
            'tanggal_pinjam'          => now()->subDays(10),
            'tanggal_kembali_rencana' => now()->subDays(7),
            'tanggal_kembali_aktual'  => now()->subDays(7),
            'keperluan'               => 'Deployment aplikasi untuk tugas mata kuliah',
            'status'                  => 'selesai',
            'reviewed_by_admin'       => $admin->id,
            'admin_reviewed_at'       => now()->subDays(9),
        ]);
        PeminjamanDetail::create(['peminjaman_id' => $p1->id, 'alat_id' => $alatRecords[0]->id, 'jumlah' => 1, 'kondisi_saat_kembali' => 'baik']);

        // Peminjaman: aktif (dipinjam)
        $alatRecords[7]->decrement('jumlah_tersedia', 1);
        $p2 = Peminjaman::create([
            'kode_pinjam'             => 'PJM-' . now()->subDays(2)->format('Ymd') . '-001',
            'user_id'                 => $mahasiswaUsers[1]->id,
            'tanggal_pinjam'          => now()->subDays(2),
            'tanggal_kembali_rencana' => now()->addDays(3),
            'keperluan'               => 'Pengambilan video untuk tugas akhir',
            'status'                  => 'dipinjam',
            'reviewed_by_admin'       => $admin->id,
            'admin_reviewed_at'       => now()->subDays(1),
        ]);
        PeminjamanDetail::create(['peminjaman_id' => $p2->id, 'alat_id' => $alatRecords[7]->id, 'jumlah' => 1]);

        // Peminjaman: menunggu (mahasiswa)
        $p3 = Peminjaman::create([
            'kode_pinjam'             => 'PJM-' . now()->format('Ymd') . '-001',
            'user_id'                 => $mahasiswaUsers[2]->id,
            'tanggal_pinjam'          => now()->addDay(),
            'tanggal_kembali_rencana' => now()->addDays(3),
            'keperluan'               => 'Koordinasi panitia untuk kegiatan expo himpunan',
            'status'                  => 'menunggu',
        ]);
        PeminjamanDetail::create(['peminjaman_id' => $p3->id, 'alat_id' => $alatRecords[3]->id, 'jumlah' => 2]);

        // Peminjaman: menunggu (dosen)
        $p4 = Peminjaman::create([
            'kode_pinjam'             => 'PJM-' . now()->format('Ymd') . '-002',
            'user_id'                 => $dosen1->id,
            'tanggal_pinjam'          => now()->addDay(),
            'tanggal_kembali_rencana' => now()->addDays(5),
            'keperluan'               => 'Keperluan penelitian - presentasi seminar',
            'status'                  => 'menunggu',
        ]);
        PeminjamanDetail::create(['peminjaman_id' => $p4->id, 'alat_id' => $alatRecords[6]->id, 'jumlah' => 1]);

        // Peminjaman: ditolak
        $p5 = Peminjaman::create([
            'kode_pinjam'             => 'PJM-' . now()->subDays(3)->format('Ymd') . '-001',
            'user_id'                 => $mahasiswaUsers[4]->id,
            'tanggal_pinjam'          => now()->subDays(3),
            'tanggal_kembali_rencana' => now()->subDays(1),
            'keperluan'               => 'Keperluan pribadi',
            'status'                  => 'ditolak',
            'catatan_penolakan'       => 'Keperluan tidak sesuai dengan kegiatan akademik.',
            'reviewed_by_admin'       => $admin->id,
            'admin_reviewed_at'       => now()->subDays(2),
        ]);
        PeminjamanDetail::create(['peminjaman_id' => $p5->id, 'alat_id' => $alatRecords[5]->id, 'jumlah' => 1]);

        // ── Notifikasi (contoh) ────────────────────────────────────────────────
        Notifikasi::kirim($mahasiswaUsers[0]->id, 'Pengembalian Selesai', "Pengembalian peminjaman {$p1->kode_pinjam} telah selesai. Terima kasih.", 'pengembalian', $p1->id);
        Notifikasi::kirim($mahasiswaUsers[1]->id, 'Peminjaman Disetujui', "Peminjaman {$p2->kode_pinjam} telah disetujui. Silakan ambil alat.", 'approval', $p2->id);
        Notifikasi::kirim($mahasiswaUsers[4]->id, 'Peminjaman Ditolak', "Peminjaman {$p5->kode_pinjam} ditolak. Alasan: Keperluan tidak sesuai dengan kegiatan akademik.", 'approval', $p5->id);
        Notifikasi::kirim($dosen1->id, 'Pengajuan Diterima', 'Pengajuan peminjaman Anda sedang menunggu persetujuan admin.', 'peminjaman', $p4->id);

        // ── Ruangan ───────────────────────────────────────────────────────────
        $ruanganData = [
            // Ruang Kelas
            ['kode_ruangan' => '1101', 'nama' => 'Kelas 1101', 'lokasi' => 'Gedung SI Lt. 1', 'kapasitas_kursi' => 50, 'foto_url' => '/images/ruangan/kelas-1.jpg'],
            ['kode_ruangan' => '1102', 'nama' => 'Kelas 1102', 'lokasi' => 'Gedung SI Lt. 1', 'kapasitas_kursi' => 48, 'foto_url' => '/images/ruangan/kelas-2.jpg'],
            ['kode_ruangan' => '2102', 'nama' => 'Kelas 2102', 'lokasi' => 'Gedung SI Lt. 2', 'kapasitas_kursi' => 52, 'foto_url' => '/images/ruangan/kelas-4.jpg'],
            ['kode_ruangan' => '2103', 'nama' => 'Kelas 2103', 'lokasi' => 'Gedung SI Lt. 2', 'kapasitas_kursi' => 46, 'foto_url' => '/images/ruangan/kelas-1.jpg'],
            ['kode_ruangan' => '2104', 'nama' => 'Kelas 2104', 'lokasi' => 'Gedung SI Lt. 2', 'kapasitas_kursi' => 54, 'foto_url' => '/images/ruangan/kelas-2.jpg'],
            ['kode_ruangan' => '2208', 'nama' => 'Kelas 2208', 'lokasi' => 'Gedung SI Lt. 2', 'kapasitas_kursi' => 42, 'foto_url' => '/images/ruangan/kelas-4.jpg'],
            ['kode_ruangan' => '2209', 'nama' => 'Kelas 2209', 'lokasi' => 'Gedung SI Lt. 2', 'kapasitas_kursi' => 44, 'foto_url' => '/images/ruangan/kelas-1.jpg'],
            ['kode_ruangan' => '4101', 'nama' => 'Kelas 4101', 'lokasi' => 'Gedung SI Lt. 4', 'kapasitas_kursi' => 56, 'foto_url' => '/images/ruangan/kelas-2.jpg'],
            ['kode_ruangan' => '4102', 'nama' => 'Kelas 4102', 'lokasi' => 'Gedung SI Lt. 4', 'kapasitas_kursi' => 58, 'foto_url' => '/images/ruangan/kelas-4.jpg'],
            ['kode_ruangan' => '4201', 'nama' => 'Kelas 4201', 'lokasi' => 'Gedung SI Lt. 4', 'kapasitas_kursi' => 60, 'foto_url' => '/images/ruangan/kelas-1.jpg'],
            ['kode_ruangan' => '4202', 'nama' => 'Kelas 4202', 'lokasi' => 'Gedung SI Lt. 4', 'kapasitas_kursi' => 40, 'foto_url' => '/images/ruangan/kelas-2.jpg'],
            // Studio
            ['kode_ruangan' => 'STUDIO-PRG', 'nama' => 'Studio Pemrograman',     'lokasi' => 'Gedung SI', 'kapasitas_kursi' => 40, 'foto_url' => '/images/ruangan/studio-pemrograman.jpg',    'deskripsi' => 'Studio Pemrograman Sistem Informasi ITS - dilengkapi workstation dan perangkat pengembangan.'],
            ['kode_ruangan' => 'STUDIO-APP', 'nama' => 'Studio Aplikasi Terapan','lokasi' => 'Gedung SI', 'kapasitas_kursi' => 42, 'foto_url' => '/images/ruangan/studio-aplikasi-terapan.jpg','deskripsi' => 'Studio Aplikasi Terapan Sistem Informasi ITS - fasilitas untuk pengembangan aplikasi dan riset terapan.'],
        ];
        $ruangan = [];
        foreach ($ruanganData as $data) {
            $ruangan[] = Ruangan::create($data + ['aktif' => true]);
        }

        // ── Contoh booking ruangan (untuk besok, status pending) ──────────────
        $besok  = now()->addDay()->toDateString();
        $kodeBk = fn ($i) => 'BKR-' . now()->format('Ymd') . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);

        // Kelas (prioritas 1) - dosen atas nama mata kuliah, 30 kursi di Lab 1
        BookingRuangan::create([
            'kode_booking' => $kodeBk(1), 'user_id' => $dosen1->id, 'ruangan_id' => $ruangan[0]->id,
            'tipe' => 'kelas', 'prioritas' => 1, 'mata_kuliah' => 'Pemrograman Web - Kelas A',
            'keperluan' => 'Praktikum Pemrograman Web', 'tanggal' => $besok,
            'jam_mulai' => '08:00', 'jam_selesai' => '10:00', 'jumlah_kursi' => 25, 'status' => 'pending',
        ]);

        // Dosen pribadi (prioritas 2) - slot bentrok dengan kelas di atas
        BookingRuangan::create([
            'kode_booking' => $kodeBk(2), 'user_id' => $dosen2->id, 'ruangan_id' => $ruangan[0]->id,
            'tipe' => 'dosen', 'prioritas' => 2,
            'keperluan' => 'Bimbingan tugas akhir', 'tanggal' => $besok,
            'jam_mulai' => '08:00', 'jam_selesai' => '10:00', 'jumlah_kursi' => 8, 'status' => 'pending',
        ]);

        // Mahasiswa (prioritas 3) - slot sama, kemungkinan tergeser kuota
        BookingRuangan::create([
            'kode_booking' => $kodeBk(3), 'user_id' => $mahasiswaUsers[0]->id, 'ruangan_id' => $ruangan[0]->id,
            'tipe' => 'mahasiswa', 'prioritas' => 3,
            'keperluan' => 'Mengerjakan tugas kelompok', 'tanggal' => $besok,
            'jam_mulai' => '09:00', 'jam_selesai' => '11:00', 'jumlah_kursi' => 4, 'status' => 'pending',
        ]);
    }

    /** Salin gambar bawaan alat dari folder seeder ke storage publik. */
    private function salinGambarAlat(): void
    {
        $sumber = database_path('seeders/seed-images/alat');
        $tujuan = storage_path('app/public/alat');

        if (! File::isDirectory($sumber)) {
            return;
        }
        File::ensureDirectoryExists($tujuan);

        foreach (File::files($sumber) as $file) {
            File::copy($file->getPathname(), $tujuan . DIRECTORY_SEPARATOR . $file->getFilename());
        }
    }
}
