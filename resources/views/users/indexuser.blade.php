@php
    $isPrimaire = strlen($currentAffect) > 6;
@endphp
@extends('layouts.master')
@section('css')
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المستخدمين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة
                    الموظفين</span>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mg-b-0"><i class="fa fa-users icon-text-spacing">
                            </i>قائمة الموظفين</h4>
                        <a href="{{ route('users.transfer') }}" type="button"
                            class="btn strong btn-success btn-lg btn-fill">
                            <i class="fas fa-user-plus"></i> طلب موظف
                        </a>
                    </div>
                </div>
                @if ($isPrimaire)
                    <div class="alert alert-info shadow-sm border-0 mt-2">
                        <h4 class="fw-bold mb-2">
                            <i class="fa-solid fa-circle-info"></i> ملاحظة خاصة بالابتدائيات:
                        </h4>
                        <h5>تُعرض في هذه الصفحة جميع الموظفين التابعين للمؤسسة الأم (المتوسطة). ويمكن للمدير القيام بما يلي:
                        </h5>
                        <ul class="mb-0" style="line-height: 1.8;">
                            <li>استخدام زر <strong>إسناد الموظف</strong> لربط الموظف بالمؤسسة الابتدائية.</li>
                            <li>استخدام زر <strong>إلغاء الانتماء</strong> لإلغاء انتماء الموظف بالمؤسسة الابتدائية.</li>
                        </ul>
                    </div>
                @endif
                <div class="filter-buttons mt-3 d-flex flex-wrap gap-2">
                    <button class="btn {{ $currentAdm == '' ? 'btn-dark' : 'btn-outline-dark' }} adm-filter-btn"
                        data-adm="">
                        الكل
                        <span class="badge bg-danger text-white ms-1">{{ $users->count() }}</span>
                    </button>

                    @foreach ($departments as $department)
                        <button
                            class="btn {{ $currentAdm == $department->ADM ? 'btn-dark' : 'btn-outline-dark' }} adm-filter-btn"
                            data-adm="{{ $department->ADM }}">
                            {{ $department->name }}
                            <span class="badge bg-danger text-white ms-1">{{ $department->users_count }}</span>
                        </button>
                    @endforeach
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-md-nowrap" id="usersTable">
                            <thead>
                                <tr>
                                    <th class="wd-5p border-bottom-0">#</th>
                                    <th class="wd-15p border-bottom-0">اللقب والاسم</th>
                                    <th class="wd-10p border-bottom-0">تاريخ الميلاد</th>
                                    <th class="wd-20 border-bottom-0">الرتبة</th>
                                    <th class="wd-45p border-bottom-0">العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $index => $user)
                                    <tr data-adm="{{ $user->ADM }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $user->NOMA . ' ' . $user->PRENOMA }}</td>
                                        <td>{{ $user->DATNAIS }}</td>
                                        <td>
                                            @foreach ($grades as $grade)
                                                @if ($user->CODFONC == $grade->codtab)
                                                    {{ $grade->name }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            {{-- زر تعديل --}}
                                            <a class="btn btn-sm btn-warning"
                                                href="{{ route('employees.edit', $user->id) }}">
                                                <i class="fas fa-edit ml-1"></i>تعديل
                                            </a>

                                            {{-- لو الحساب ابتدائية: أزرار إسناد / إلغاء --}}
                                            @if ($isPrimaire)
                                                @if ($user->PRIMAIRE === $currentAffect)
                                                    <form method="POST"
                                                        action="{{ route('employees.unassign', $user->id) }}"
                                                        class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-times"></i> إلغاء الانتماء
                                                        </button>
                                                    </form>
                                                @else
                                                    <form method="POST"
                                                        action="{{ route('employees.assign', $user->id) }}"
                                                        class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="primaire_affect"
                                                            value="{{ $currentAffect }}">
                                                        <button type="submit" class="btn btn-success btn-sm">
                                                            <i class="fas fa-plus"></i> إسناد
                                                        </button>
                                                    </form>
                                                @endif
                                            @else
                                                {{-- لو الحساب متوسطة: عرض الانتماء الحالي فقط --}}
                                                @if ($user->PRIMAIRE)
                                                    <span class="badge bg-info text-white">
                                                        منتمي إلى {{ $user->primaireGroup?->name ?? $user->PRIMAIRE }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">غير منتمي</span>
                                                @endif
                                            @endif
                                        </td>

                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">لا يوجد الموظفين </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="alert-container" class="alertify-notifier ajs-bottom ajs-right">
                </div>
            </div>
        </div>
        @include('users.transferModal')
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            alertify.set('notifier', 'position', 'bottom-left');
            alertify.set('notifier', 'delay', 3);
            @if (session('success'))
                alertify.success("{{ session('success') }}");
            @endif
            @if (session('error'))
                alertify.error("{{ session('error') }}");
            @endif
            @if (session('status'))
                alertify.error("{{ session('status') }}");
            @endif
        });
    </script>
@endsection
