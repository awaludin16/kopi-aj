<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class XenditService
{
    protected string $baseUrl;
    protected string $secretKey;

    public function __construct()
    {
        $this->baseUrl   = config('services.xendit.base_url', 'https://api.xendit.co');
        $this->secretKey = config('services.xendit.secret_key');
    }

    protected function request()
    {
        return Http::withBasicAuth($this->secretKey, '')
            ->acceptJson()
            ->asJson();
    }

    /**
     * Buat invoice pembayaran baru di Xendit
     */
    public function createInvoice(array $payload)
    {
        return $this->request()->post('https://api.xendit.co/v2/invoices', $payload);
    }

    /**
     * Ambil data invoice berdasarkan ID invoice Xendit
     */
    public function getInvoice(string $invoiceId)
    {
        return $this->request()->get("https://api.xendit.co/v2/invoices/{$invoiceId}");
    }

    /**
     * Cari invoice berdasarkan external_id
     */
    public function getInvoiceByExternalId(string $externalId)
    {
        return $this->request()->get("https://api.xendit.co/v2/invoices", [
            'external_id' => $externalId
        ]);
    }
}
