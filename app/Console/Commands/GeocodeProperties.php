<?php

namespace App\Console\Commands;

use App\Models\Property;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GeocodeProperties extends Command
{
    protected $signature = 'properties:geocode';
    protected $description = 'Fill in latitude/longitude for properties that don\'t have them yet, using the free Nominatim (OpenStreetMap) geocoder.';

    public function handle(): int
    {
        $properties = Property::whereNull('latitude')
            ->orWhereNull('longitude')
            ->get();

        if ($properties->isEmpty()) {
            $this->info('Nothing to geocode — every property already has coordinates.');
            return self::SUCCESS;
        }

        $this->info("Geocoding {$properties->count()} properties...");

        foreach ($properties as $property) {
            $query = trim("{$property->address}, {$property->city} {$property->postal_code}");

            // Nominatim usage policy: max 1 request per second, and a real User-Agent is required
            $response = Http::withHeaders([
                'User-Agent' => 'YourRealEstateApp/1.0 (contact@yoursite.com)',
            ])->get('https://nominatim.openstreetmap.org/search', [
                'q' => $query,
                'format' => 'json',
                'limit' => 1,
            ]);

            $results = $response->json();

            if (!empty($results)) {
                $property->update([
                    'latitude' => $results[0]['lat'],
                    'longitude' => $results[0]['lon'],
                ]);
                $this->line("✔ Property #{$property->id}: {$query} -> {$results[0]['lat']}, {$results[0]['lon']}");
            } else {
                $this->warn("✘ Property #{$property->id}: no match found for \"{$query}\" — set coordinates manually.");
            }

            // Respect Nominatim's rate limit (free tier: 1 request/second)
            sleep(1);
        }

        $this->info('Done.');
        return self::SUCCESS;
    }
}
