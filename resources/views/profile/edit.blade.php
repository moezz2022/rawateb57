@extends('layouts.master')
@section('content')
    <div class="row row-sm">
        <div class="col-lg-12 col-xl-12 col-md-12 mt-5">
            <div class="card mt-3">
                <div class="card-header bg-purple text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-edit mr-2"></i> تعديل البيانات الشخصية
                    </h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="profile-tab-link" data-toggle="tab" data-target="#profile-tab" href="javascript:void(0);"
                                role="tab" aria-controls="profile-tab" aria-selected="true">
                                <i class="fa fa-user"></i> بيانات المستخدم
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="password-tab-link" data-toggle="tab" data-target="#password-tab" href="javascript:void(0);" role="tab"
                                aria-controls="password-tab" aria-selected="false">
                                <i class="fa fa-lock"></i> تعديل كلمة المرور
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="avatar-tab-link" data-toggle="tab" data-target="#avatar-tab" href="javascript:void(0);" role="tab"
                                aria-controls="avatar-tab" aria-selected="false">
                                <i class="fa fa-image"></i> تغيير الصورة الشخصية
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content mt-4">
                        <div class="tab-pane fade show active" id="profile-tab" role="tabpanel"
                            aria-labelledby="profile-tab-link">
                            @include('profile.updateprofile')
                        </div>
                        <div class="tab-pane fade" id="password-tab" role="tabpanel" aria-labelledby="password-tab-link">
                            @include('profile.updatepassword')
                        </div>
                        <div class="tab-pane fade" id="avatar-tab" role="tabpanel" aria-labelledby="avatar-tab-link">
                            @include('profile.updateavatar')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function showAlert(message, type) {
            Swal.fire({
                icon: type,
                title: type === 'success' ? 'نجاح!' : 'خطأ!',
                text: message,
                confirmButtonText: 'موافق',
                confirmButtonColor: '#8b5cf6'
            });
        }
        
        @if (session('status'))
            showAlert("{{ session('status') }}", 'success');
        @endif
        
        @if ($errors->any())
            showAlert("{{ implode('، ', $errors->all()) }}", 'error');
        @endif
    </script>
@endsection
