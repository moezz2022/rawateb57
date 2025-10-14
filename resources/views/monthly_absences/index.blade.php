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
        /* Custom styling for the absence reason dropdown */
        .absence-reason-select {
            width: 100%;
            border-radius: 5px;
        }

        .justified-absence {
            background-color: #d1fae5;
            color: #065f46;
        }

        .unjustified-absence {
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
                    الغيابات الشهرية</span>
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
                            <i class="fas fa-calendar-times ml-2"></i>الغيابات الشهرية:

                            @php
                                $arabicMonths = [
                                    1 => 'جانفي',
                                    2 => 'فيفري',
                                    3 => 'مارس',
                                    4 => 'أفريل',
                                    5 => 'ماي',
                                    6 => 'جوان',
                                    7 => 'جويلية',
                                    8 => 'أوت',
                                    9 => 'سبتمبر',
                                    10 => 'أكتوبر',
                                    11 => 'نوفمبر',
                                    12 => 'ديسمبر',
                                ];
                            @endphp

                            @if ($currentSetting)
                                {{ $arabicMonths[$currentSetting->month] ?? 'شهر غير معروف' }} {{ $currentSetting->year }}
                            @else
                                <span class="text-danger">لا توجد بيانات للشهر الحالي</span>
                            @endif
                        </h2>

                        <div>
                            <a href="{{ route('monthly_absences.months') }}" class="btn btn-danger ml-2">
                                <i class="fas fa-cog ml-2"></i>الإعدادات
                            </a>
                        </div>
                    </div>
                </div>
                @if ($currentSetting && $currentSetting->is_open)
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
                        @if ($currentSetting && $currentSetting->is_open)
                            <table class="table key-buttons text-md-nowrap" id="absences-table">
                                <thead>
                                    <tr>
                                        <th>رمز الموظف</th>
                                        <th>اللقب و الاسم</th>
                                        <th>الرتبة</th>
                                        <th>المؤسسة</th>
                                        <th>عدد أيام الغياب</th>
                                        <th>سبب الغياب</th>
                                        <th>العمليات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $employee)
                                        @php
                                            $absence = $employee->monthlyAbsences->first();
                                            $absenceDays = $absence ? $absence->absence_days : 0;
                                            $rowClass = $absenceDays > 0 ? '' : 'zero-absence';
                                        @endphp
                                        <tr data-department-type="{{ $employee->ADM }}" class="{{ $rowClass }}">
                                            <td data-matri="{{ $employee->MATRI }}">{{ $employee->MATRI }}</td>
                                            <td>{{ $employee->NOMA . ' ' . $employee->PRENOMA }}</td>
                                            <td>{{ $employee->grade->name ?? 'غير متوفر' }}</td>
                                            <td>{{ $employee->group->name ?? 'غير معروف' }}</td>
                                            <td>
                                                <input type="number" name="absence_days" class="form-control absence_days"
                                                    value="{{ $absenceDays }}" min="0" max="30"
                                                    data-matri="{{ $employee->MATRI }}">
                                            </td>
                                            <td>
                                                <select name="absence_reason" class="form-control absence_reason"
                                                    data-matri="{{ $employee->MATRI }}">
                                                    <option value="غياب مبرر"
                                                        {{ $absence && $absence->absence_reason == 'غياب مبرر' ? 'selected' : '' }}>
                                                        غياب مبرر</option>
                                                    <option value="غياب غير مبرر"
                                                        {{ $absence && $absence->absence_reason == 'غياب غير مبرر' ? 'selected' : '' }}>
                                                        غياب غير مبرر</option>
                                                </select>
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
        const saveAbsenceUrl = "{{ route('monthly_absences.store') }}";
        const csrfToken = "{{ csrf_token() }}";
    </script>
@endsection
