<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanBooking extends Model
{
    protected $table = 'pengaturan_booking';

    public $timestamps = false;

    protected $fillable = ['jam_alokasi', 'last_ran_date', 'updated_by', 'updated_at'];

    protected function casts(): array
    {
        return [
            'updated_at'    => 'datetime',
            'last_ran_date' => 'date',
        ];
    }

    public static function jamAlokasi(): string
    {
        return static::first()?->jam_alokasi ?? '22:00:00';
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
