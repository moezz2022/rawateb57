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
                            @if ($setting)
                                {{ $setting->year }}
                            @else
                                <span class="text-danger">لا توجد بيانات حاليا</span>
                            @endif
                        </h2>

                        <div>
                            <a href="{{ route('prime_scolarité.primesettings') }}" class="btn btn-danger ml-2">
                                <i class="fas fa-cog ml-2"></i>الإعدادات
                            </a>
                        </div>
                    </div>
                </div>
                        @if (isset($setting) && $setting->is_open)
                    <div class="filter-buttons mt-3 d-flex flex-wrap gap-2">
                        <button class="btn {{ $currentAdm == '' ? 'btn-warning' : 'btn-outline-warning' }} adm-filter-btn"
                            data-adm="">
                            الكل
                            <span class="badge bg-danger text-white ms-1">{{ $employees->count() }}</span>
                        </button>

                        @foreach ($departments as $department)
                            <button
                                class="btn {{ $currentAdm == $department->ADM ? 'btn-warning' : 'btn-outline-warning' }} adm-filter-btn"
                                data-adm="{{ $department->ADM }}">
                                {{ $department->name }}
                                <span class="badge bg-danger text-white ms-1">{{ $department->employees_count }}</span>
                            </button>
                        @endforeach
                    </div>
                @endif
                <div class="card-body">
                    <div class="table-responsive">
                        @if (isset($setting) && $setting->is_open)
                            <table class="table key-buttons text-md-nowrap" id="primescolerite-table">
                                <thead>
                                    <tr>
                                        <th>رمز الموظف</th>
                                        <th>اللقب و الاسم</th>
                                        <th>الرتبة</th>
                                        <th>المؤسسة</th>
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
                                            <td>{{ $employee->group->name ?? 'غير معروف' }}</td>
                                            <td>
                                                <input type="number" name="ENF" class="form-control nb_enf"
                                                    value="{{ $employee->primescolarites->first()->ENF ?? 0 }}"
                                                    min="0" data-matri="{{ $employee->MATRI }}">
                                            </td>
                                            <td>
                                                <input type="number" name="ENFSCO" class="form-control nb_enfsco"
                                                    value="{{ $employee->primescolarites->first()->ENFSCO ?? 0 }}"
                                                    min="0" data-matri="{{ $employee->MATRI }}">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger save-btn"
                                                    data-matri="{{ $employee->MATRI }}" title="حفظ البيانات">
                                                    <i class="fa-solid fa-floppy-disk"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-lock fa-4x mb-3 text-danger"></i>
                                <h1 class="text-danger">الحجز مغلق حاليا.</h1>
                                <p class="text-muted mt-3">يرجى التواصل مع المسؤول لفتح الحجز.</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <!-- Internal Data tables -->
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
        const savePrimeUrl = "{{ route('prime_scolarité.store') }}";
        const csrfToken = "{{ csrf_token() }}";
        const currentYear = "{{ $setting->year ?? '' }}";
    </script>
@endsection
