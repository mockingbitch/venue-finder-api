<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Ensure the authenticated user has one of the allowed roles.
 * Use as: middleware('role:admin') or middleware('role:admin,editor').
 * Must be used after auth middleware so the user is authenticated.
 */
class EnsureUserHasRole
{
    /**
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $allowed = array_map(
            fn (string $role) => Role::tryFrom($role),
            $roles
        );
        $allowed = array_filter($allowed);

        $userRole = $user->role;
        if (! $userRole || ! in_array($userRole, $allowed, true)) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        return $next($request);
    }
}
