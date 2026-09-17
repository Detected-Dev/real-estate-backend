<?php

namespace App\Http\Controllers;

use App\Models\PropertyRequest;
use Illuminate\Http\Request;

class PropertyRequestController extends Controller
{
    /**
     * Display all property requests.
     */
    public function index()
    {
        $requests = PropertyRequest::all();

        return response()->json([
            'message' => 'Demandes récupérées avec succès.',
            'data' => $requests
        ], 200);
    }

    /**
     * Store a new property request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'user_id' => 'required|exists:users,id',
            'agency_id' => 'required|exists:agencies,id',

            'type' => 'required|string|in:purchase,rental,visit',

            'message' => 'nullable|string',

            'offer_price' => 'nullable|numeric|min:0',

            'status' => 'sometimes|string|in:pending,accepted,rejected,cancelled,completed',
        ]);

        $propertyRequest = PropertyRequest::create($validated);

        return response()->json([
            'message' => 'Demande créée avec succès.',
            'data' => $propertyRequest
        ], 201);
    }

    /**
     * Display one property request.
     */
    public function show(PropertyRequest $propertyRequest)
    {
        return response()->json([
            'message' => 'Demande récupérée avec succès.',
            'data' => $propertyRequest
        ], 200);
    }

    /**
     * Update a property request.
     */
    public function update(Request $request, PropertyRequest $propertyRequest)
    {
        $validated = $request->validate([
            'property_id' => 'sometimes|exists:properties,id',
            'user_id' => 'sometimes|exists:users,id',
            'agency_id' => 'sometimes|exists:agencies,id',

            'type' => 'sometimes|string|in:purchase,rental,visit',

            'message' => 'nullable|string',

            'offer_price' => 'nullable|numeric|min:0',

            'status' => 'sometimes|string|in:pending,accepted,rejected,cancelled,completed',
        ]);

        $propertyRequest->update($validated);

        return response()->json([
            'message' => 'Demande modifiée avec succès.',
            'data' => $propertyRequest
        ], 200);
    }

    /**
     * Delete a property request.
     */
    public function destroy(PropertyRequest $propertyRequest)
    {
        $propertyRequest->delete();

        return response()->json([
            'message' => 'Demande supprimée avec succès.'
        ], 200);
    }
}
