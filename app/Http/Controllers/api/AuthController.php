<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller

{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('api-token')->plainTextToken;
            return response()->json(['token' => $token, 'user' => $user, 'status' => "true"], 200);
        } else {
            return response()->json(['error' => 'No Autorizado', 'status' => "false"], 200);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out'], 200);
    }
}
