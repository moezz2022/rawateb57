@extends('layouts.master2')
@section('css')
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<style>
    /* Header Section */
    .header_section {
        background: linear-gradient(to right, #6a11cb, #2575fc);
        text-align: center;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
    }

    .header_section h1 {
        font-size: 2.5rem;
        font-weight: 400;
        color: gold;
        animation: slideInFromLeft 1s ease-out;
    }

    @keyframes slideInFromLeft {
        from {
            transform: translateX(-100%);
        }

        to {
            transform: translateX(0);
        }
    }
    .header_section img {
        height: 80px;
    }
</style>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="header_section">
                <a href="{{ route('auth.index') }}"><img src="{{ asset('assets/img/brand/logo57.png') }}"
                        class="sign-favicon ht-40" alt="logo">
                </a>
                <h1>مديرية التربية لولاية المغير</h1>
            </div>
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center mb-4">سحب الاستدعاء</h2>

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('istidea.download') }}" method="POST" target="_blank">
                        @csrf
                        <div class="form-group">
                            <label for="username" class="form-label">اسم المستخدم<span
                                    style="color: red;">*</span></label>
                            <input id="username" type="text" class="form-control" name="username"
                                value="{{ old('username') }}" required autocomplete="username" autofocus>
                        </div>
                        <div class="form-group position-relative">
                            <label for="password">كلمة المرور <span style="color: red;">*</span></label>
                            <div class="input-group position-relative">
                                <input id="password" type="password" class="form-control" name="password" required
                                    autocomplete="current-password">
                                <span class="toggle-password"
                                    onclick="togglePasswordVisibility('password', 'toggleIconCurrent')">
                                    <i class="fa fa-eye" id="toggleIconCurrent"></i>
                                </span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">تحميل الاستدعاء</button>
                    </form>
                </div>
            </div>
        </div>
</div>
@endsection