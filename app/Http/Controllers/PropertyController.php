<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display all properties.
     */
    public function index()
    {
        $properties = Property::all();

        return response()->json([
            'message' => 'Biens récupérés avec succès.',
            'data' => $properties
        ], 200);
    }

    /**
     * Store a new property.
     */
    public function store(Request $request)
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
        return response()->json([
            'message' => 'Bien récupéré avec succès.',
            'data' => $property
        ], 200);
    }

    /**
     * Update a property.
     */
    public function update(Request $request, Property $property)
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
