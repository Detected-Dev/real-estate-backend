<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Services\GeocodingService;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display all properties.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('limit', 20);
        $properties = Property::latest()->with('propertyType' , 'images')->paginate($perPage);
        return $properties;
    }

    /**
     * Store a new property.
     */
    public function store(Request $request ,GeocodingService $geocoder)
    {
        $validated = $request->validate([
            'owner_id' => 'required|exists:users,id',
            'agency_id' => 'required|exists:agencies,id',
            'property_type_id' => 'required|exists:property_types,id',

            'title' => 'required|string|max:255',
            'description' => 'nullable|string',

            'transaction_type' => 'required|string|in:sale,rent',

            'price' => 'required|numeric|min:0',

            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',

            'surface' => 'required|numeric|min:0',

            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'floors' => 'nullable|integer|min:0',

            'status' => 'sometimes|string|in:draft,pending_approval,available,reserved,sold,rented,rejected,inactive',
        ]);
        $coords = $geocoder->geocode(
            $validated['address'],
            $validated['city'],
            $validated['postal_code'] ?? ''
        );

        $validated['latitude'] = $coords['latitude'] ?? null;
        $validated['longitude'] = $coords['longitude'] ?? null;

        $property = Property::create($validated);

        return response()->json([
            'message' => 'Bien créé avec succès.',
            'data' => $property
        ], 201);
    }

    /**
     * Display one property.
     */
    public function show(Property $property)
    {
        $property = $property->load('propertyType', 'agency');
        return response()->json([
            'message' => 'Bien récupéré avec succès.',
            'data' => $property
        ], 200);
    }

    /**
     * Update a property.
     */
    public function update(Request $request, Property $property, GeocodingService $geocoder)
    {
        $validated = $request->validate([
            'owner_id' => 'sometimes|exists:users,id',
            'agency_id' => 'sometimes|exists:agencies,id',
            'property_type_id' => 'sometimes|exists:property_types,id',

            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',

            'transaction_type' => 'sometimes|string|in:sale,rent',

            'price' => 'sometimes|numeric|min:0',

            'address' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:255',
            'postal_code' => 'nullable|string|max:20',

            'surface' => 'sometimes|numeric|min:0',

            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'floors' => 'nullable|integer|min:0',

            'status' => 'sometimes|string|in:draft,pending_approval,available,reserved,sold,rented,rejected,inactive',
        ]);

        if (
            $validated['address'] !== $property->address ||
            $validated['city'] !== $property->city
        ) {
            $coords = $geocoder->geocode(
                $validated['address'],
                $validated['city'],
                $validated['postal_code'] ?? ''
            );
            $validated['latitude'] = $coords['latitude'] ?? $property->latitude;
            $validated['longitude'] = $coords['longitude'] ?? $property->longitude;
        }

        $property->update($validated);

        return response()->json([
            'message' => 'Bien modifié avec succès.',
            'data' => $property
        ], 200);
    }

    /**
     * Delete a property.
     */
    public function destroy(Property $property)
    {
        $property->delete();

        return response()->json([
            'message' => 'Bien supprimé avec succès.'
        ], 200);
    }
}
