<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class check_level
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$levels)
    {
        if (!$request->user() || !in_array($request->user()->level, $levels)) {
            abort(403, 'Unauthorized action.');
        }
        
        // the middleware checks if the authenticated user has a role that matches 
        // one of the specified roles in the middleware parameters. If the user doesn't 
        // have one of the roles, a 403 Forbidden HTTP response is returned.

        return $next($request);
    }
}
