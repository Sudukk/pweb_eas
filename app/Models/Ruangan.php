<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangan';

    protected $fillable = [
        'kode_ruangan',
        'nama',
        'lokasi',
        'kapasitas_kursi',
        'deskripsi',
        'foto_url',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    public function booking()
    {
        return $this->hasMany(BookingRuangan::class);
    }
}
