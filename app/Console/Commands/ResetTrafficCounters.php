<?php

namespace App\Console\Commands;

use App\Models\Domain;
use Illuminate\Console\Command;

class ResetTrafficCounters extends Command
{
    protected $signature = 'traffic:reset-daily';
    protected $description = 'Reset penghitung traffic harian untuk semua domain kembali ke 0';

    public function handle()
    {
        $this->info('Merestart penghitung traffic harian...');
        
        // Ini adalah satu-satunya query yang Anda perlukan.
        // Update semua domain dan set 'traffic_count' ke 0.
        $updatedCount = Domain::query()->update(['traffic_count' => 0]);

        $this->info("Selesai. {$updatedCount} domain telah di-reset.");
        return 0;
    }
}