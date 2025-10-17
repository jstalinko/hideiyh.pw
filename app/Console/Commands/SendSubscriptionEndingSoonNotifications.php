<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Notifications\SubscriptionEndingSoon; // Pastikan Anda sudah membuat notifikasi ini
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendSubscriptionEndingSoonNotifications extends Command
{
    /**
     * Nama dan signature dari command Artisan.
     *
     * @var string
     */
    protected $signature = 'subscriptions:notify-ending-soon';

    /**
     * Deskripsi dari command Artisan.
     *
     * @var string
     */
    protected $description = 'Mencari pengguna yang langganannya akan berakhir dalam 3 hari dan mengirim notifikasi.';

    /**
     * Jalankan logika command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Memulai pengecekan langganan yang akan berakhir...');

        // 1. Tentukan tanggal target, yaitu tepat 3 hari dari sekarang.
        $targetDate = now()->addDays(3)->toDateString();

        // 2. Cari semua langganan yang:
        //    - Statusnya masih 'active'.
        //    - Tanggal 'end_at'-nya cocok dengan tanggal target.
        $subscriptions = Subscription::query()
            ->where('status', 'active')
            ->whereDate('end_at', $targetDate) // Menggunakan whereDate untuk mengabaikan jam/menit/detik
            ->with('user') // Eager load relasi user untuk mencegah N+1 query problem
            ->get();

        // 3. Jika tidak ada langganan yang cocok, hentikan proses.
        if ($subscriptions->isEmpty()) {
            $this->info('Tidak ada langganan yang akan berakhir dalam 3 hari.');
            return self::SUCCESS; // Kode exit 0, menandakan sukses
        }

        $this->info("Ditemukan {$subscriptions->count()} langganan. Memulai pengiriman notifikasi...");

        // 4. Loop setiap langganan dan picu notifikasi untuk penggunanya.
        foreach ($subscriptions as $subscription) {
            // Pastikan relasi user ada untuk menghindari error
            if ($subscription->user) {
                // Di sinilah notifikasi dipanggil.
                // Notifikasi ini kemudian akan disimpan ke database sesuai logika Anda.
                $subscription->user->notify(new SubscriptionEndingSoon($subscription));
                
                // Log untuk tracking
                Log::info("Notifikasi 'SubscriptionEndingSoon' telah dibuat untuk user ID: {$subscription->user_id}");
                $this->line("-> Notifikasi dibuat untuk user: {$subscription->user->email}");
            }
        }

        $this->info('Selesai membuat notifikasi.');
        return self::SUCCESS;
    }
}