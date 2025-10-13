<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIfUserIsActive
{
    /**
     * Gestisce una richiesta entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && ($user->role === 'Evaluator' || $user->role === 'User')) {
            if (!$user->is_active) {
                return redirect()->route('dashboard')->with('error', 'Your account is not active.');
            }
        }

        return $next($request);
    }
}
