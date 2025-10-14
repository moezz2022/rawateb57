<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckAdmin
{

public function handle(Request $request, Closure $next)
{

    if (!auth()->check() || auth()->user()->role !== 'admin') {
        return redirect()->route('dashboard')->with('error', 'ليس لديك صلاحية للوصول إلى هذه الصفحة.');
    }

    return $next($request);
}


}

