<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

/**
 * API controller for JWT authentication (login, register, logout, me).
 */
class AuthController extends Controller
{
    /**
     * Authenticate user and return JWT token.
     *
     * POST /api/login
     *
     * @param  Request  $request  Must contain email and password
     * @return JsonResponse  { token, token_type, expires_in, user }
     * @throws ValidationException  When credentials are invalid
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = $request->only('email', 'password');

        if (! $token = auth('api')->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = auth('api')->user();

        return $this->respondWithToken($token, $user);
    }

    /**
     * Register a new user and return JWT token.
     *
     * POST /api/register
     *
     * @param  Request  $request  Must contain name, email, password, password_confirmation
     * @return JsonResponse  { token, token_type, expires_in, user } (201)
     */
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        $token = auth('api')->login($user);

        return $this->respondWithToken($token, $user, 201);
    }

    /**
     * Invalidate the current JWT (blacklist token).
     *
     * POST /api/logout
     *
     * @param  Request  $request  Requires Bearer token
     * @return JsonResponse  { message }
     */
    public function logout(Request $request): JsonResponse
    {
        auth('api')->logout();

        return response()->json(['message' => 'Logged out.']);
    }

    /**
     * Get the currently authenticated user from JWT.
     *
     * GET /api/me
     *
     * @param  Request  $request  Requires Bearer token
     * @return JsonResponse  { user: { id, name, email, role } }
     */
    public function me(Request $request): JsonResponse
    {
        $user = auth('api')->user();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }

    /**
     * Build JSON response with JWT token and user data.
     *
     * @param  string  $token  JWT token string
     * @param  User  $user  Authenticated user
     * @param  int  $status  HTTP status code (default 200)
     * @return JsonResponse
     */
    private function respondWithToken(string $token, User $user, int $status = 200): JsonResponse
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => (int) config('jwt.ttl') * 60,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ], $status);
    }
}
