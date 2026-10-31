<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class FonnteService
{
    protected string $baseUrl;

    protected ?string $token;

    public function __construct()
    {
        $this->baseUrl = config('services.fonnte.base_url', 'https://api.fonnte.com');
        $this->token = config('services.fonnte.token');
    }

    /**
     * Kirim pesan WhatsApp melalui Fonnte.
     *
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>
     */
    public function send(string $target, string $message, array $options = []): array
    {
        if (blank($this->token)) {
            throw new RuntimeException('FONNTE_TOKEN belum dikonfigurasi.');
        }

        $response = Http::withHeaders([
            'Authorization' => $this->token,
        ])->asForm()->post($this->baseUrl . '/send', array_merge([
            'target' => $target,
            'message' => $message,
        ], $options));

        if ($response->failed()) {
            Log::error('Fonnte send failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        }

        return $response->json() ?? [];
    }
}
