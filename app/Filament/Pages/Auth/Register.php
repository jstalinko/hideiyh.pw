<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Http\Responses\Auth\Contracts\RegistrationResponse;

class Register extends BaseRegister
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(table: 'users'),
                TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->maxLength(14),
                TextInput::make('invoice_code')
                    ->required()
                    ->maxLength(255)
                    ->label('Invoice Code')
                    ->placeholder('Enter your invoice code')
                    ->helperText('Enter the invoice code from Javara Digital'),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->minLength(8)
                    ->same('passwordConfirmation'),
                TextInput::make('passwordConfirmation')
                    ->password()
                    ->required()
                    ->minLength(8)
                    ->label('Confirm Password'),
            ]);
    }

    public function register(): ?RegistrationResponse
    {
        try {
            $this->validateInvoiceCode();
            return parent::register();
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());

            Notification::make()
                ->title('Registration Failed')
                ->body($e->getMessage())
                ->danger()
                ->send();

            return null;
        }
    }

    protected function validateInvoiceCode(): void
    {
        $invoiceCode = $this->form->getState()['invoice_code'];
        $invoiceCode = str_replace('#', '', $invoiceCode);

        $userHasInvoice = User::where('invoice_code', $invoiceCode)->exists();
        if ($userHasInvoice) {
            Notification::make()
                ->title('Invalid Invoice Code')
                ->body('Invoice code sudah terdaftar sebagai member')
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'invoice_code' => 'Invalid invoice code.',
            ]);
        }

        try {
            $response = Http::timeout(30)->get("https://javaradigital.com/api/transaction/{$invoiceCode}");

            Log::info('Invoice validation response:', [
                'invoice_code' => $invoiceCode,
                'response' => $response->json(),
                'status' => $response->status()
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to connect to invoice validation service. Please try again.');
            }

            $data = $response->json();
            // /dd($data);
            if (!isset($data['success'])) {
                throw new \Exception('Invalid response from invoice validation service.');
            }


            if ($data['data']['status'] != 'PAID') {
                Notification::make()
                    ->title('INVOICE NOT PAID')
                    ->body('Please make payment first, after that u can activate your account with invoice code')
                    ->warning()
                    ->send();
            }

            if (!$data['success']) {
                Notification::make()
                    ->title('Invalid Invoice Code')
                    ->body('The provided invoice code is not valid. Please check and try again.')
                    ->danger()
                    ->send();

                throw ValidationException::withMessages([
                    'invoice_code' => 'Invalid invoice code.',
                ]);
            }
            $sku = $data['data']['order_items'][0]['sku'];
            if (strpos($sku, 'REF') === false) {
                $skui = $sku;
            } else {
                //'#P'.$product->id.'_REF_'.$referralCode
                $sk = explode("_", $sku);
                $skui = $sk[0];
            }
            if ($skui != '#P29') {
                Notification::make()
                    ->title('Not valid invoice code')
                    ->body('The provided invoice code is not valid. Please check and try again.')
                    ->danger()->send();
                throw ValidationException::withMessages([
                    'invoice_code' => 'Invalid invoice code.',
                ]);
            }

            Notification::make()
                ->title('Invoice Validated')
                ->body('Invoice code validation successful.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Log::error('Invoice validation error: ' . $e->getMessage());

            Notification::make()
                ->title('Validation Error')
                ->body($e->getMessage())
                ->danger()
                ->send();

            throw ValidationException::withMessages([
                'invoice_code' => 'Invoice validation failed: ' . $e->getMessage(),
            ]);
        }
    }
}
