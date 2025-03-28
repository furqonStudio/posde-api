<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\User\UserSimpleResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends BaseController
{
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            return $this->successResponse(new UserSimpleResource($user), 'User registered successfully', 201);
        } catch (Exception $e) {
            Log::error('Error registering user: ' . $e->getMessage());
            return $this->errorResponse('Failed to register user', 500);
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();

            $user = User::where('email', $validated['email'])->first();

            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return $this->errorResponse('Invalid credentials. Please try again.', 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->successResponse([
                'user' => new UserSimpleResource($user),
                'token' => $token,
            ], 'Login successful');
        } catch (Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return $this->errorResponse('Failed to process login', 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return $this->successResponse(null, 'Logged out successfully');
        } catch (Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return $this->errorResponse('Failed to logout', 500);
        }
    }

    public function validateToken(Request $request)
    {
        try {
            $token = $request->bearerToken();
            if (!$token) {
                return response()->json(['message' => 'Token not provided'], 401);
            }

            // Validasi token menggunakan Sanctum atau JWT
            $user = Auth::guard('sanctum')->user();
            if (!$user) {
                return response()->json(['message' => 'Invalid token'], 401);
            }

            return response()->json(['message' => 'Token valid'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid token'], 401);
        }
    }
}
