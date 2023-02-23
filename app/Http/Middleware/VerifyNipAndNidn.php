<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyNipAndNidn
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
        $user = Auth::user();
        $currentUrl = $request->url();
        $logoutUrl = route('logout');

        // if (!$user->nip || !$user->nidn) {
        //     return redirect()->route('dosen.verify_nip_and_nidn');
        // }

        // if ($user && (!$user->nip || !$user->nidn)) {
        //     return redirect()->route('dosen.verify_nip_and_nidn');
        // }

        if ($currentUrl !== $logoutUrl) {
            // Perform the NIP and NIDN check here
            
            if ($user && (!$user->nip || !$user->nidn) && !$request->is('dosen/verifikasi-nip-dan-nidn') && $user->level == 'dosen') {
                return redirect()->route('dosen.verify_nip_and_nidn');
            }
        }
        
       
        
        return $next($request);
    }
}
