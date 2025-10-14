@extends('layouts.master')
@section('css')
    <!--- Internal Select2 css-->
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المستخدمين</h4>
                <h4 class="text-muted mt-1 tx-13 mr-2 mb-0">/ تحديث معلومات الحساب</h4>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <div class="col-md-12 col-xl-12 col-xs-12 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-edit"></i> تحديث معلومات الحساب</h3>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success mb-4">
                        <i class="fas fa-check-circle ml-2"></i> {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <h4 class="form-section-title"><i class="fas fa-id-card"></i> المعلومات الشخصية</h4>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label for="name">الإسم و اللقب</label>
                                <input class="form-control input-with-icon @error('name') is-invalid @enderror"
                                    id="name" type="text" name="name" placeholder="الإسم و اللقب بالعربية"
                                    value="{{ old('name', $user->name) }}">
                                <i class="fas fa-user input-icon"></i>
                                @error('name')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label for="phone">رقم الهاتف</label>
                                <input class="form-control input-with-icon @error('phone') is-invalid @enderror"
                                    id="phone" type="text" name="phone" placeholder="رقم الهاتف"
                                    value="{{ old('phone', $user->phone) }}">
                                <i class="fas fa-phone input-icon"></i>
                                @error('phone')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <h4 class="form-section-title"><i class="fas fa-user-shield"></i> معلومات الحساب</h4>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label for="email">البريد الإلكتروني (e-mail)</label>
                                <input class="form-control input-with-icon @error('email') is-invalid @enderror"
                                    id="email" type="email" name="email" placeholder="البريد الإلكتروني"
                                    value="{{ old('email', $user->email) }}">
                                <i class="fas fa-envelope input-icon"></i>
                                @error('email')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label for="username">اسم المستخدم</label>
                                <input class="form-control input-with-icon @error('username') is-invalid @enderror"
                                    id="username" type="text" name="username" placeholder="اسم المستخدم"
                                    value="{{ old('username', $user->username) }}">
                                <i class="fas fa-id-badge input-icon"></i>
                                @error('username')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <h4 class="form-section-title"><i class="fas fa-building"></i> المؤسسة والإدارة</h4>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="main_group">اختيار الإدارة أو الهيئة</label>
                                <select id="main_group" name="main_group"
                                    class="form-control select2 @error('main_group') is-invalid @enderror">
                                    <option value="">يرجى اختيار الإدارة أو الهيئة</option>
                                    @foreach ($mainGroups as $mainGroup)
                                        <option value="{{ $mainGroup->id }}"
                                            {{ $user->main_group == $mainGroup->id ? 'selected' : '' }}>
                                            {{ $mainGroup->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('main_group')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sub_group">اختيار المؤسسة</label>
                                <select id="sub_group" name="sub_group"
                                    class="form-control select2 @error('sub_group') is-invalid @enderror">
                                    <option value="">--الرجاء الاختيار--</option>
                                    @foreach ($subGroups as $subGroup)
                                        <option value="{{ $subGroup->id }}"
                                            {{ $user->sub_group == $subGroup->id ? 'selected' : '' }}>
                                            {{ $subGroup->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('sub_group')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <h4 class="form-section-title"><i class="fas fa-lock"></i> كلمة المرور</h4>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <label for="new_password" class="form-label font-weight-bold">كلمة المرور الجديدة</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-lock text-purple"></i>
                                        </span>
                                    </div>
                                    <input id="new_password" type="password" class="form-control" name="new_password">
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-light cursor-pointer"
                                            onclick="togglePasswordVisibility('new_password', 'toggleIconNew')">
                                            <i class="fa fa-eye" id="toggleIconNew"></i>
                                        </span>
                                    </div>
                                </div>
                                @error('new_password')
                                    <span class="text-danger d-block mt-1">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mt-2">
                                <div class="progress" style="height: 8px;">
                                    <div id="passwordStrengthBar" class="progress-bar" role="progressbar"
                                        style="width: 0%; transition: width 0.3s ease;" aria-valuenow="0"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span id="passwordStrengthText" class="small text-muted d-block mt-1"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-4 text-left">
                        <button type="submit" class="btn btn-purple btn-lg">
                            <i class="fas fa-save ml-1"></i> تحديث المعلومات
                        </button>
                        <a href="{{ route('users.activeuser.index') }}" class="btn btn-secondary btn-lg mr-2">
                            <i class="fas fa-times ml-1"></i> إلغاء
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Internal Select2 js-->
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
@endsection
