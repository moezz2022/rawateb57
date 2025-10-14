<!-- main-header opened -->
<div class="main-header sticky side-header  nav nav-item">
    <div class="container-fluid">
        <div class="main-header-left ">
            <div class="responsive-logo">
                <a href="{{ url('/' . ($page = 'dashboard')) }}"><img src="{{ asset('assets/img/brand/logo57.png') }}"
                        class="logo-1" alt="logo"></a>
            </div>
            <div class="app-sidebar__toggle" data-toggle="sidebar">
                <a class="open-toggle" href="#"><i class="header-icon fe fe-align-left"></i></a>
                <a class="close-toggle" href="#"><i class="header-icons fe fe-x"></i></a>
            </div>
            <div class="main-header-center mr-3 mt-2 d-lg-block">
                <h1>مديرية التربية لولاية المغير</h1>
            </div>
        </div>
        <div class="main-header-right">
            <div class="nav nav-item  navbar-nav-right ml-auto">
                <div class="dropdown main-profile-menu nav nav-item nav-link">
                    <a class="profile-user d-flex" href="">
                        <img alt="user-img" class="avatar avatar-xl brround"
                            src="{{ asset('storage/' . auth()->user()->avatar) }}">
                    </a>
                    <div class="dropdown-menu">
                        <div class="main-header-profile bg-purple p-3">
                            <div class="d-flex wd-100p">
                                <div class="main-img-user"><img alt="user-img" class="avatar avatar-xl brround"
                                        src="{{ asset('storage/' . auth()->user()->avatar) }}">
                                </div>
                                <div class="mr-3 my-auto">
                                    <h6>{{ auth()->user()->name }}</h6>
                                    <span>{{ auth()->user()->subGroup ? auth()->user()->subGroup->name : 'لا توجد مجموعة فرعية' }}</span>
                                </div>
                            </div>
                        </div>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fa fa-user-cog"></i> تعديل الملف الشخصي</a>
                        <a class="dropdown-item" href="{{ route('auth.twofactorchallenge') }}"> <i class="fas fa-shield-alt"></i> المصادقة الثنائية</a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> <i
                                class="bx bx-log-out"></i>تسجيل الخروج </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /main-header -->
