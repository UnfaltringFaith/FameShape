<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        // Return a successful response
        return response()->json(['message' => 'User registered successfully', 'token' => $token], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate the user
        if (!Auth::attempt($data)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return a successful response
        return response()->json(['message' => 'User logged in successfully', 'token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            // Revoke the user's token
            $user->currentAccessToken()->delete();
            return response()->json(['message' => 'User logged out successfully'], 200);
        }

        return response()->json(['message' => 'No user authenticated'], 401);
    }
}
