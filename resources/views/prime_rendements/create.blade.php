@extends('layouts.master')
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الأجور</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ حجز المردودية</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line ml-2"></i>
                        المردودية للثلاثي: {{ $setting->period ?? 'غير محددة' }} {{ $setting->year ?? 'غير محددة' }}
                    </h3>
                </div>

                @if (isset($setting) && $setting->is_open)
                    <!-- أزرار الإدارات -->
                    <div class="filter-buttons mt-3 d-flex flex-wrap gap-2">
                        @foreach ($departments as $department)
                            <button
                                class="btn {{ $currentAdm == $department->ADM ? 'btn-primary' : 'btn-outline-primary' }} adm-filter-btn"
                                data-adm="{{ $department->ADM }}">
                                {{ $department->name }}
                                <span class="badge bg-danger text-white ms-1">{{ $department->employee_count }}</span>
                            </button>
                        @endforeach
                    </div>
                @endif

                <div class="card-body">
                    <!-- أزرار العمليات -->
                    <div class="mb-3 text-left">
                        <button type="button" id="save-all" class="btn btn-success ml-2">
                            <i class="fa-solid fa-floppy-disk"></i> حفظ
                        </button>
                        <button type="button" id="set-full-marks" class="btn btn-danger ml-2">
                            <i class="fas fa-star"></i> العلامة الكاملة للجميع
                        </button>
                        <button type="button" id="reset-all" class="btn btn-warning ml-2">
                            <i class="fas fa-undo"></i> إلغاء الحفظ
                        </button>
                        <button type="button" id="print-rendements" class="btn btn-primary" title="طباعة">
                            <i class="fas fa-print ml-1"></i> طباعة المردودية
                        </button>
                    </div>
                    <div class="table-responsive">
                        @if (isset($setting) && $setting->is_open)
                            <table class="table key-buttons text-md-nowrap" id="primeTable">
                                <thead>
                                    <tr>
                                        <th>رمز الموظف</th>
                                        <th>اللقب و الاسم</th>
                                        <th>الرتبة</th>
                                        <th>العلامة</th>
                                        <th>عدد أيام الغياب</th>
                                        <th>ملاحظات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $employee)
                                        @php
                                            $isEligible40 = in_array($employee->CODFONC, $eligibleFor40);
                                            $maxMark = $isEligible40 ? 40 : 30;
                                            $primeRendement = optional($employee->primeRendements->first());
                                        @endphp
                                        <tr data-matri="{{ $employee->MATRI }}" data-adm="{{ $employee->ADM }}">
                                            <td>{{ $employee->MATRI }}</td>
                                            <td>{{ $employee->NOMA . ' ' . $employee->PRENOMA }}</td>
                                            <td>{{ $employee->grade->name ?? 'غير متوفر' }}</td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="number" name="mark" class="form-control mark"
                                                        value="{{ $primeRendement->mark ?? '' }}"
                                                        max="{{ $maxMark }}" min="0">
                                                    <span class="input-group-text">/ {{ $maxMark }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="number" name="absence_days" class="form-control absence_days"
                                                    value="{{ $employee->total_absences_period }}" min="0" readonly>
                                            </td>
                                            <td>
                                                <input type="text" name="notes" class="form-control notes"
                                                    value="{{ e($primeRendement->notes ?? '') }}"
                                                    placeholder="أدخل ملاحظة...">
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
    <!-- DataTables Scripts -->
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <script>
        window.primeConfig = {
            storeUrl: "{{ route('prime_rendements.store') }}",
            resetUrl: "{{ route('prime_rendements.reset') }}",
            csrf: "{{ csrf_token() }}",
            year: "{{ $setting->year }}",
            quarter: "{{ $setting->quarter }}",
        };
        window.rendementPrint = {
            subGroup: "{{ auth()->user()->subGroup->name ?? 'لا توجد مجموعة' }}",
            period: "{{ $setting->period ?? 'غير محدد' }}",
            year: "{{ $year ?? 'غير محدد' }}",
            adm: "{{ $currentDepartment->name ?? 'غير محددة' }}" // 👈 الإدارة
        };
    </script>
@endsection
