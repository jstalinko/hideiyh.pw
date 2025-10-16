<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Helpers\Helper;
use Filament\Pages\Page;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class Api extends Page
{
    protected static ?string $navigationIcon = 'heroicon-m-code-bracket';

    protected static string $view = 'filament.pages.api';
    protected static ?int $navigationSort = 5;
    protected static ?string $navigationLabel = 'API';
    public function revokeApiKeyAction(): Action
    {
        return Action::make('revokeApiKey')
            ->label('Revoke API Key')
            ->color('danger')
            ->icon('heroicon-o-arrow-path')
            ->requiresConfirmation()
            ->modalHeading('Revoke API Key')
            ->modalDescription('Are you sure you want to revoke this key? Your old key will stop working immediately and a new one will be generated.')
            ->modalSubmitActionLabel('Yes, Revoke')
            ->action(function () {
                $user = User::find(auth()->user()->id);
                $user->update([
                    'apikey' => Helper::generateApikey(Str::random(60).$user->id) // Membuat key baru yang aman
                ]);

                Notification::make()
                    ->title('API Key has been revoked and a new one generated!')
                    ->success()
                    ->send();
                
                // Refresh halaman untuk menampilkan key baru
                return redirect(static::getUrl());
            });
    }
    public function phpExampleAction()
    {
        return Action::make('phpExample')
            ->label('Example Code')
            ->icon('heroicon-o-code-bracket')
            ->modalContent(view('filament.modals.php-code-example'))
            ->modalSubmitAction(false) // Sembunyikan tombol submit
            ->modalCancelActionLabel('Close');
    }
  
}
