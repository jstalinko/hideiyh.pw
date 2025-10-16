<?php

namespace App\Filament\Pages;

use App\Models\Subscription;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class MySubscription extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static string $view = 'filament.pages.my-subscription';

protected static ?int $navigationSort = 1;

    public ?Subscription $subscription;

    public function mount(): void
    {
        $this->subscription = Auth::user()->activeSubscription;
    }

    /**
     * Metode ini sekarang dikosongkan karena action dipindah ke dalam konten.
     */
    protected function getHeaderActions(): array
    {
        return []; // Kosongkan atau hapus action dari sini
    }

    /**
     * Buat metode baru yang SECARA KHUSUS mendefinisikan dan mengembalikan action
     * untuk input kode. Metode ini akan kita panggil dari file Blade.
     */
    public function inputCodeAction(): Action
    {
        return Action::make('inputCode') // Gunakan nama yang berbeda untuk menghindari kebingungan
            ->label('Input Subscription Code')
            ->icon('heroicon-o-qr-code')
            ->form([
                TextInput::make('subscription_code')
                    ->label('Subscription Code')
                    ->required(),
            ])
            ->action(function (array $data) {
                // Logika untuk memvalidasi kode langganan
                Notification::make()
                    ->title('Kode sedang diproses')
                    ->success()
                    ->send();
                
                // Jangan lupa untuk me-refresh halaman setelah aksi berhasil
                // agar view-nya update
                return redirect(static::getUrl());
            });
    }
    
    // Metode goToPricing dan goToUpgrade tetap sama
    public function goToPricing()
    {
        return redirect()->to(url('/#pricing')); 
    }

    public function goToUpgrade()
    {
        return redirect()->to(url('#pricing'));
    }
    public function cancelSubscriptionAction(): Action
    {
        return Action::make('cancelSubscription')
            ->label('Cancel Subscription')
            ->color('danger') // Memberi warna merah untuk aksi berbahaya
            ->icon('heroicon-o-no-symbol')
            ->requiresConfirmation() // INI KUNCINYA: Otomatis memunculkan modal
            ->modalHeading('Batalkan Langganan')
            ->modalDescription('Apakah Anda yakin ingin membatalkan langganan ini? Paket Anda akan dinonaktifkan di akhir periode berjalan.')
            ->modalSubmitActionLabel('Ya, Batalkan')
            ->action(function () {
                if ($this->subscription) {
                    // Logika pembatalan: Ubah status, mungkin set end_at, dll.
                    $this->subscription->update([
                        'status' => 'canceled'
                    ]);

                    Notification::make()
                        ->title('Langganan telah dibatalkan')
                        ->success()
                        ->send();
                    
                    // Refresh halaman untuk memperbarui tampilan
                    return redirect(static::getUrl());
                }
            });
    }
}