<!-- main-sidebar -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidebar-header active">
        <div class="responsive-logo">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/img/brand/logo57.png') }}" class="logo-1" alt="logo">
            </a>
        </div>
        <div class="text-baridi">
            <h1>بــريـــدي</h1>
        </div>
    </div>

    <div class="main-sidemenu d-flex flex-column" style="height: 100%;">
        <!-- معلومات المستخدم -->
        <div class="app-sidebar__user clearfix">
            <div class="dropdown user-pro-body">
                <div>
                    <img alt="user-img" class="avatar avatar-xl brround"
                        src="{{ asset(
                            auth()->check()
                                ? (auth()->user()->avatar
                                    ? 'storage/' . auth()->user()->avatar
                                    : 'storage/default-avatar.png')
                                : 'storage/default-avatar.png',
                        ) }}">
                    <span class="avatar-status profile-status bg-green"></span>
                </div>
                @auth
                    <div class="user-info">
                        <h4 class="text-white mt-3 mb-0">{{ auth()->user()->name }}</h4>
                        <span>{{ auth()->user()->subGroup ? auth()->user()->subGroup->name : 'لا توجد مجموعة فرعية' }}</span>
                    </div>
                @endauth
            </div>
        </div>

        <ul class="side-menu">
            <!-- الرئيسية -->
            <li class="slide">
                <a class="side-menu__item" href="{{ route('dashboard') }}">
                    <i class="fa-solid fa-home side-menu__icon"></i>
                    <span class="side-menu__label">الرئيسية</span>
                </a>
            </li>

            <!-- قسم الرسائل -->
            @if (auth()->check() && in_array(auth()->user()->role, ['admin', 'director', 'manager', 'office_head', 'inspector']))
                <li class="slide">
                    <a class="side-menu__item" href="{{ route('messages.create') }}">
                        <i class="fa-solid fa-envelope-open-text side-menu__icon"></i>
                        <span class="side-menu__label">رسالة جديدة</span>
                    </a>
                </li>
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="#">
                        <i class="fa-solid fa-envelope side-menu__icon"></i>
                        <span class="side-menu__label">المراسلات</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>
                    <ul class="slide-menu">
                        <li><a class="slide-item" href="{{ route('messages.inbox') }}"><i class="fa-solid fa-inbox"></i>
                                البريد الوارد</a></li>
                        <li><a class="slide-item" href="{{ route('messages.outbox') }}"><i
                                    class="fa-solid fa-paper-plane"></i> البريد الصادر</a></li>
                        <li><a class="slide-item" href="{{ route('messages.saved') }}"><i class="fa-solid fa-save"></i>
                                الرسائل المحفوظة</a></li>
                        <li><a class="slide-item" href="{{ route('messages.trash') }}"><i
                                    class="fa-solid fa-trash"></i>
                                الرسائل المحذوفة</a></li>
                    </ul>
                </li>
            @endif

            <!-- قسم المستخدمين -->
            @if (auth()->check() && in_array(auth()->user()->role, ['admin', 'director', 'manager']))
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="#">
                        <i class="fa-solid fa-users side-menu__icon"></i>
                        <span class="side-menu__label">المستخدمين</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>
                    <ul class="slide-menu">
                        @if (auth()->user()->role === 'admin')
                            <li><a class="slide-item" href="{{ route('users.activeuser.index') }}"><i
                                        class="fa-solid fa-key"></i> تفعيل الحسابات</a></li>
                            <li><a class="slide-item" href="{{ route('groups.index') }}"><i
                                        class="fa-solid fa-building"></i>
                                    قائمة المؤسسات</a></li>
                            <li><a class="slide-item" href="{{ route('employees.index') }}"><i
                                        class="fa-solid fa-users"></i>
                                    قائمة الموظفين</a></li>
                            <li><a class="slide-item" href="{{ route('attendance.records') }}"><i
                                        class="fa-solid fa-users"></i>
                                    حضور وانصراف</a></li>
                            <li><a class="slide-item" href="{{ route('users.transfer') }}"><i
                                        class="fa-solid fa-exchange-alt"></i> التحويلات</a></li>
                        @endif
                        @if (in_array(auth()->user()->role, ['director', 'manager']))
                            <li><a class="slide-item" href="{{ route('users.indexuser') }}"><i
                                        class="fa-solid fa-users-gear"></i> قائمة الموظفين</a></li>
                            <li><a class="slide-item" href="{{ route('prime_rendements.rndmsettings') }}"><i
                                        class="fa-solid fa-pen-to-square"></i> حجز المردودية</a></li>
                            <li><a class="slide-item" href="{{ route('monthly_absences.settings') }}"><i
                                        class="fa-solid fa-pen-nib"></i> الغيابات الشهرية</a></li>
                            <li><a class="slide-item" href="{{ route('prime_scolarité.settings') }}"><i
                                        class="fa-solid fa-pen-nib"></i> منحة التمدرس</a></li>
                        @endif
                    </ul>
                </li>
            @endif
            <!-- قسم المسابقات -->
            @if (auth()->check() && (auth()->user()->role === 'admin' || in_array(auth()->user()->sub_group, [8, 10])))
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="#">
                        <i class="fa-solid fa-gear side-menu__icon"></i>
                        <span class="side-menu__label">المسابقات</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>
                    <ul class="slide-menu">
                        <li>
                            <a class="slide-item" href="{{ route('concours.trait') }}">
                               <i class="fa-solid fa-users-gear"></i> قائمة المترشحين
                            </a>
                        </li>
                         <li>
                            <a class="slide-item" href="{{ route('concours.stats') }}">
                                <i class="fa-solid fa-chart-line"></i> إحصائيات
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            <!-- قسم إدارة الأجور (Admin فقط) -->
            @if (auth()->check() && auth()->user()->role === 'admin')
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="#">
                        <i class="fa-solid fa-cubes side-menu__icon"></i>
                        <span class="side-menu__label">إدارة الأجور</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>
                    <ul class="slide-menu">
                        <li><a class="slide-item" href="{{ route('paie.index') }}"><i
                                    class="fa-solid fas fa-upload"></i> تحميل الراتب</a></li>
                        <li><a class="slide-item" href="{{ route('prime_rendements.settings.months') }}"><i
                                    class="fa-solid fa-cog"></i> إعدادات المردودية</a></li>
                        <li><a class="slide-item" href="{{ route('monthly_absences.months') }}"><i
                                    class="fa-solid fa-cog"></i> إعدادات الغيابات</a></li>
                        <li><a class="slide-item" href="{{ route('prime_scolarité.primesettings') }}"><i
                                    class="fa-solid fa-cog"></i> إعدادات منحة التمدرس</a></li>
                    </ul>
                </li>
            @endif

            <!-- قسم الأجور -->
            @if (auth()->check() && in_array(auth()->user()->role, ['admin', 'director', 'manager']))
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="#">
                        <i class="fa-solid fa-money-bill side-menu__icon"></i>
                        <span class="side-menu__label">الأجور</span>
                        <i class="angle fe fe-chevron-down"></i>
                    </a>
                    <ul class="slide-menu">
                        <!-- روابط مشتركة -->
                        <li><a class="slide-item" href="{{ route('paie.show') }}"><i
                                    class="fa-solid fa-money-bill"></i> الكشوف</a></li>
                        <li><a class="slide-item" href="{{ route('paie.salaryannualshow') }}"><i
                                    class="fa-solid fa-file-invoice"></i> الكشف السنوي</a></li>
                        <li><a class="slide-item" href="{{ route('paie.salaryreport') }}"><i
                                    class="fa-solid fa-line-chart"></i> التقرير المفصل</a></li>
                        @if (auth()->user()->group && empty(auth()->user()->group->PRIMAIRE))
                            <li>
                                <a class="slide-item" href="{{ route('ats.settings') }}">
                                    <i class="fa-solid fa-file-contract"></i> شهادة العمل والأجر
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        </ul>
    </div>
    <!-- زر تسجيل الخروج  -->
    <div class="logout-btn">
        <li class="slide">
            <a class="side-menu__item" href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-power-off side-menu__icon"></i>
                <span class="side-menu__label">تسجيل الخروج</span>
            </a>
        </li>
    </div>
</aside>
<!-- /main-sidebar -->
