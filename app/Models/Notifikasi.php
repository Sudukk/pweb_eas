<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'tipe',
        'referensi_id',
        'is_read',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Kirim notifikasi ke seorang user.
     * $tipe: peminjaman | approval | pengembalian | denda
     */
    public static function kirim(int $userId, string $judul, string $pesan, string $tipe, ?int $referensiId = null): void
    {
        static::create([
            'user_id'      => $userId,
            'judul'        => $judul,
            'pesan'        => $pesan,
            'tipe'         => $tipe,
            'referensi_id' => $referensiId,
            'is_read'      => false,
            'created_at'   => now(),
        ]);
    }

    /** Kirim notifikasi yang sama ke semua admin (mis. saat ada pengajuan baru). */
    public static function kirimAdmin(string $judul, string $pesan, string $tipe, ?int $referensiId = null): void
    {
        User::where('role', 'admin')->pluck('id')->each(
            fn ($id) => static::kirim($id, $judul, $pesan, $tipe, $referensiId)
        );
    }
}
