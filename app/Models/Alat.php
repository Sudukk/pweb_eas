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
}
