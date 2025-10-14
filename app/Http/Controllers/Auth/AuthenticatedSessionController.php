<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Route;


class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $user = $request->user();
        session()->forget('two_factor_authenticated');
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'حسابك غير مفعل. يرجى الاتصال بمسؤول الرقمنة.');
        }

        if ($user->two_factor_enabled && !$request->session()->get('two_factor_pending')) {
            session(['two_factor_pending' => $user->id]);
            return redirect()->route('auth.twofactor-challenge')
                ->with('info', 'يرجى إدخال رمز التحقق لتفعيل تسجيل الدخول.');
        }

        $request->session()->regenerate();
        session()->flash('success', 'تم تسجيل الدخول بنجاح');

        return redirect()->intended(default: route('dashboard', absolute: false));
    }
    public function destroy(Request $request)
    {
        Auth::logout();

        session()->flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }


}