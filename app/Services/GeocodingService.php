<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodingService
{
    /**
     * Convert an address into [latitude, longitude] using the free
     * Nominatim (OpenStreetMap) geocoder. Returns null if no match found
     * or the request fails — callers should handle that gracefully.
     */
    public function geocode(string $address, string $city = '', string $postalCode = ''): ?array
    {
        $query = trim("{$address}, {$city} {$postalCode}");

        try {
            $response = Http::withHeaders([
                // Nominatim requires a real identifying User-Agent
                'User-Agent' => 'YourRealEstateApp/1.0 (contact@yoursite.com)',
            ])->timeout(5)->get('https://nominatim.openstreetmap.org/search', [
                'q' => $query,
                'format' => 'json',
                'limit' => 1,
            ]);

            $results = $response->json();

            if (!empty($results)) {
                return [
                    'latitude' => (float) $results[0]['lat'],
                    'longitude' => (float) $results[0]['lon'],
                ];
            }
        } catch (\Throwable $e) {
            Log::warning("Geocoding failed for \"{$query}\": {$e->getMessage()}");
        }

        return null;
    }
}
