<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Check if user is logged in

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Check if user's role matches any of the allowed roles
        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'You do not have permission to access this page.');
        }
        return $next($request);
    }
}
