@extends('layouts.master3')
@section('content')
    <div class="costom-page">
        <div class="modal fade" id="twoFactorModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
            aria-labelledby="twoFactorModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg custom2FA-modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-white" id="twoFactorModalLabel">
                            <i class="fas fa-shield-alt mr-2"></i> المصادقة الثنائية
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center mb-4">
                            <div class="d-inline-block rounded-circle bg-light p-3 pulse-animation">
                                <i class="fas fa-lock text-purple fa-3x"></i>
                            </div>
                            <h4 class="mt-3">التحقق من هويتك</h4>
                            <p class="text-muted">لحماية حسابك، نحتاج إلى التأكد من أنك أنت بالفعل</p>
                        </div>                        
                        <form method="POST" action="{{ route('auth.twofactor-challenge.verify') }}">
                            @csrf
                            <div class="mb-3">
                                <h5>رمز التحقق</h5>
                                <label for="otp" class="form-label">أدخل رمز التحقق من تطبيق الأمان الخاص بك:</label>
                                <div class="otp-container">
                                    <input type="text" name="otp" id="otp"
                                        class="form-control @error('otp') is-invalid @enderror" 
                                        placeholder="● ● ● ● ● ●" maxlength="6" autocomplete="off">
                                    <div class="otp-icon">
                                        <i class="fas fa-key"></i>
                                    </div>
                                </div>
                                @error('otp')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror                                
                                <div class="timer-container">
                                    <span>سينتهي الرمز خلال: </span>
                                    <span class="timer-text" id="countdown">30</span>
                                    <span> ثانية</span>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-check-circle mr-2"></i> تحقق
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $('#twoFactorModal').modal('show');
                setTimeout(function() {
                $('#otp').focus();
            }, 500);
            
            let timeLeft = 60;
            let countdownTimer = setInterval(function() {
                timeLeft--;
                $('#countdown').text(timeLeft);
                if (timeLeft <= 0) {
                    clearInterval(countdownTimer);
                    $('#countdown').text('0');
                    $('#resendCode').removeClass('text-muted').addClass('text-purple');
                }
            }, 1000); 
            $('#resendCode').on('click', function(e) {
                e.preventDefault();
                if (timeLeft <= 0) {
                    alertify.success('تم إعادة إرسال الرمز بنجاح');
                    timeLeft = 30;
                    $('#countdown').text(timeLeft);
                    countdownTimer = setInterval(function() {
                        timeLeft--;
                        $('#countdown').text(timeLeft);
                        if (timeLeft <= 0) {
                            clearInterval(countdownTimer);
                            $('#countdown').text('0');
                            $('#resendCode').removeClass('text-muted').addClass('text-purple');
                        }
                    }, 1000);
                    $('#resendCode').addClass('text-muted').removeClass('text-purple');
                }
            });
                $('#otp').on('input', function() {
                $(this).val($(this).val().replace(/[^0-9]/g, ''));
            }); 
            $('#twoFactorModal').on('hidden.bs.modal', function() {
                $.ajax({
                    url: '/logout-twofactor', 
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log(response.message);
                        window.location.href = '/';
                    },
                    error: function(xhr) {
                        alertify.error('حدث خطأ أثناء حذف الجلسة.');
                        console.error('فشل في حذف الجلسة:', xhr.responseText);
                    }
                });
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
    </script>
@endsection
