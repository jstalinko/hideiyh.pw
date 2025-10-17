<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;

class ExpireSubscriptions extends Command
{
    protected $signature = 'subscriptions:expire';
    protected $description = 'Set subscription status to expired if the end_at date has passed.';

    public function handle()
    {
        $this->info('Mengecek langganan yang sudah kedaluwarsa...');

        // Cari semua langganan yang statusnya 'active' TAPI tanggal 'end_at'-nya
        // sudah lewat dari waktu saat ini.
        $expiredCount = Subscription::query()
            ->where('status', 'active')
            ->where('end_at', '>', now()) // Menggunakan '<=' untuk keamanan
            ->update(['status' => 'expired']); // Update massal yang efisien

        if ($expiredCount > 0) {
            $this->info("Berhasil mengubah status {$expiredCount} langganan menjadi 'expired'.");
        } else {
            $this->info('Tidak ada langganan aktif yang perlu diubah statusnya.');
        }
        
        return 0;
    }
}