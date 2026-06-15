<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DisplayMiddleware
{
    /**
     * Handle an incoming request.
     * Allow users with 'admin' or 'display' role.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !in_array($request->user()->role, ['admin', 'display'])) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }

            return redirect('/login')->with('error', 'Access denied.');
        }

        return $next($request);
    }
}
