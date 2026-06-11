<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alat extends Model
{
    use HasFactory;

    protected $table = 'alat';

    protected $fillable = [
        'kode_alat',
        'nama',
        'deskripsi',
        'foto',
        'jumlah_total',
        'jumlah_tersedia',
        'kondisi',
    ];

    public function peminjamanDetail()
    {
        return $this->hasMany(PeminjamanDetail::class);
    }

    /**
     * URL foto + cache-buster berbasis updated_at, supaya gambar yang baru
     * diperbarui admin langsung tampil (tidak tertahan cache browser).
     */
    public function getFotoUrlAttribute(): ?string
    {
        if (! $this->foto) {
            return null;
        }

        // Path root-relative ("/storage/...") agar tetap benar di host/port apa pun,
        // plus cache-buster berbasis updated_at supaya update gambar langsung tampil.
        return '/storage/' . ltrim($this->foto, '/') . '?v=' . optional($this->updated_at)->timestamp;
    }
}
