<?php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification; // <-- 1. Impor ini

class SubscriptionEndingSoon extends Notification
{
    use Queueable;

    public function __construct(public Subscription $subscription)
    {
        //
    }

    /**
     * Tentukan channel pengiriman. HANYA ke database.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Mempersiapkan data untuk disimpan di channel database.
     * Kode ini TIDAK SAYA UBAH, sesuai permintaan Anda.
     */
    public function toDatabase(object $notifiable): array
    {
        $phoneNumber = $notifiable->routeNotificationFor('whatsapp');

       // 1. Buat data dasar yang dibutuhkan oleh Filament
        $filamentData = FilamentNotification::make()
            ->title('Langganan Akan Segera Berakhir!')
            ->body("Langganan Anda untuk paket {$this->subscription->package->name} akan berakhir dalam 3 hari.")
            ->info()
            ->actions([
                \Filament\Notifications\Actions\Action::make('view')
                    ->label('Lihat Langganan')
                    ->url('/my-subscription')
                    ->markAsRead(),
            ])
            ->getDatabaseMessage();

        // 2. Siapkan data kustom yang dibutuhkan oleh command WhatsApp
        $whatsappData = [
            'whatsapp_message' => "Hai ".$notifiable->name.", langganan Anda akan segera berakhir dalam 3 hari. Segera perpanjang agar layanan tidak terputus!",
            'phone'            => $notifiable->routeNotificationFor('whatsapp'),
        ];

        // 3. Gabungkan kedua array tersebut
        return array_merge($filamentData, $whatsappData);
        
    }

    /**
     * ✅ 2. TAMBAHKAN METODE BARU INI
     * Mendefinisikan bagaimana notifikasi ini akan ditampilkan di dalam Filament.
     */
    public function toFilament(object $notifiable): FilamentNotification
    {
        // Ambil data yang sudah kita simpan di database
        $data = $this->toArray($notifiable);

        return FilamentNotification::make()
            ->title('Langganan Akan Segera Berakhir!') // Judul notifikasi
            ->body($data['message']) // Isi notifikasi, diambil dari 'message'
            ->info() // Warna notifikasi (bisa juga: success, warning, danger)
            ->actions([
                // Membuat tombol di dalam notifikasi
                \Filament\Notifications\Actions\Action::make('view')
                    ->label('Lihat Langganan')
                    ->url($data['link']) // URL diambil dari 'link'
                    ->markAsRead(), // Tandai sudah dibaca saat diklik
            ]);
    }
}