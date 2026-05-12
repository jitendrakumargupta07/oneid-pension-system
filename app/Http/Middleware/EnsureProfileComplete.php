<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileComplete
{
    /**
     * Redirect user to profile creation if no elderly profile exists yet.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->role === 'user' && ! $user->elderlyProfile) {
            // Allow profile routes and logout
            if (! $request->routeIs('user.profile.*') && ! $request->routeIs('logout')) {
                return redirect()->route('user.profile.create')
                    ->with('info', 'Please complete your profile to continue.');
            }
        }

        return $next($request);
    }
}
