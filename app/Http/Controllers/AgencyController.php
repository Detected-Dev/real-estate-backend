<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\User;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AgencyController extends Controller
{
    /**
     * Display all agencies.
     */
    public function index()
    {
        $agencies = Agency::with('user' , 'properties')->get();

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
            'email' => [
                'required',
                'email',
                'min:8',
                'unique:users,email',
            ],
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        DB::beginTransaction();
        try{

            $profileImagePath = null ;
            if($request->hasFile('profile_image')){
                $profileImagePath = $request->file('profile_image')->store('users/profile_images' , 'public');
            }

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'agence',
                'profile_image' => $profileImagePath,
            ]);

            $agency = Agency::create([
                'name' => $validated['name'],
                'user_id' => $user->id,
                'email' => $validated['email'],
                'telephone' => $validated['telephone'],
                'description' => $validated['description'] ?? null,
                'address' => $validated['address'],
                'city' => $validated['city'],
                'logo' => $validated['logo'] ?? null,
            ]);

            DB::commit(); // SUbmit  the transaction ;
            return response()->json([
                'message' => 'Agency created Successfully',
            ]);

        }catch(\Throwable $e){
            DB::rollBack();
            return response()->json([
                'message' => 'FAiled to create agency',
                'error' => $e->getMessage(),
            ],500);
        }
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
            'email' => [
                'required',
                'email',
                'min:8',
                'unique:users,email,'. $agency->user_id,
            ],
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
        if ($agency->properties()->exists()) {
            return response()->json([
                'message' => 'This agency cannot be deleted because it has properties.'
            ], 409);
        }

        DB::beginTransaction();
        try{
            $user = $agency->user;
            $agency->delete();
            $user->delete();
            DB::commit();
            return response()->json([
                "message" => "agency and associated user has deleted successfully",
            ]);
        }catch(\Throwable $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to delete agency',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
