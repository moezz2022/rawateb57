<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required',
            'password' => 'required|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'يرجى إدخال اسم المستخدم أو رقم الهاتف.',
            'password.required' => 'يرجى إدخال كلمة المرور.',
            'password.min' => 'يجب أن تكون كلمة المرور 8 أحرف على الأقل.',
        ];
    }
    

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();
    
        $loginField = filter_var($this->input('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 
            (is_numeric($this->input('username')) ? 'phone' : 'username');
    
        if (!Auth::attempt([$loginField => $this->input('username'), 'password' => $this->input('password')], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());
    
            throw ValidationException::withMessages([
                'username' => 'اسم المستخدم أو رقم الهاتف غير صحيح.',
                'password' => 'كلمة المرور غير صحيحة.',
            ]);
        }
    
        RateLimiter::clear($this->throttleKey());
    }
    

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('username')).'|'.$this->ip());
    }
}
