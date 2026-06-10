<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'nim',
        'nip',
        'role',
        'jurusan',
        'no_hp',
        'is_blacklisted',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_blacklisted' => 'boolean',
        ];
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function denda()
    {
        return $this->hasMany(Denda::class);
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }


}
