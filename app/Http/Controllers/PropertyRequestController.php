<?php

namespace App\Http\Controllers;

use App\Models\Property;
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
            'agency_id' => 'required|exists:agencies,id',
            'type' => 'required|string|in:rent,sale',
            'message' => 'nullable|string',
            'offer_price' => 'required|numeric|min:0',
        ]);

        $propertyRequest = PropertyRequest::create([
            'property_id' => $validated['property_id'],
            'user_id' => $request->user()->id,
            'agency_id' => $validated['agency_id'],
            'type' => $validated['type'],
            'message' => $validated['message'],
            'offer_price' => $validated['offer_price'],
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Request has been created successfully',
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
    // Other Function :
    public function myRequest(Property $property , Request $request){
        $propertyId = $property->id ;
        $userId = $request->user()->id ;
        $myRequest = PropertyRequest::where([['property_id' , $propertyId],['user_id' , $userId]])->latest()->first();
        return response()->json([
            'data' =>  $myRequest ,
        ]);
    }

    public function accept (PropertyRequest $propertyRequest){
        $propertyRequest->update([
            'status' => 'accepted'
        ]);
        return response()->json([
            'message' => 'property Request has been accepted',
        ]);
    }
    public function reject (PropertyRequest $propertyRequest){
        $propertyRequest->update([
            'status' => 'rejected'
        ]);
        return response()->json([
            'message' => 'property Request has been rejected',
        ]);
    }

}
