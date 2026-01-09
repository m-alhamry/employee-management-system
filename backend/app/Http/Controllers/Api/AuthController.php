<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Handle user login and return authentication token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->findUserByEmail($request->email);

        if (!$this->validateCredentials($user, $request->password)) {
            return $this->unauthorizedResponse();
        }

        $token = $this->createToken($user);

        return $this->loginSuccessResponse($user, $token);
    }

    /**
     * Handle user logout and revoke token.
     */
    public function logout(Request $request): JsonResponse
    {
        $this->revokeCurrentToken($request->user());

        return $this->logoutSuccessResponse();
    }

    /**
     * Find user by email address.
     */
    private function findUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Validate user credentials.
     */
    private function validateCredentials(?User $user, string $password): bool
    {
        if (!$user) {
            return false;
        }

        return Hash::check($password, $user->password);
    }

    /**
     * Create authentication token for user.
     */
    private function createToken(User $user): string
    {
        return $user->createToken('auth-token')->plainTextToken;
    }

    /**
     * Revoke current authentication token.
     */
    private function revokeCurrentToken(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    /**
     * Return unauthorized response.
     */
    private function unauthorizedResponse(): JsonResponse
    {
        return response()->json([
            'message' => 'Invalid credentials'
        ], 401);
    }

    /**
     * Return successful login response.
     */
    private function loginSuccessResponse(User $user, string $token): JsonResponse
    {
        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ], 200);
    }

    /**
     * Return successful logout response.
     */
    private function logoutSuccessResponse(): JsonResponse
    {
        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }
}
