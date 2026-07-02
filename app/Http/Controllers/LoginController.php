<?php

namespace App\Http\Controllers;

use App\Models\Merchant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Merchant Login
     * Request : email, password
     * Response : JsonResponse
     *
     */
    public function login(Request $request): JsonResponse
    {
        // Request validation
        $validated = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $merchant = Merchant::where('email', $validated['email'])->first();

        if (! $merchant || ! Hash::check($validated['password'], $merchant->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.',
            ], 401);
        }

        if (! $merchant->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Merchant account is inactive.',
            ], 403);
        }

        $token = $merchant->createToken('merchant-api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data' => [
                'merchant' => [
                    'id'    => $merchant->id,
                    'name'  => $merchant->name,
                    'email' => $merchant->email,
                ],
                'token' => $token,
            ]
        ], 200);
    }
}
