<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'kode_pinjam',
        'user_id',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'keperluan',
        'dokumen_pendukung',
        'status',
        'catatan_penolakan',
        'reviewed_by_dosen',
        'reviewed_by_admin',
        'dosen_reviewed_at',
        'admin_reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pinjam' => 'date',
            'tanggal_kembali_rencana' => 'date',
            'tanggal_kembali_aktual' => 'date',
            'dosen_reviewed_at' => 'datetime',
            'admin_reviewed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detail()
    {
        return $this->hasMany(PeminjamanDetail::class);
    }

    public function denda()
    {
        return $this->hasMany(Denda::class);
    }

    public function dosenReviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by_dosen');
    }

    public function adminReviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by_admin');
    }
}
