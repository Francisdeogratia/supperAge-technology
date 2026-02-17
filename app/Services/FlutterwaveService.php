<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FlutterwaveService
{
    protected $baseUrl;
    protected $secretKey;

    public function __construct()
    {
        $this->baseUrl = 'https://api.flutterwave.com/v3';
        $this->secretKey = env('FLW_SECRET_KEY');
    }

    /**
     * Get list of Nigerian banks and their codes
     */
    public function getBanks($country = 'NG')
    {
        $response = Http::withToken($this->secretKey)
            ->get("{$this->baseUrl}/banks/{$country}");

        return $response->json();
    }

    /**
     * Initiate a transfer to a bank account
     */
    public function initiateTransfer($bankCode, $accountNumber, $amount, $narration, $currency = 'NGN')
{
    $payload = [
        'account_bank'   => $bankCode,
        'account_number' => $accountNumber,
        'amount'         => $amount,
        'narration'      => $narration,
        'currency'       => $currency,
        'reference'      => uniqid('wd_'),
        'debit_currency' => $currency
    ];

    $response = Http::withToken($this->secretKey)
        ->post("{$this->baseUrl}/transfers", $payload);

    return $response->json();
}

    /**
     * Verify webhook signature
     */
    public function verifyWebhook($request)
    {
        $signature = $request->header('verif-hash');
        return $signature && $signature === env('FLW_SECRET_HASH');
    }
}
