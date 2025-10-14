@extends('layouts.master3')
@section('content')
<!-- Top Bar with Contact Info and Social Media -->
<div class="container-fluid topbar py-2">
    <div class="container">
        <div class="row gx-0 align-items-center">
            <div class="col-lg-8 text-center text-lg-start mb-2 mb-lg-0">
                <div class="d-flex flex-wrap">
                    <div class="border-end border-primary pe-3">
                        <a href="#" class="text-white mx-2"><i class="fas fa-map-marker-alt text-primary mx-2"></i>موقع
                            المديرية</a>
                    </div>
                    <div class="ps-3">
                        <a href="mailto:deelmeghaier57@gmail.com" class="text-white mx-2"><i
                                class="fas fa-envelope text-primary mx-2"></i>deelmeghaier57@gmail.com</a>
                    </div>
                </div>
            </div>
            <div class="col text-center text-lg-end">
                <div class="d-flex justify-content-end">
                    <div class="d-flex">
                        <a class="btn p-0 text-primary mx-2"
                            href="https://www.facebook.com/profile.php?id=100069996817695&locale=fr_FR"
                            target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn p-0 text-danger mx-2" href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Logo Header -->
<div class="header-container">
    <div class="image-logo">
        <picture>
            <img src="{{ asset('assets/img/media/Slide1.jpg') }}" class="full-width-image" alt="logo">
        </picture>
    </div>
</div>

<!-- News Ticker -->
<div class="container-fluid py-0">
    <div class="news-ticker">
        <div class="ticker-content">
            <a style="color: #ea580c;" href="{{ route('concours.register') }}"><span>انطلاق التسجيلات في مسابقة العمال
                    المهنيين</span></a>
            <a style="color: #ea580c;" href="{{ route('concours.istidea') }}"><span>سحب استدعاء مسابقة العمال
                    المهنيين</span></a>
            <a style="color: #ea580c;" href="#"><span>إعلان نتائج مسابقة التوظيف</span></a>
        </div>
    </div>
</div>

<!-- Main Content Section - Improved Design -->
<div class="container-fluid mt-4">
    <div class="container">
        <div class="main-content-card mt-5">
            <div class="row align-items-center">
                <div class="col-lg-4 text-center fadeInLeft">
                    <div class="image-container">
                        <img src="{{ asset('assets/img/media/image.png') }}" class="img-fluid" alt="logo">
                    </div>
                </div>
                <div class="col-lg-8 text-center text-lg-end fadeInRight">
                    <div>
                        <h2 class="welcome-heading">مرحبا بكم</h2>
                        <h1 class="main-heading">في الفضاء الرقمي لمديرية التربية لولاية المغير</h1>
                        <div class="d-flex justify-content-center justify-content-lg-end">
                            <a class="login-button" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-2"></i> تسجيل الدخول 
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="container-fluid">
    <div class="container">
        <div class="main-footer">
            <div class="container-fluid">
                <h6 class="text-center mt-1 footer-text"> 
                    مديرية التربية لولاية المغير M©B خلية الاعلام الآلي {{ now()->year }}
                </h6>
            </div>
        </div>
    </div>
</div>
@endsection
