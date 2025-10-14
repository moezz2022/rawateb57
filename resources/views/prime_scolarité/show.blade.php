@extends('layouts.master')
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        .prime-reason-select {
            width: 100%;
            border-radius: 5px;
        }

        .justified-prime {
            background-color: #d1fae5;
            color: #065f46;
        }

        .unjustified-prime {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الأجور</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ حجز
                    منحة التمدرس </span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2>
                            <i class="fas fa-calendar-times ml-2"></i>منحة التمدرس:
                            {{ $setting->year }}

                        </h2>
                    </div>
                </div>
                <div class="filter-buttons mt-3 d-flex flex-wrap gap-2">
                    <button class="btn {{ $currentAdm == '' ? 'btn-warning' : 'btn-outline-warning' }} adm-filter-btn"
                        data-adm="">
                        الكل
                        <span class="badge bg-danger text-white ms-1">{{ $employees->count() }}</span>
                    </button>

                    @foreach ($departments as $department)
                        @if ($department->employee_count > 0)
                            <button
                                class="btn {{ $currentAdm == $department->ADM ? 'btn-warning' : 'btn-outline-warning' }} adm-filter-btn"
                                data-adm="{{ $department->ADM }}">
                                {{ $department->name }}
                                <span class="badge bg-danger text-white ms-1">{{ $department->employee_count }}</span>
                            </button>
                        @endif
                    @endforeach
                </div>
                <div class="card-body">
                    <div class="mb-3 text-left">
                        <button type="button" id="print-prime" class="btn btn-success" title="طباعة">
                            <i class="fas fa-print ml-1"></i> طباعة
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table key-buttons text-md-nowrap" id="primescolerite-table">
                            <thead>
                                <tr>
                                    <th>رمز الموظف</th>
                                    <th>اللقب و الاسم</th>
                                    <th>الرتبة</th>
                                    <th>عدد الاولاد</th>
                                    <th>عدد الاولاد المتمدرسين</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                    <tr data-department-type="{{ $employee->ADM }}">
                                        <td data-matri="{{ $employee->MATRI }}">{{ $employee->MATRI }}</td>
                                        <td>{{ $employee->NOMA . ' ' . $employee->PRENOMA }}</td>
                                        <td>{{ $employee->grade->name ?? 'غير متوفر' }}</td>
                                        <td>{{ $employee->primescolarites->first()->ENF ?? 0 }}</td>
                                        <td>{{ $employee->primescolarites->first()->ENFSCO ?? 0 }}</td>
                                        <td>
                                            <button type="button" class="btn btn-danger">
                                                <i class="fa-solid fa-floppy-disk"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <!-- DataTables Scripts -->
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <script>
        const currentYear = "{{ $setting->year ?? '' }}";
        const authGroup = "{{ auth()->user()->subGroup ? auth()->user()->subGroup->name : 'لا توجد مجموعة فرعية' }}";

        $(document).ready(function() {
            @if (!isset($setting) || !$setting->is_open)
                Swal.fire({
                    icon: 'warning',
                    title: '⚠️ تنبيه',
                    text: 'تم غلق الحجز من طرف مصالح المديرية. لا يمكن تعديل البيانات.',
                    confirmButtonText: 'حسنا',
                    allowOutsideClick: false
                });
            @endif
        });
    </script>
@endsection
