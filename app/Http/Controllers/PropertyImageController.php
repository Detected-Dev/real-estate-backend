<?php

namespace App\Http\Controllers;

use App\Models\PropertyImage;
use Illuminate\Http\Request;

class PropertyImageController extends Controller
{
    /**
     * Display all property images.
     */
    public function index()
    {
        $images = PropertyImage::all();

        return response()->json([
            'message' => 'Images récupérées avec succès.',
            'data' => $images
        ], 200);
    }

    /**
     * Store a new property image.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'image_url' => 'required|string|max:255',
            'is_primary' => 'sometimes|boolean',
        ]);

        $image = PropertyImage::create($validated);

        return response()->json([
            'message' => 'Image ajoutée avec succès.',
            'data' => $image
        ], 201);
    }

    /**
     * Display one property image.
     */
    public function show(PropertyImage $propertyImage)
    {
        return response()->json([
            'message' => 'Image récupérée avec succès.',
            'data' => $propertyImage
        ], 200);
    }

    /**
     * Update a property image.
     */
    public function update(Request $request, PropertyImage $propertyImage)
    {
        $validated = $request->validate([
            'property_id' => 'sometimes|exists:properties,id',
            'image_url' => 'sometimes|string|max:255',
            'is_primary' => 'sometimes|boolean',
        ]);

        $propertyImage->update($validated);

        return response()->json([
            'message' => 'Image modifiée avec succès.',
            'data' => $propertyImage
        ], 200);
    }

    /**
     * Delete a property image.
     */
    public function destroy(PropertyImage $propertyImage)
    {
        $propertyImage->delete();

        return response()->json([
            'message' => 'Image supprimée avec succès.'
        ], 200);
    }
}
