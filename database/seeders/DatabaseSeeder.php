<?php

namespace Database\Seeders;

use App\Models\Alat;
use App\Models\BookingRuangan;
use App\Models\Denda;
use App\Models\PeminjamanDetail;
use App\Models\PengaturanDenda;
use App\Models\Ruangan;
use App\Models\User;
use App\Models\Peminjaman;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
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
            ['kode_alat' => 'JRG-001', 'nama' => 'Cisco Switch 24-Port',      'jumlah_total' => 5,  'foto' => 'alat/JRG-001.jpg'],
            ['kode_alat' => 'JRG-002', 'nama' => 'Cisco Router 2900',         'jumlah_total' => 3,  'foto' => 'alat/JRG-002.jpg'],
            ['kode_alat' => 'JRG-003', 'nama' => 'Kabel UTP Cat6 (10m)',      'jumlah_total' => 20, 'foto' => 'alat/JRG-003.jpg'],
            ['kode_alat' => 'JRG-004', 'nama' => 'Raspberry Pi 4',            'jumlah_total' => 10, 'foto' => 'alat/JRG-004.jpg'],
            ['kode_alat' => 'PRG-001', 'nama' => 'Laptop ASUS VivoBook',      'jumlah_total' => 15, 'foto' => 'alat/PRG-001.jpg'],
            ['kode_alat' => 'PRG-002', 'nama' => 'Arduino Uno',               'jumlah_total' => 20, 'foto' => 'alat/PRG-002.jpg'],
            ['kode_alat' => 'PRG-003', 'nama' => 'ESP32 Module',              'jumlah_total' => 25, 'foto' => 'alat/PRG-003.jpg'],
            ['kode_alat' => 'MUL-001', 'nama' => 'Kamera Canon EOS M50',     'jumlah_total' => 4,  'foto' => 'alat/MUL-001.jpg'],
            ['kode_alat' => 'MUL-002', 'nama' => 'Tripod Kamera',             'jumlah_total' => 6,  'foto' => 'alat/MUL-002.jpg'],
            ['kode_alat' => 'MUL-003', 'nama' => 'Ring Light LED',            'jumlah_total' => 4,  'foto' => 'alat/MUL-003.jpg'],
            ['kode_alat' => 'MUL-004', 'nama' => 'Microphone Condenser',      'jumlah_total' => 5,  'foto' => 'alat/MUL-004.jpg'],
            ['kode_alat' => 'MUL-005', 'nama' => 'Drone DJI Mini 3',          'jumlah_total' => 2,  'foto' => 'alat/MUL-005.jpg'],
            ['kode_alat' => 'ELK-001', 'nama' => 'Multimeter Digital',        'jumlah_total' => 15, 'foto' => 'alat/ELK-001.jpg'],
            ['kode_alat' => 'ELK-002', 'nama' => 'Osiloskop Digital',         'jumlah_total' => 5,  'foto' => 'alat/ELK-002.jpg'],
            ['kode_alat' => 'ELK-003', 'nama' => 'Power Supply DC',           'jumlah_total' => 10, 'foto' => 'alat/ELK-003.jpg'],
            ['kode_alat' => 'SIS-001', 'nama' => 'Server Dell PowerEdge',     'jumlah_total' => 2,  'foto' => 'alat/SIS-001.jpg'],
            ['kode_alat' => 'SIS-002', 'nama' => 'NAS Storage Synology',      'jumlah_total' => 2,  'foto' => 'alat/SIS-002.jpg'],
            ['kode_alat' => 'SIS-003', 'nama' => 'Proyektor Epson',           'jumlah_total' => 3,  'foto' => 'alat/SIS-003.jpg'],
            ['kode_alat' => 'SIS-004', 'nama' => 'Whiteboard Portable',       'jumlah_total' => 4,  'foto' => 'alat/SIS-004.jpg'],
            ['kode_alat' => 'SIS-005', 'nama' => 'Tablet Wacom',              'jumlah_total' => 6,  'foto' => 'alat/SIS-005.jpg'],
        ];

        $alatRecords = [];
        foreach ($alatData as $data) {
            $alatRecords[] = Alat::create(array_merge($data, [
                'jumlah_tersedia' => $data['jumlah_total'],
                'kondisi'         => 'baik',
                'deskripsi'       => 'Peralatan laboratorium ' . $data['nama'],
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
            'keperluan'               => 'Praktikum jaringan komputer semester 5',
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
            'keperluan'               => 'Praktikum elektronika dasar',
            'status'                  => 'menunggu',
        ]);
        PeminjamanDetail::create(['peminjaman_id' => $p3->id, 'alat_id' => $alatRecords[12]->id, 'jumlah' => 2]);

        // Peminjaman: menunggu (dosen)
        $p4 = Peminjaman::create([
            'kode_pinjam'             => 'PJM-' . now()->format('Ymd') . '-002',
            'user_id'                 => $dosen1->id,
            'tanggal_pinjam'          => now()->addDay(),
            'tanggal_kembali_rencana' => now()->addDays(5),
            'keperluan'               => 'Keperluan penelitian — presentasi seminar',
            'status'                  => 'menunggu',
        ]);
        PeminjamanDetail::create(['peminjaman_id' => $p4->id, 'alat_id' => $alatRecords[17]->id, 'jumlah' => 1]);

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
        PeminjamanDetail::create(['peminjaman_id' => $p5->id, 'alat_id' => $alatRecords[13]->id, 'jumlah' => 1]);

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
            ['kode_ruangan' => 'STUDIO-PRG', 'nama' => 'Studio Pemrograman',     'lokasi' => 'Gedung SI', 'kapasitas_kursi' => 40, 'foto_url' => '/images/ruangan/studio-pemrograman.jpg',    'deskripsi' => 'Studio Pemrograman Sistem Informasi ITS — dilengkapi workstation dan perangkat pengembangan.'],
            ['kode_ruangan' => 'STUDIO-APP', 'nama' => 'Studio Aplikasi Terapan','lokasi' => 'Gedung SI', 'kapasitas_kursi' => 42, 'foto_url' => '/images/ruangan/studio-aplikasi-terapan.jpg','deskripsi' => 'Studio Aplikasi Terapan Sistem Informasi ITS — fasilitas untuk pengembangan aplikasi dan riset terapan.'],
        ];
        $ruangan = [];
        foreach ($ruanganData as $data) {
            $ruangan[] = Ruangan::create($data + ['aktif' => true]);
        }

        // ── Sample booking ruangan (untuk besok, status pending) ──────────────
        $besok  = now()->addDay()->toDateString();
        $kodeBk = fn ($i) => 'BKR-' . now()->format('Ymd') . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);

        // Kelas (prioritas 1) — dosen atas nama mata kuliah, 30 kursi di Lab 1
        BookingRuangan::create([
            'kode_booking' => $kodeBk(1), 'user_id' => $dosen1->id, 'ruangan_id' => $ruangan[0]->id,
            'tipe' => 'kelas', 'prioritas' => 1, 'mata_kuliah' => 'Pemrograman Web - Kelas A',
            'keperluan' => 'Praktikum Pemrograman Web', 'tanggal' => $besok,
            'jam_mulai' => '08:00', 'jam_selesai' => '10:00', 'jumlah_kursi' => 25, 'status' => 'pending',
        ]);

        // Dosen pribadi (prioritas 2) — slot bentrok dengan kelas di atas
        BookingRuangan::create([
            'kode_booking' => $kodeBk(2), 'user_id' => $dosen2->id, 'ruangan_id' => $ruangan[0]->id,
            'tipe' => 'dosen', 'prioritas' => 2,
            'keperluan' => 'Bimbingan tugas akhir', 'tanggal' => $besok,
            'jam_mulai' => '08:00', 'jam_selesai' => '10:00', 'jumlah_kursi' => 8, 'status' => 'pending',
        ]);

        // Mahasiswa (prioritas 3) — slot sama, kemungkinan tergeser kuota
        BookingRuangan::create([
            'kode_booking' => $kodeBk(3), 'user_id' => $mahasiswaUsers[0]->id, 'ruangan_id' => $ruangan[0]->id,
            'tipe' => 'mahasiswa', 'prioritas' => 3,
            'keperluan' => 'Mengerjakan tugas kelompok', 'tanggal' => $besok,
            'jam_mulai' => '09:00', 'jam_selesai' => '11:00', 'jumlah_kursi' => 4, 'status' => 'pending',
        ]);
    }
}
