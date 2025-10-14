@extends('layouts.master3')
@section('content')
<div class="page-login">
    <div class="login-container card">
        <div class="card-body animated fadeInDown">
            <div class="header_section text-center mb-4">
            <a href="{{ route('auth.index') }}"><img src="{{ asset('assets/img/brand/logo57.png') }}"
                        class="sign-favicon ht-40" alt="logo">
                </a>
                <h1 class="main-logo1">مديرية التربية لولاية المغير</h1>
            </div>
            <h5 class="text-center font-weight-semibold mb-4">
                قم بتسجيل الدخول باستخدام اسم المستخدم أو رقم الهاتف وكلمة المرور
            </h5>
            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                <div class="form-group">
                    <label for="username" class="form-label">اسم المستخدم أو رقم الهاتف</label>
                    <input id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                        name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                    @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group position-relative">
                    <label>كلمة المرور</label>
                    <div class="input-group position-relative">
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" name="password" required
                            autocomplete="current-password">
                        <span class="toggle-password"
                            onclick="togglePasswordVisibility('password', 'toggleIconCurrent')">
                            <i class="fa fa-eye" id="toggleIconCurrent"></i>
                        </span>
                    </div>
                    @error('password')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary mt1 bg-purple btn-block">تسجيل الدخول</button>
            </form>
            <div class="text-center mt-3">
                <p class="mb-0">هل نسيت كلمة المرور؟ <a href="{{ route('password.request') }}"
                        class="text-warning">إعادة تعيين كلمة المرور</a></p>
            </div>
            <div class="text-center mt-3">
                <p class="heart mb-0"> <a class="text-danger" href="{{ route('register') }}"> ليس لديك حساب؟ سجل هنا</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    $(document).ready(function () {
        alertify.set('notifier', 'position', 'bottom-left');
        alertify.set('notifier', 'delay', 6);
        @if (session('error'))
            alertify.error("{{ session('error') }}");
        @elseif (session('success'))
            alertify.success("{{ session('success') }}");
        @elseif (session('info'))
            alertify.message("{{ session('info') }}");
        @elseif (session('warning'))
            alertify.warning("{{ session('warning') }}");
        @endif
    });

</script>
@endsection