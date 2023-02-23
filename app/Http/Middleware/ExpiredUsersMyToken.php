<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExpiredUsersMyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $fetch = User::where('updated_at', '<', Carbon::now()->subMinutes(2))
        ->whereNotNull('my_token')
        ->get();

        foreach ($fetch as $data) {
        $data->update(['my_token' => null]);
        }

        return $next($request);
    }
}
