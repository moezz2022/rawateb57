@extends('layouts.master2')
@section('css')
    <!-- Sidemenu-respoansive-tabs css -->
    <link href="{{ asset('assets/plugins/sidemenu-responsive-tabs/css/sidemenu-responsive-tabs.css') }}"
        rel="stylesheet">
@endsection
@section('content')
    <div class="reset-page-container">
        <div class="reset-form-container">
            <div class="logo-container">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('assets/img/brand/11.png') }}" alt="logo">
                </a>
                <h1 class="logo-text">مديرية التربية لولاية المغير</h1>
            </div>
            
            <div class="form-header-icon">
                <i class="fas fa-key"></i>
            </div>
            
            <h2 class="form-title">استعادة كلمة المرور</h2>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                
                <div class="form-group mb-4">
                    <label class="form-label">البريد الإلكتروني</label>
                    <div class="input-group input-with-icon">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-envelope text-purple"></i>
                            </span>
                        </div>
                        <input class="form-control @error('email') is-invalid @enderror" 
                            name="email" type="email" 
                            value="{{ old('email', $request->email) }}" 
                            placeholder="ادخل البريد الإلكتروني" required autofocus>
                    </div>
                    @error('email')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group mb-4">
                    <label class="form-label">كلمة المرور الجديدة</label>
                    <div class="input-group input-with-icon">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-lock text-purple"></i>
                            </span>
                        </div>
                        <input id="password" class="form-control @error('password') is-invalid @enderror" 
                            name="password" type="password" 
                            placeholder="ادخل كلمة المرور الجديدة" required>
                        <div class="input-group-append">
                            <span class="input-group-text password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="password-toggle-icon"></i>
                            </span>
                        </div>
                    </div>
                    @error('password')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group mb-4">
                    <label class="form-label">تأكيد كلمة المرور</label>
                    <div class="input-group input-with-icon">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="fas fa-check-circle text-purple"></i>
                            </span>
                        </div>
                        <input id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" 
                            name="password_confirmation" type="password" 
                            placeholder="أكد كلمة المرور الجديدة" required>
                        <div class="input-group-append">
                            <span class="input-group-text password-toggle" onclick="togglePassword('password_confirmation')">
                                <i class="fas fa-eye" id="password-confirmation-toggle-icon"></i>
                            </span>
                        </div>
                    </div>
                    @error('password_confirmation')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="password-requirements">
                    <h6 class="mb-2"><i class="fas fa-shield-alt"></i> متطلبات كلمة المرور:</h6>
                    <div class="requirement-item">
                        <i class="fas fa-circle"></i>
                        يجب أن تحتوي على 8 أحرف على الأقل
                    </div>
                    <div class="requirement-item">
                        <i class="fas fa-circle"></i>
                        يجب أن تحتوي على حرف كبير واحد على الأقل
                    </div>
                    <div class="requirement-item">
                        <i class="fas fa-circle"></i>
                        يجب أن تحتوي على رقم واحد على الأقل
                    </div>
                    <div class="requirement-item">
                        <i class="fas fa-circle"></i>
                        يجب أن تحتوي على رمز خاص واحد على الأقل
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary mt1 bg-purple btn-block">
                    <i class="fas fa-key mr-2"></i> استعادة كلمة المرور
                </button>
            </form>
            
            <div class="back-to-login">
                <a href="{{ route('login') }}">
                    <i class="fas fa-arrow-right ml-1"></i> العودة إلى صفحة تسجيل الدخول
                </a>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    function togglePassword(inputId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(inputId === 'password' ? 'password-toggle-icon' : 'password-confirmation-toggle-icon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>
@endsection
