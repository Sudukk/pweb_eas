<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengaturanDenda extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_denda';

    public $timestamps = false;

    protected $fillable = [
        'tarif_per_hari',
        'denda_kerusakan_ringan',
        'denda_kerusakan_berat',
        'denda_kehilangan',
        'updated_by',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'tarif_per_hari' => 'decimal:2',
            'denda_kerusakan_ringan' => 'decimal:2',
            'denda_kerusakan_berat' => 'decimal:2',
            'denda_kehilangan' => 'decimal:2',
            'updated_at' => 'datetime',
        ];
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
