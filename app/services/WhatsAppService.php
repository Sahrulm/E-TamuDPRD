<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $token;

    public function __construct()
    {
        $this->token = config('services.fonnte.token', '');
    }

    /**
     * Kirim pesan WA lewat Fonnte
     *
     * @param string $phone   Nomor tanpa +62, boleh dengan 0 depan (misal: 081234567890)
     * @param string $message Isi pesan
     * @return bool sukses / gagal
     */
    public function sendMessage(string $phone, string $message): bool
    {
        if (empty($this->token)) {
            Log::warning('Fonnte token belum di-set.');
            return false;
        }

        // Normalisasi nomor: buang spasi, -, dll
        $phone = preg_replace('/\D+/', '', $phone);

        // Kalau nomor mulai dari "0", ubah ke 62 (Indonesia)
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post('https://api.fonnte.com/send', [
                'target'  => $phone,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info('WA terkirim via Fonnte', [
                    'phone' => $phone,
                    'response' => $response->json(),
                ]);
                return true;
            }

            Log::error('Gagal kirim WA via Fonnte', [
                'phone' => $phone,
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Error kirim WA via Fonnte', [
                'phone' => $phone,
                'error' => $e->getMessage(),
            ]);
        }

        return false;
    }
}
