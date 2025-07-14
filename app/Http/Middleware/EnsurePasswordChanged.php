<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and must change password
        if (Auth::check() && Auth::user()->must_change_password) {
            // Redirect to public password change route
            return redirect()->route('password.force-change')
                ->with('warning', 'Você deve alterar sua senha antes de acessar o sistema.');
        }

        return $next($request);
    }
}
