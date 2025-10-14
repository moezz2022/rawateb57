@extends('layouts.master3')
@section('css')
@endsection
@section('content')
    <div class="page-login">
        <div class="login-container card">
            <div class="card-body animated fadeInDown">
                <div class="header_section text-center mb-2">
                    <a href="{{ route('auth.index') }}"><img src="{{ asset('assets/img/brand/logo57.png') }}"
                            class="sign-favicon ht-40" alt="logo">
                    </a>
                    <h1 class="main-logo1">مديرية التربية لولاية المغير</h1>
                </div>
                <h1 class="text-center">التسجــيل</h1>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">الإسم و اللقب</label>
                        <input class="form-control @error('name') is-invalid @enderror" id="name" type="text"
                            name="name" placeholder="الإسم و اللقب بالعربية" value="{{ old('name') }}">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            @enderror
                    </div>
                    <div class="form-group">
                        <label for="phone">رقم الهاتف</label>
                        <input class="form-control @error('phone') is-invalid @enderror" id="phone" type="text"
                            name="phone" placeholder="  رقم الهاتف" value="{{ old('phone') }}">
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            @enderror
                    </div>
                    <div class="form-group">
                        <label for="email"> البريد الإلكتروني</label>
                        <input class="form-control @error('email') is-invalid @enderror" id="email" type="email"
                            name="email" placeholder="البريد الإلكتروني" value="{{ old('email') }}">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            @enderror
                    </div>
                    <div class="form-group">
                        <label for="username"> اسم المستخدم</label>
                        <input class="form-control @error('username') is-invalid @enderror" id="username" type="text"
                            name="username" placeholder="اسم المستخدم مثل mohamed@88" value="{{ old('username') }}">
                        @error('username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            @enderror
                    </div>
                    <div class="form-group">
                        <label for="user_type">نوع المستخدم</label>
                        <select id="user_type" name="user_type"
                            class="form-control @error('user_type') is-invalid @enderror">
                            <option value="">يرجى اختيار..</option>
                            <option value="admin" {{ old('user_type') == 'admin' ? 'selected' : '' }}>مشرف عام</option>
                            <option value="office_head" {{ old('user_type') == 'office_head' ? 'selected' : '' }}>رئيس
                                مصلحة / مكتب</option>
                            <option value="director" {{ old('user_type') == 'director' ? 'selected' : '' }}>مدير مؤسسة
                            </option>
                            <option value="manager" {{ old('user_type') == 'manager' ? 'selected' : '' }}>المسير المالي
                            </option>
                            <option value="inspector" {{ old('user_type') == 'inspector' ? 'selected' : '' }}>مفتش</option>
                        </select>
                        @error('user_type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div id="institution_fields">
                        <div class="form-group">
                            <label for="main_group"> طبيعة المؤسسة أوالإدارة أو الهيئة</label>
                            <select id="main_group" name="main_group"
                                class="form-control @error('main_group') is-invalid @enderror">
                                <option value="">يرجى اختيار..</option>
                                @foreach ($mainGroups as $group)
                                    <option value="{{ $group->id }}"
                                        {{ old('main_group') == $group->id ? 'selected' : '' }}>
                                        {{ $group->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('main_group')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                @enderror
                        </div>
                        <div class="form-group">
                            <label for="sub_group"> اسم المؤسسة أوالإدارة أو الهيئة</label>
                            <select id="sub_group" name="sub_group"
                                class="form-control @error('sub_group') is-invalid @enderror">
                                <option value="">يرجى اختيار..</option>
                            </select>
                            @error('sub_group')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12 position-relative">
                            <label for="password">كلمة المرور</label>
                            <div class="input-group position-relative">
                                <input class="form-control @error('password') is-invalid @enderror" id="password"
                                    type="password" name="password" placeholder="كلمة المرور">
                                <span class="toggle-password"
                                    onclick="togglePasswordVisibility('password', 'toggleIconCurrent')">
                                    <i class="fa fa-eye" id="toggleIconCurrent"></i>
                                </span>
                            </div>
                            @error('password')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                @enderror
                        </div>
                        <div class="form-group col-12 position-relative">
                            <label for="password_confirmation">تأكيد كلمة المرور</label>
                            <div class="input-group position-relative">
                                <input class="form-control @error('password_confirmation') is-invalid @enderror"
                                    id="password_confirmation" type="password" name="password_confirmation"
                                    placeholder="تأكيد كلمة المرور">
                                <span class="toggle-password"
                                    onclick="togglePasswordVisibility('password_confirmation', 'toggleIconCurrent')">
                                    <i class="fa fa-eye" id="toggleIconCurrent"></i>
                                </span>
                            </div>
                            @error('password_confirmation')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                @enderror
                        </div>
                    </div>
                    <button class="btn btn-primary mt1 bg-purple btn-block" type="submit">تسجيل</button>
                </form>
                <div class="text-center mt-3">
                    <p class="mb-0"> لديك حساب ؟  <p class="heart mb-0"><a href="{{ route('login') }}" class="text-danger">قم بالتسجيل
                            الدخول</a></p>
                    </p>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

