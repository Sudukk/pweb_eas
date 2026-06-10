<?php

namespace Database\Seeders;

use App\Models\Alat;
use Illuminate\Database\Seeder;

class AlatImageSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            'JRG-001' => 'alat/JRG-001.jpg',
            'JRG-002' => 'alat/JRG-002.jpg',
            'JRG-003' => 'alat/JRG-003.jpg',
            'JRG-004' => 'alat/JRG-004.jpg',
            'PRG-001' => 'alat/PRG-001.jpg',
            'PRG-002' => 'alat/PRG-002.jpg',
            'PRG-003' => 'alat/PRG-003.jpg',
            'MUL-001' => 'alat/MUL-001.jpg',
            'MUL-002' => 'alat/MUL-002.jpg',
            'MUL-003' => 'alat/MUL-003.jpg',
            'MUL-004' => 'alat/MUL-004.jpg',
            'MUL-005' => 'alat/MUL-005.jpg',
            'ELK-001' => 'alat/ELK-001.jpg',
            'ELK-002' => 'alat/ELK-002.jpg',
            'ELK-003' => 'alat/ELK-003.jpg',
            'SIS-001' => 'alat/SIS-001.jpg',
            'SIS-002' => 'alat/SIS-002.jpg',
            'SIS-003' => 'alat/SIS-003.jpg',
            'SIS-004' => 'alat/SIS-004.jpg',
            'SIS-005' => 'alat/SIS-005.jpg',
        ];

        foreach ($map as $kode => $path) {
            Alat::where('kode_alat', $kode)->update(['foto' => $path]);
        }

        $this->command->info('Alat images seeded: ' . count($map) . ' items updated.');
    }
}
