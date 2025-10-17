<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * @var string URL endpoint dari Piwapi
     */
    protected string $apiUrl = "https://piwapi.com/api/send/whatsapp";

    /**
     * Mengirim pesan ke API WhatsApp menggunakan Laravel HTTP Client.
     *
     * @param string $phoneNumber Nomor telepon penerima
     * @param string $message Isi pesan yang akan dikirim
     * @return bool Mengembalikan true jika berhasil, false jika gagal.
     */
    public function sendMessage(string $phoneNumber, string $message): bool
    {
        // Ambil kredensial dari file .env
        $apiSecret = env('PIWAPI_SECRET');
        $accountId = env('PIWAPI_ACCOUNT_ID');

        // Validasi awal: pastikan kredensial sudah diatur
        if (!$apiSecret || !$accountId) {
            Log::critical('Kredensial Piwapi (PIWAPI_SECRET atau PIWAPI_ACCOUNT_ID) belum diatur di file .env.');
            return false;
        }

        try {
            // Kirim request POST menggunakan Laravel HTTP Client
            $response = Http::timeout(20) // Timeout 20 detik
                ->asForm() // Mengirim data sebagai application/x-www-form-urlencoded
                ->post($this->apiUrl, [
                    'secret'    => $apiSecret,
                    'account'   => $accountId,
                    'recipient' => $phoneNumber,
                    'type'      => 'text',
                    'message'   => $message,
                ]);

            // Cek apakah request berhasil (status code 2xx)
            if ($response->successful()) {
                Log::info("Pesan WhatsApp berhasil dikirim ke {$phoneNumber}.", ['response' => $response->body()]);
                return true;
            }
            
            // Jika request gagal (status code 4xx atau 5xx)
            Log::error("Gagal mengirim pesan WhatsApp ke {$phoneNumber}", [
                'status_code' => $response->status(),
                'response' => $response->body()
            ]);
            return false;

        } catch (\Throwable $e) {
            // Menangkap error koneksi, timeout, atau exception lainnya
            Log::critical("Exception saat mengirim WhatsApp ke Piwapi", [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}