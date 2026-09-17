<?php

namespace App\Http\Controllers;

use App\Models\PropertyType;
use Illuminate\Http\Request;

class PropertyTypeController extends Controller
{
    /**
     * Display all property types.
     */
    public function index()
    {
        $propertyTypes = PropertyType::all();

        return response()->json([
            'message' => 'Types de biens récupérés avec succès.',
            'data' => $propertyTypes
        ], 200);
    }

    /**
     * Store a new property type.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $propertyType = PropertyType::create($validated);

        return response()->json([
            'message' => 'Type de bien créé avec succès.',
            'data' => $propertyType
        ], 201);
    }

    /**
     * Display one property type.
     */
    public function show(PropertyType $propertyType)
    {
        return response()->json([
            'message' => 'Type de bien récupéré avec succès.',
            'data' => $propertyType
        ], 200);
    }

    /**
     * Update a property type.
     */
    public function update(Request $request, PropertyType $propertyType)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);

        $propertyType->update($validated);

        return response()->json([
            'message' => 'Type de bien modifié avec succès.',
            'data' => $propertyType
        ], 200);
    }

    /**
     * Delete a property type.
     */
    public function destroy(PropertyType $propertyType)
    {
        $propertyType->delete();

        return response()->json([
            'message' => 'Type de bien supprimé avec succès.'
        ], 200);
    }
}
