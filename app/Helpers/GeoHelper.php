<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class GeoHelper
{
    public static function lookup($ip)
    {
        if (!$ip || $ip === '127.0.0.1') {
            return 'Localhost';
        }

        try {
            $response = Http::get("https://ipapi.co/{$ip}/json/");
            if ($response->successful()) {
                $data = $response->json();
                return "{$data['city']}, {$data['country_name']}";
            }
        } catch (\Exception $e) {
            return 'Unknown';
        }

        return 'Unknown';
    }
}
