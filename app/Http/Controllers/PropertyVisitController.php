<?php

namespace App\Http\Controllers;

use App\Models\PropertyVisit;
use Illuminate\Http\Request;

class PropertyVisitController extends Controller
{
    /**
     * Display all property visits.
     */
    public function index()
    {
        $visits = PropertyVisit::all();

        return response()->json([
            'message' => 'Visites récupérées avec succès.',
            'data' => $visits
        ], 200);
    }

    /**
     * Store a new property visit.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'user_id' => 'required|exists:users,id',
            'agency_id' => 'required|exists:agencies,id',

            'scheduled_at' => 'required|date',

            'message' => 'nullable|string',

            'status' => 'sometimes|string|in:pending,confirmed,completed,cancelled',
        ]);

        $visit = PropertyVisit::create($validated);

        return response()->json([
            'message' => 'Visite créée avec succès.',
            'data' => $visit
        ], 201);
    }

    /**
     * Display one property visit.
     */
    public function show(PropertyVisit $propertyVisit)
    {
        return response()->json([
            'message' => 'Visite récupérée avec succès.',
            'data' => $propertyVisit
        ], 200);
    }

    /**
     * Update a property visit.
     */
    public function update(Request $request, PropertyVisit $propertyVisit)
    {
        $validated = $request->validate([
            'property_id' => 'sometimes|exists:properties,id',
            'user_id' => 'sometimes|exists:users,id',
            'agency_id' => 'sometimes|exists:agencies,id',

            'scheduled_at' => 'sometimes|date',

            'message' => 'nullable|string',

            'status' => 'sometimes|string|in:pending,confirmed,completed,cancelled',
        ]);

        $propertyVisit->update($validated);

        return response()->json([
            'message' => 'Visite modifiée avec succès.',
            'data' => $propertyVisit
        ], 200);
    }

    /**
     * Delete a property visit.
     */
    public function destroy(PropertyVisit $propertyVisit)
    {
        $propertyVisit->delete();

        return response()->json([
            'message' => 'Visite supprimée avec succès.'
        ], 200);
    }
}
