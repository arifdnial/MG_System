<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string...$roles): Response
    {
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            if ($request->user()) {
                return redirect()->route($request->user()->dashboardRoute())
                    ->with('error', 'You do not have permission to access that page.');
            }
            return redirect()->route('login');
        }

        return $next($request);
    }
}
