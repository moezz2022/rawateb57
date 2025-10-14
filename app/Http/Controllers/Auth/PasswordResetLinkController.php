<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Support\Facades\RateLimiter;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = $request->only('email');

        // Check rate limiting
        $key = 'password-reset-attempts:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors([
                'error' => 'لقد تجاوزت الحد المسموح به من المحاولات. يرجى المحاولة مرة أخرى بعد بضع دقائق.',
            ]);
        }

        // Send reset link
        $status = Password::sendResetLink($email);

        // Increment the rate limit counter
        RateLimiter::hit($key, 60); // 60 seconds cooldown period per failed attempt

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('success', __('تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني!'))
                    : back()->withInput($email)
                            ->withErrors(['error' => __('لم نتمكن من العثور على حساب مرتبط بهذا البريد الإلكتروني.')]);
    }
}
