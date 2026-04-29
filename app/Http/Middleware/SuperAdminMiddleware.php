<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard('super_admin')->check()) {
            return $next($request);
        }

        return redirect()->route('super-admin.auth.login');
    }
}
