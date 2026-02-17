<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Currency Exchange Rate Service
 * 
 * This service fetches and caches exchange rates from external APIs
 * Replace the manual exchange rates in AdminTransactionController with this service
 */
class CurrencyExchangeService
{
    /**
     * Base currency for all conversions
     */
    private $baseCurrency = 'NGN';

    /**
     * Supported currencies in the system
     */
    private $supportedCurrencies = [
        'NGN', 'USD', 'GBP', 'EUR', 'GHS', 'KES', 
        'TZS', 'UGX', 'ZAR', 'XAF', 'XOF'
    ];

    /**
     * Cache duration in seconds (1 hour)
     */
    private $cacheDuration = 3600;

    /**
     * Get exchange rates with caching
     * 
     * @return array
     */
    public function getExchangeRates()
    {
        $cacheKey = 'currency_exchange_rates';

        return Cache::remember($cacheKey, $this->cacheDuration, function () {
            return $this->fetchExchangeRates();
        });
    }

    /**
     * Fetch exchange rates from API
     * Uses exchangerate-api.com (free tier available)
     * 
     * @return array
     */
    private function fetchExchangeRates()
    {
        try {
            // Method 1: ExchangeRate-API (Free tier: 1,500 requests/month)
            $response = Http::timeout(10)->get(
                "https://api.exchangerate-api.com/v4/latest/{$this->baseCurrency}"
            );

            if ($response->successful()) {
                $data = $response->json();
                return $this->formatRates($data['rates']);
            }

            // Fallback to manual rates if API fails
            Log::warning('Exchange rate API failed, using fallback rates');
            return $this->getFallbackRates();

        } catch (\Exception $e) {
            Log::error('Exchange rate fetch failed: ' . $e->getMessage());
            return $this->getFallbackRates();
        }
    }

    /**
     * Alternative API: CurrencyAPI (requires API key)
     * Uncomment and use this if you have an API key
     */
    private function fetchFromCurrencyAPI()
    {
        $apiKey = env('CURRENCY_API_KEY'); // Add to .env file

        try {
            $response = Http::timeout(10)->get('https://api.currencyapi.com/v3/latest', [
                'apikey' => $apiKey,
                'base_currency' => $this->baseCurrency,
                'currencies' => implode(',', $this->supportedCurrencies)
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $this->formatCurrencyAPIRates($data['data']);
            }

            return $this->getFallbackRates();

        } catch (\Exception $e) {
            Log::error('CurrencyAPI fetch failed: ' . $e->getMessage());
            return $this->getFallbackRates();
        }
    }

    /**
     * Alternative API: Fixer.io (requires API key)
     */
    private function fetchFromFixer()
    {
        $apiKey = env('FIXER_API_KEY'); // Add to .env file

        try {
            $response = Http::timeout(10)->get('https://api.fixer.io/latest', [
                'access_key' => $apiKey,
                'base' => $this->baseCurrency,
                'symbols' => implode(',', $this->supportedCurrencies)
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $this->formatRates($data['rates']);
            }

            return $this->getFallbackRates();

        } catch (\Exception $e) {
            Log::error('Fixer API fetch failed: ' . $e->getMessage());
            return $this->getFallbackRates();
        }
    }

    /**
     * Format rates to ensure all supported currencies are present
     * 
     * @param array $rates
     * @return array
     */
    private function formatRates($rates)
    {
        $formatted = [];

        foreach ($this->supportedCurrencies as $currency) {
            $formatted[$currency] = $rates[$currency] ?? 1;
        }

        return $formatted;
    }

    /**
     * Format rates from CurrencyAPI response
     * 
     * @param array $data
     * @return array
     */
    private function formatCurrencyAPIRates($data)
    {
        $formatted = [];

        foreach ($this->supportedCurrencies as $currency) {
            $formatted[$currency] = $data[$currency]['value'] ?? 1;
        }

        return $formatted;
    }

    /**
     * Fallback rates if API is unavailable
     * Update these periodically or use a more reliable API
     * 
     * @return array
     */
    private function getFallbackRates()
    {
        return [
            'NGN' => 1,
            'USD' => 0.0013,      // 1 NGN = 0.0013 USD (approx 770 NGN/USD)
            'GBP' => 0.0010,      // 1 NGN = 0.0010 GBP
            'EUR' => 0.0012,      // 1 NGN = 0.0012 EUR
            'GHS' => 0.016,       // 1 NGN = 0.016 GHS
            'KES' => 0.17,        // 1 NGN = 0.17 KES
            'TZS' => 3.05,        // 1 NGN = 3.05 TZS
            'UGX' => 4.87,        // 1 NGN = 4.87 UGX
            'ZAR' => 0.024,       // 1 NGN = 0.024 ZAR
            'XAF' => 0.78,        // 1 NGN = 0.78 XAF
            'XOF' => 0.78         // 1 NGN = 0.78 XOF
        ];
    }

    /**
     * Convert amount from one currency to another
     * 
     * @param float $amount
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return float
     */
    public function convert($amount, $fromCurrency, $toCurrency)
    {
        $rates = $this->getExchangeRates();

        // Convert to base currency (NGN) first
        $amountInBase = $amount / $rates[$fromCurrency];

        // Then convert to target currency
        $convertedAmount = $amountInBase * $rates[$toCurrency];

        return round($convertedAmount, 2);
    }

    /**
     * Get a specific exchange rate
     * 
     * @param string $currency
     * @return float
     */
    public function getRate($currency)
    {
        $rates = $this->getExchangeRates();
        return $rates[$currency] ?? 1;
    }

    /**
     * Manually update exchange rates (for admin use)
     * 
     * @param array $rates
     * @return void
     */
    public function updateRates($rates)
    {
        Cache::put('currency_exchange_rates', $rates, $this->cacheDuration);
        Log::info('Exchange rates manually updated', ['rates' => $rates]);
    }

    /**
     * Clear cached exchange rates
     * 
     * @return void
     */
    public function clearCache()
    {
        Cache::forget('currency_exchange_rates');
        Log::info('Exchange rate cache cleared');
    }

    /**
     * Get supported currencies
     * 
     * @return array
     */
    public function getSupportedCurrencies()
    {
        return $this->supportedCurrencies;
    }

    /**
     * Get currency symbols
     * 
     * @return array
     */
    public function getCurrencySymbols()
    {
        return [
            'NGN' => '₦',
            'USD' => '$',
            'GBP' => '£',
            'EUR' => '€',
            'GHS' => '₵',
            'KES' => 'KSh',
            'TZS' => 'TSh',
            'UGX' => 'USh',
            'ZAR' => 'R',
            'XAF' => 'FCFA',
            'XOF' => 'CFA'
        ];
    }

    /**
     * Format amount with currency symbol
     * 
     * @param float $amount
     * @param string $currency
     * @return string
     */
    public function formatAmount($amount, $currency)
    {
        $symbols = $this->getCurrencySymbols();
        $symbol = $symbols[$currency] ?? $currency;
        
        return $symbol . ' ' . number_format($amount, 2);
    }
}
