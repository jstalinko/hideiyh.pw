<?php

namespace App\Console\Commands;

use App\Notifications\SubscriptionEndingSoon;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;
use Illuminate\Notifications\DatabaseNotification;

class SendWhatsAppNotifications extends Command
{
    protected $signature = 'notifications:send-whatsapp';
    protected $description = 'Periksa notifikasi database dan kirim yang tertunda melalui WhatsApp';

    public function __construct(protected WhatsAppService $whatsAppService)
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Mulai memeriksa antrian notifikasi WhatsApp...');

        // Cari semua notifikasi dari tipe SubscriptionEndingSoon yang belum "dibaca"
        $notifications = DatabaseNotification::query()
            ->where('type', SubscriptionEndingSoon::class)
            ->whereNull('read_at')
            ->get();

        if ($notifications->isEmpty()) {
            $this->info('Tidak ada notifikasi WhatsApp untuk dikirim.');
            return 0;
        }

        $this->info("Ditemukan {$notifications->count()} notifikasi untuk dikirim.");

        foreach ($notifications as $notification) {
            $data = $notification->data;
            $phoneNumber = $data['phone'] ?? null;
            $message = $data['whatsapp_message'] ?? $data['body'];

            // Lewati jika tidak ada nomor telepon
            if (empty($phoneNumber)) {
                $this->warn("Dilewati: Notifikasi ID {$notification->id} tidak memiliki nomor telepon.");
                $notification->markAsRead(); // Tandai agar tidak dicek lagi
                continue;
            }

            // Kirim pesan menggunakan service
            $isSent = $this->whatsAppService->sendMessage($phoneNumber, $message);
            
            // Jika berhasil terkirim, tandai sebagai "sudah dibaca"
            if ($isSent) {
                $notification->markAsRead();
                $this->info("Berhasil: Notifikasi ID {$notification->id} terkirim ke {$phoneNumber}.");
            } else {
                $this->error("Gagal: Notifikasi ID {$notification->id} tidak terkirim.");
            }
        }

        $this->info('Selesai memproses antrian.');
        return 0;
    }
}