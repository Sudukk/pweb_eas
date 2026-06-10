<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Denda extends Model
{
    use HasFactory;

    protected $table = 'denda';

    protected $fillable = [
        'peminjaman_id',
        'user_id',
        'jenis',
        'jumlah_hari_telat',
        'tarif_per_hari',
        'nominal',
        'status',
        'keterangan',
        'dibayar_at',
    ];

    protected function casts(): array
    {
        return [
            'nominal' => 'decimal:2',
            'tarif_per_hari' => 'decimal:2',
            'dibayar_at' => 'datetime',
        ];
    }

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
