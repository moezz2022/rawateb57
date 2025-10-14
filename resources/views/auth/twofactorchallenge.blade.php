@extends('layouts.master')
@section('css')
<style> 
    .setup-container {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }
    
    @media (min-width: 992px) {
        .setup-container {
            flex-direction: row;
        }
        
        .qr-section {
            flex: 1;
            border-right: 1px solid #e2e8f0;
            padding-right: 30px;
        }
        
        .form-section {
            flex: 1;
            padding-left: 30px;
        }
    }
    
    .step-container {
        margin-bottom: 25px;
    }
    
    .step-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        background-color: #8b5cf6;
        color: white;
        border-radius: 50%;
        font-weight: bold;
        margin-right: 10px;
    }
    
    .step-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 10px;
    }
    
    .step-content {
        margin-left: 40px;
        color: #475569;
    }
    
    .qrcode-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px;
        border: 2px dashed #e2e8f0;
        border-radius: 10px;
        margin-top: 20px;
        background-color: #f8fafc;
    }
    
    #qrcode {
        padding: 15px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }
    
    .secret-key {
        margin-top: 15px;
        font-family: monospace;
        font-size: 1.1rem;
        background-color: #f1f5f9;
        padding: 10px 15px;
        border-radius: 5px;
        border: 1px solid #e2e8f0;
        word-break: break-all;
        text-align: center;
    }
    
    .form-control {
        height: 50px;
        border-radius: 8px;
        padding: 10px 15px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        font-size: 1.2rem;
        letter-spacing: 2px;
        text-align: center;
    }
    
    .form-control:focus {
        border-color: #8b5cf6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.25);
    }
    
    
    
    .app-badge {
        display: inline-block;
        margin: 5px;
        padding: 8px 15px;
        background-color: #f1f5f9;
        border-radius: 20px;
        font-size: 0.9rem;
        color: #475569;
        transition: all 0.3s ease;
    }
    
    .app-badge:hover {
        background-color: #e2e8f0;
        transform: translateY(-2px);
    }
    
    .status-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 1.25rem !important; 
        font-weight: 600 !important;
    }
    
    .status-enabled {
        background-color: #dcfce7;
        color: #166534;
    }
    
    .status-disabled {
        background-color: #fee2e2;
        color: #991b1b;
    }
</style>
@endsection
@section('content')
        <div class="row row-sm">
            <div class="col-lg-12 col-xl-12 col-md-12 mt-5">
                <div class="card mt-4">
                    <div class="card-header bg-purple text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-shield-alt mr-2"></i> تفعيل المصادقة الثنائية
                            <span class="status-badge float-left {{ $user->two_factor_enabled ? 'status-enabled' : 'status-disabled' }}">
                                {{ $user->two_factor_enabled ? 'مفعل' : 'غير مفعل' }}
                            </span>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-secondary">
                            <strong>تعزيز أمان حسابك:</strong> المصادقة الثنائية توفر طبقة إضافية من الحماية لحسابك. بعد تفعيلها، ستحتاج إلى إدخال رمز من تطبيق المصادقة بالإضافة إلى كلمة المرور عند تسجيل الدخول.
                        </div>
                        
                        <div class="setup-container">
                            <div class="qr-section">
                                <div class="step-container">
                                    <span class="step-number">1</span>
                                    <span class="step-title">قم بتنزيل تطبيق المصادقة</span>
                                    <div class="step-content">
                                        <p>قم بتنزيل وتثبيت أحد تطبيقات المصادقة التالية على هاتفك:</p>
                                        <div>
                                            <span class="app-badge"><i class="fab fa-google mr-1"></i> Google Authenticator</span>
                                            <span class="app-badge"><i class="fas fa-mobile-alt mr-1"></i> Authy</span>
                                            <span class="app-badge"><i class="fas fa-key mr-1"></i> FreeOTP</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="step-container">
                                    <span class="step-number">2</span>
                                    <span class="step-title">امسح رمز QR</span>
                                    <div class="step-content">
                                        <p>افتح تطبيق المصادقة وامسح رمز QR التالي:</p>
                                        <div class="qrcode-container">
                                            <div id="qrcode"></div>
                                            <p class="mt-3 mb-1">أو أدخل المفتاح السري يدويًا:</p>
                                            <div class="secret-key">{{ $secret }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <div class="step-container">
                                    <span class="step-number">3</span>
                                    <span class="step-title">أدخل رمز التحقق</span>
                                    <div class="step-content">
                                        <p>أدخل الرمز المكون من 6 أرقام الذي يظهر في تطبيق المصادقة:</p>
                                        
                                        <form action="{{ $user->two_factor_enabled ? route('two-factor.disable') : route('two-factor.enable') }}" method="post" class="needs-validation" novalidate>
                                            @csrf
                                            <div class="mb-3">
                                                <input type="text" id="otp" name="otp" class="form-control @error('otp') is-invalid @enderror"
                                                    placeholder="● ● ● ● ● ●" maxlength="6" autocomplete="off" required>
                                                @error('otp')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            
                                            <div class="alert alert-danger">
                                                <i class="fas fa-exclamation-triangle mr-2"></i> ملاحظة: بعد إلغاء تفعيل ثم تفعيل الخدمة سيتم إنشاء رمز جديد.
                                            </div>
                                            
                                            <button type="submit" class="btn btn-lg btn-fill float-left {{ $user->two_factor_enabled ? 'btn-danger' : 'btn-purple ' }}">
                                                <i class="fas {{ $user->two_factor_enabled ? 'fa-times-circle' : 'fa-check-circle' }} mr-2"></i>
                                                {{ $user->two_factor_enabled ? 'تعطيل' : 'تفعيل' }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            // تركيز تلقائي على حقل الإدخال
            setTimeout(function() {
                $('#otp').focus();
            }, 500);
            
            // تنسيق إدخال OTP
            $('#otp').on('input', function() {
                $(this).val($(this).val().replace(/[^0-9]/g, ''));
            });
            
            alertify.set('notifier', 'position', 'bottom-left');
            alertify.set('notifier', 'delay', 3);
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

        new QRCode(document.getElementById("qrcode"), {
            text: "otpauth://totp/{{ rawurlencode('YourApp:' . $username) }}?secret={{ $secret }}&issuer={{ rawurlencode('YourAppName') }}",
            width: 150,
            height: 150,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    </script>
@endsection
