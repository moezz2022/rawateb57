@extends('layouts.master3')
@section('content')
    <div class="reset-page-container">
        <div class="reset-form-container">
            <div class="animated fadeInDown">
                <div class="text-center mb-4">
                    <div class="logo-container">
                        <a href="{{ route('auth.index') }}">
                            <img src="{{ asset('assets/img/brand/logo57.png') }}" class="img-fluid" alt="logo">
                        </a>
                        <h1 class="logo-text">مديرية التربية لولاية المغير</h1>
                    </div>
                </div>
                
                <div class="form-header-icon">
                    <i class="fas fa-key"></i>
                </div>
                
                <h2 class="form-title text-center mb-4">استرجاع كلمة السر</h2>
                
                @if (session('status'))
                    <div class="alert alert-success mb-4">
                        <i class="fas fa-check-circle ml-2"></i>
                        {{ session('status') }}
                    </div>
                @endif
                
                <p class="text-center text-muted mb-4">أدخل البريد الإلكتروني وسيتم إرسال رابط تعيين كلمة المرور الجديدة.</p>
                
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="form-group mb-4">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <div class="input-with-icon">
                            <div class="input-group">
                                <div class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <input id="email" class="form-control @error('email') is-invalid @enderror" 
                                    name="email" value="{{ old('email') }}" placeholder="أدخل البريد الإلكتروني"
                                    type="email" required autofocus>
                                
                            </div>
                        </div>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary mt1 bg-purple btn-block">
                            <i class="fas fa-paper-plane ml-2"></i>
                            {{ __('إرسال رابط إعادة تعيين كلمة المرور') }}
                        </button>
                    </div>
                </form>
                
                <div class="back-to-login mt-4 text-center">
                     <p class="heart mb-0"><a class="text-danger"  href="{{ route('login') }}">
                        <i class="fas fa-arrow-right ml-1"></i>
                        العودة إلى صفحة تسجيل الدخول
                    </a></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
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
            
            // Focus on email input when page loads
            $('#email').focus();
        });
    </script>
@endsection
