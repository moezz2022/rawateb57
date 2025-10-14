<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    public function create()
    {
        $user = Auth::user();

        if (!$user->two_factor_secret) {
            $user->two_factor_secret = (new Google2FA())->generateSecretKey();
            $user->save();
        }
        return view('auth.twofactorchallenge', [
            'username' => $user->name,
            'secret' => $user->two_factor_secret,
            'user' => $user,
        ]);
    }
    
    private function isValidOtp(string $otp, string $secret): bool
    {
        return (new Google2FA())->verifyKey($secret, $otp);
    }

    public function enable(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ], [
            'otp.required' => 'يرجى إدخال رمز التحقق.',
            'otp.digits' => 'رمز التحقق يجب أن يتكون من 6 أرقام.',
        ]);
    
        $user = $request->user();
    
        if (!$user->two_factor_secret) {
            return redirect()->back()->with('error', 'لا يمكن تفعيل المصادقة الثنائية دون إنشاء مفتاح سري.');
        }
    
        if (!$this->isValidOtp($request->otp, $user->two_factor_secret)) {
            return redirect()->back()->with('error', 'رمز التحقق غير صحيح.');
        }
    
        $user->update(['two_factor_enabled' => true]);
    
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect()->route('login')->with('success', 'تم تفعيل المصادقة الثنائية بنجاح. يرجى تسجيل الدخول مرة أخرى.');
    }
    

    public function disable(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);
        $user = $request->user();
        if (!$user->two_factor_enabled) {
            return redirect()->back()->withErrors(['error' => 'المصادقة الثنائية غير مفعلة.']);
        }
        if (!$this->isValidOtp($request->otp, $user->two_factor_secret)) {
            return redirect()->back()->withErrors(['error' => 'رمز OTP غير صحيح.']);
        }
        $user->update([
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
        ]);
        return redirect()->back()->with('error', 'تم تعطيل المصادقة الثنائية بنجاح.');
    }
    public function showTwoFactorForm()
    {
        if (!session('two_factor_pending')) {
            return redirect()->route('login')->with('error', 'يرجى تسجيل الدخول أولاً.');
        }
        return view('auth.twofactor-challenge');
    }

    public function verifyTwoFactor(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);
        $user = Auth::user();
        if ($this->isValidOtp($request->otp, $user->two_factor_secret)) {
            session(['two_factor_authenticated' => true]);

            return redirect()->route('dashboard')
                ->with('success', 'تم تسجيل الدخول بنجاح.');
        } else {
            return back()->with('error', 'رمز التحقق غير صحيح. حاول مرة أخرى.');
        }
    }

}
