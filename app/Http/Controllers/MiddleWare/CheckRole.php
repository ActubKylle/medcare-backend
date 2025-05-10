<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Get authenticated user
        $user = Auth::user();

        // Check if user has any of the specified roles
        if (!in_array($user->role, $roles)) {
            return response()->json(['message' => 'Unauthorized. Insufficient permissions.'], 403);
        }

        return $next($request);
    }
}
