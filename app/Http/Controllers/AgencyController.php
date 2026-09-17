<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    /**
     * Display all agencies.
     */
    public function index()
    {
        $agencies = Agency::all();

        return response()->json([
            'message' => 'Agences récupérées avec succès.',
            'data' => $agencies
        ], 200);
    }

    /**
     * Store a new agency.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'telephone' => 'required|string|max:30',
            'email' => 'required|email|max:255',
            'logo' => 'nullable|string|max:255',
        ]);

        $agency = Agency::create($validated);

        return response()->json([
            'message' => 'Agence créée avec succès.',
            'data' => $agency
        ], 201);
    }

    /**
     * Display one agency.
     */
    public function show(Agency $agency)
    {
        return response()->json([
            'message' => 'Agence récupérée avec succès.',
            'data' => $agency
        ], 200);
    }

    /**
     * Update an agency.
     */
    public function update(Request $request, Agency $agency)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'address' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:255',
            'telephone' => 'sometimes|string|max:30',
            'email' => 'sometimes|email|max:255',
            'logo' => 'nullable|string|max:255',
        ]);

        $agency->update($validated);

        return response()->json([
            'message' => 'Agence modifiée avec succès.',
            'data' => $agency
        ], 200);
    }

    /**
     * Delete an agency.
     */
    public function destroy(Agency $agency)
    {
        $agency->delete();

        return response()->json([
            'message' => 'Agence supprimée avec succès.'
        ], 200);
    }
}
