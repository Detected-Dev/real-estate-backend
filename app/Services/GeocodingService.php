<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodingService
{
    public function geocode(string $address, string $city = ''): ?array
    {
        $query = trim("{$address}, {$city}");

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'YourRealEstateApp/1.0 (contact@yoursite.com)',
            ])->timeout(5)->get(
                'https://nominatim.openstreetmap.org/search',
                [
                    'q' => $query,
                    'format' => 'json',
                    'limit' => 1,
                ]
            );

            $results = $response->json();

            if (!empty($results)) {
                return [
                    'latitude' => (float) $results[0]['lat'],
                    'longitude' => (float) $results[0]['lon'],
                ];
            }
        } catch (\Throwable $e) {
            Log::warning(
                "Geocoding failed for \"{$query}\": {$e->getMessage()}"
            );
        }

        return null;
    }
}
