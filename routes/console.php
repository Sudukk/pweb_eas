<?php

use App\Models\PengaturanBooking;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Tampilkan kutipan inspiratif');

// Alokasi kursi ruangan - waktu bisa dikonfigurasi oleh admin.
// Scheduler dijalankan tiap menit; eksekusi hanya terjadi satu kali saat jam cocok.
Schedule::command('booking:proses')
    ->everyMinute()
    ->when(function () {
        try {
            $p      = PengaturanBooking::first();
            $cutoff = \Illuminate\Support\Carbon::today()->setTimeFromTimeString($p->jam_alokasi ?? '22:00:00');

            // Jalan sekali sehari: waktu sekarang sudah lewat jam alokasi, dan belum jalan hari ini
            return now()->gte($cutoff)
                && ($p->last_ran_date === null || !$p->last_ran_date->isToday());
        } catch (\Throwable) {
            return false;
        }
    });
