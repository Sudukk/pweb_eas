<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingRuangan extends Model
{
    use HasFactory;

    protected $table = 'booking_ruangan';

    /** Peta tipe → prioritas alokasi (semakin kecil semakin diutamakan). */
    public const PRIORITAS = [
        'kelas'     => 1,
        'dosen'     => 2,
        'mahasiswa' => 3,
    ];

    protected $fillable = [
        'kode_booking',
        'user_id',
        'ruangan_id',
        'tipe',
        'prioritas',
        'mata_kuliah',
        'keperluan',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'jumlah_kursi',
        'kursi_dipilih',
        'status',
        'catatan',
        'diproses_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal'       => 'date',
            'diproses_at'   => 'datetime',
            'kursi_dipilih' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }
}
