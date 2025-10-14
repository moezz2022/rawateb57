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
                <!-- أزرار الإدارات -->
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
                    <!-- أزرار العمليات -->
                    <div class="mb-3 text-left">
                        <button type="button" id="print-rendements" class="btn btn-success" title="طباعة">
                            <i class="fas fa-print ml-1"></i> طباعة المردودية
                        </button>
                    </div>
                    <div class="table-responsive">
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
                                    <tr data-matri="{{ $employee->MATRI }}">
                                        <td>{{ $employee->MATRI }}</td>
                                        <td>{{ $employee->NOMA . ' ' . $employee->PRENOMA }}</td>
                                        <td>{{ $employee->grade->name ?? 'غير متوفر' }}</td>
                                        <td>
                                            <div class="input-group">
                                                <input type="number" name="mark" class="form-control mark"
                                                    value="{{ $primeRendement->mark ?? '' }}" max="{{ $maxMark }}"
                                                    min="0" readonly>
                                                <span class="input-group-text">/ {{ $maxMark }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" name="absence_days" class="form-control absence_days"
                                                value="{{ $employee->total_absences_period }}" min="0" readonly>
                                        </td>
                                        <td>
                                            <input type="text" name="notes" class="form-control notes"
                                                value="{{ e($primeRendement->notes ?? '') }}" placeholder="أدخل ملاحظة..."
                                                readonly>
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
        window.rendementPrint = {
            subGroup: "{{ auth()->user()->subGroup->name ?? 'لا توجد مجموعة' }}",
            period: "{{ $setting->period ?? 'غير محدد' }}",
            year: "{{ $year ?? 'غير محدد' }}",
            adm: "{{ $currentAdm ?? 'غير محددة' }}"
        };

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
