<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware to restrict access based on user role.
 * Usage: ->middleware('role:chef_pole') or ->middleware('role:magasinier')
 */
class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Checks if the authenticated user has the required role.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check() || Auth::user()->role !== $role) {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}
