<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (! auth()->check()) {
            return redirect('/login');
        }

        if (! $request->user()->hasApplicationRole($role)) {
            abort(403, 'Unauthorized access');
        }

        return $next($request);
    }
}
