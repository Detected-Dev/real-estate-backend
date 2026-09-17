<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Afficher tous les utilisateurs.
     */
    public function index()
    {
        $users = User::select(
            'id',
            'name',
            'email',
            'phone',
            'profile_image',
            'role',
            'email_verified_at',
            'created_at',
            'updated_at'
        )->get();

        return response()->json([
            'message' => 'Utilisateurs récupérés avec succès.',
            'data' => $users
        ], 200);
    }

    /**
     * Créer un utilisateur.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'email' => 'required|email|max:255|unique:users,email',

            'phone' => 'nullable|string|max:30',

            'profile_image' => 'nullable|string|max:255',

            'role' => [
                'sometimes',
                Rule::in(['user', 'agent', 'admin'])
            ],

            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'profile_image' => $validated['profile_image'] ?? null,
            'role' => $validated['role'] ?? 'user',
            'password' => $validated['password'],
        ]);

        return response()->json([
            'message' => 'Utilisateur créé avec succès.',
            'data' => $user
        ], 201);
    }

    /**
     * Afficher un utilisateur.
     */
    public function show(User $user)
    {
        return response()->json([
            'message' => 'Utilisateur récupéré avec succès.',
            'data' => $user
        ], 200);
    }

    /**
     * Modifier un utilisateur.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',

            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'phone' => 'nullable|string|max:30',

            'profile_image' => 'nullable|string|max:255',

            'role' => [
                'sometimes',
                Rule::in(['user', 'agent', 'admin'])
            ],

            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Utilisateur modifié avec succès.',
            'data' => $user->fresh()
        ], 200);
    }

    /**
     * Supprimer un utilisateur.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'Utilisateur supprimé avec succès.'
        ], 200);
    }
}
