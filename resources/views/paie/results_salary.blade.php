@extends('layouts.master')
@section('css')
    <!-- DataTables Styles -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        .scroll-area {
            max-height: 60vh;
            overflow-y: auto;
            border: 1px solid #e9ecef;
            border-radius: .5rem;
        }

        .table-sticky thead th {
            top: 0;
            z-index: 2;
            background: #f8f9fa;
        }

        .table-sticky tbody tr:hover {
            background: #f6f6f6;
        }

        [dir="rtl"] .scroll-area {
            direction: rtl;
            scrollbar-gutter: stable both-edges;
        }

        .scroll-area::-webkit-scrollbar {
            width: 10px;
        }

        .scroll-area::-webkit-scrollbar-thumb {
            background: #ced4da;
            border-radius: 8px;
        }

        .scroll-area::-webkit-scrollbar-thumb:hover {
            background: #adb5bd;
        }
    </style>
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الأجور</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ كشف الراتب</span>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <!-- نافذة البحث عن الموظف -->
    <div class="modal fade" id="payrollModal" tabindex="-1" role="dialog" aria-labelledby="payrollModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg custom-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="payrollModalLabel"> <i class="fas fa-search mr-2"></i>البحث
                        عن كشف الراتب</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="searchForm" method="GET" action="{{ route('paie.search') }}">
                        <div class="form-group">
                            <label for="search">أدخل اسم الموظف أو رقم الحساب:</label>
                            <input type="text" class="form-control" id="search" name="search"
                                placeholder="أدخل الاسم أو رقم الحساب" required>
                            <input type="hidden" id="hiddenMonth" name="month" value="">
                            <input type="hidden" id="hiddenYear" name="year" value="">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                            <button type="submit" class="btn btn-primary search-btn">بحث</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- مودال قائمة الموظفين -->
    <div class="modal fade" id="employeeListModal" tabindex="-1" role="dialog" aria-labelledby="employeeListModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content shadow-lg rounded-3 border-0">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="employeeListModalLabel">
                        <i class="fas fa-users mr-2"></i> قائمة الموظفين
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- التبويبات -->
                    <ul class="nav nav-tabs" id="employeeTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all-employees"
                                role="tab">جميع الموظفين</a>
                        </li>
                    </ul>
                    <!-- محتوى التبويبات -->
                    <div class="tab-content mt-3"
                        style="background-color: #fff; border-radius: 10px; padding: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);"
                        id="employeeTabsContent">
                        <!-- جميع الموظفين -->
                        <div class="tab-pane fade show active" id="all-employees" role="tabpanel">
                            <div class="row">
                                <!-- البحث -->
                                <div class="col-md-4 border-right">
                                    <div class="form-group">
                                        <label for="employeeSearch" class="font-weight-bold">🔍 البحث عن
                                            موظف</label>
                                        <input type="text" id="employeeSearch" class="form-control"
                                            placeholder="اكتب الاسم أو رقم الحساب...">
                                        <small class="form-text text-muted">ابحث باستخدام الاسم، اللقب أو رقم
                                            الحساب الجاري.</small>
                                    </div>
                                </div>
                                <!-- الجدول -->
                                <div class="col-md-8">
                                    <!-- حاوية قابلة للتمرير -->
                                    <div class="tab-content scroll-area"
                                        style="max-height: 300px; overflow-y: auto; direction: rtl;">
                                        <table class="table table-striped table-hover table-sticky"
                                            id="allEmployeesTable">
                                            <tbody>
                                                <!-- نتائج البحث عبر AJAX -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div> <!-- row -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> إغلاق
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div id="employee-list">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title mg-b-0">
                                <i class="fas fa-file-invoice-dollar ml-2"></i>
                                نتائج البحث
                            </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($employees->isEmpty())
                            <div class="alert alert-info text-center">لا توجد نتائج مطابقة.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table text-md-nowrap" id="example1">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>الاسم الكامل</th>
                                            <th>الرتبة</th>
                                            <th>رقم الحساب</th>
                                            <th>الإجراء</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employees as $index => $employee)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $employee->NOMA }} {{ $employee->PRENOMA }}</td>
                                                <td data-label="الرتبة">
                                                    @foreach ($grades as $grade)
                                                        @if ($employee->CODFONC == $grade->codtab)
                                                            {{ $grade->name }}
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>{{ $employee->MATRI }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-lg btn-fill"
                                                        data-toggle="modal" data-target="#employeeListModal">
                                                        <i class="fas fa-users ml-2"></i> قائمة الموظفين
                                                    </button>
                                                    <button class="btn btn-danger payroll-link"
                                                        data-matri="{{ $employee->MATRI }}"
                                                        data-month="{{ $month }}" data-year="{{ $year }}"
                                                        data-lang="ar">
                                                        <i class="fas fa-file-alt mr-2"></i> عرض كشف الراتب
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
                {{-- قسم عرض كشف الراتب --}}
                @if ($employees->isNotEmpty())
                    <div id="payroll-details" class="card" style="display: none;">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title"> <i class="fas fa-file-invoice-dollar ml-2"></i>
                                    تفاصيل كشف الراتب
                                </h3>
                                <a href="{{ route('paie.show') }}" type="button"
                                    class="btn strong btn-warning btn-lg btn-fill">
                                    <i class="fas fa-arrow-right ml-2"></i> العودة إلى صفحة الكشوف
                                </a>
                            </div>
                        </div>
                        <div class="card-footer text-left">
                            <!-- قائمة الموظفين -->
                            <button type="button" class="btn btn-info btn-lg btn-fill" data-toggle="modal"
                                data-target="#employeeListModal">
                                <i class="fas fa-users ml-2"></i> قائمة الموظفين
                            </button>
                            <!-- الكشف المجمل -->
                            @if ($migration)
                                <button type="button" class="btn btn-secondary btn-lg btn-fill"
                                    onclick="window.location.href='{{ url('/paie/salary_details') }}?month={{ $migration->MONTH }}&year={{ $migration->YEAR }}&adm={{ $firstAdm->ADM ?? '' }}'">
                                    <i class="fa-solid fa-check"></i> الكشف المجمل
                                </button>
                            @endif
                            <!-- إدراج منحة المردودية -->
                            <button type="button" id="toggleMrdiyyaBtn" class="btn btn-danger btn-lg btn-fill">
                                <i class="fas fa-gift mr-2"></i> إدراج منحة المردودية
                            </button>
                            <!-- فرنسي -->
                            @php $first = $employees->first(); @endphp
                            <button type="button" id="toggleLangBtn" class="btn btn-success btn-lg btn-fill"
                                data-matri="{{ $first->MATRI ?? '' }}" data-month="{{ $month }}"
                                data-year="{{ $year }}" data-lang="fr"
                                {{ $employees->isEmpty() ? 'disabled' : '' }}>
                                <i class="fas fa-file-alt mr-2"></i> فرنسي
                            </button>
                            <!-- طباعة -->
                            <button type="button" class="btn btn-primary btn-lg btn-fill" id="printSlipButton">
                                <i class="fas fa-print mr-2"></i> طباعة
                            </button>
                        </div>
                        <div class="card-body" id="salary-slip"></div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('js')
    <!-- DataTables js -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    <script>
        window.payrollConfig = {
            month: "{{ $month }}",
            year: "{{ $year }}"
        };
    </script>
@endsection
