<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class TwoFactorMiddleware
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->two_factor_enabled && !session()->get('two_factor_authenticated')) {
            session()->forget('two_factor_authenticated');
            return redirect()->route('auth.twofactor-challenge')->with('error', 'يجب التحقق من المصادقة الثنائية.');
        }

        return $next($request);
    }
}
