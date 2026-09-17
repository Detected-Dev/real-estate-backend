<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request){
        $validate = $request->validate([
            'name' => ['required' , 'string' , 'max:255'],
            'email' => [
                'required',
                'email',
                'min:8',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed'
            ],
        ]);

        $user = User::create([
            'name' => $validate['name'],
            'email' => $validate['email'],
            'password' => Hash::make($validate['password']),
            'role' => 'user'
        ]);
        $request->session()->regenerate();
        return response()->json([
            'message' => 'Registration successfully',
            'user' => $user,
        ]);
    }

    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    if (!Auth::attempt($credentials, true)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    $request->session()->regenerate();

    return response()->json([
        'message' => 'Login successful',
        'user' => $request->user(),
    ]);
}

    public function user(Request $request){
        return response()->json([
            'user' => $request->user()
        ]);
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json([
            "message" => "Logout sucessful"
        ]);
    }
}
