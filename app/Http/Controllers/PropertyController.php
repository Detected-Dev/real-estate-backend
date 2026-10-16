<?php

namespace App\Http\Controllers;

use App\Filters\PropertiesFilter;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Services\GeocodingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
{
    /**
     * Display all properties.
     */
    public function index(Request $request , PropertiesFilter $filter)
    {
        $filterConditions = $filter->transform($request);

        $properties = Property::where($filterConditions);

        $perPage = $request->get('limit', 20);

        return $properties->latest()->with('propertyType' , 'images')->paginate($perPage);
    }

    /**
     * Store a new property.
     */
    public function store(Request $request ,GeocodingService $geocoder)
    {
        $validated = $request->validate([
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
            'images' => 'required|array|min:3',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $coords = $geocoder->geocode(
            $validated['address'],
            $validated['city'],
        );

        $validated['latitude'] = $coords['latitude'] ?? null;
        $validated['longitude'] = $coords['longitude'] ?? null;

        DB::beginTransaction();

        try{
            $property = Property::create([
                'owner_id' => $request->user()->id,
                'agency_id' => $validated['agency_id'],
                'property_type_id' => $validated['property_type_id'],
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'transaction_type' => $validated['transaction_type'],
                'price' => $validated['price'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'surface' => $validated['surface'] ?? null,
                'bedrooms' => $validated['bedrooms'] ?? null,
                'bathrooms' => $validated['bathrooms'] ?? null,
                'floors' => $validated['floors'] ?? null,
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
            ]);

            if($request->hasFile('images')){
                foreach($request->file('images') as $index => $image){
                    $path = $image->store('properties/images' , 'public');
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'image_url' => $path,
                        'is_primary' => $index === 0,
                    ]);
                }
            }
            DB::commit();
        }catch(\Throwable $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create property',
                'error' => $e->getMessage()
            ]);
        }


        return response()->json([
            'message' => 'Property Created successfully.',
            'data' => $property
        ], 201);
    }

    /**
     * Display one property.
     */
    public function show(Property $property)
    {
        $property = $property->load('propertyType', 'agency', 'images','visits');
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
