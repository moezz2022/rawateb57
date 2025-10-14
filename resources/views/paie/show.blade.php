@extends('layouts.master')
@section('css')
    <!-- DataTables Styles -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
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
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header bg-light">
                    <h3 class="card-title">
                        <i class="fas fa-file-invoice-dollar ml-2"></i>
                        كشوف الرواتب
                    </h3>
                </div>
                <ul class="nav nav-tabs" id="payrollTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="salartab" data-toggle="tab" data-target="#salary"
                            href="javascript:void(0)" role="tab">الراتب</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="rndmTab" data-toggle="tab" data-target="#rndm" href="javascript:void(0)"
                            role="tab">المردودية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="arrears-tab" data-toggle="tab" data-target="#arrears"
                            href="javascript:void(0)" role="tab">المخلفات</a>
                    </li>
                </ul>
                <div class="tab-content" id="payrollTabsContent">
                    <!-- الراتب -->
                    <div class="tab-pane fade show active" id="salary" role="tabpanel" aria-labelledby="salartab">
                        <div class="card-body">
                            <!-- نافذة البحث عن الموظف -->
                            <div class="modal fade" id="payrollModal" tabindex="-1" role="dialog"
                                aria-labelledby="payrollModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg custom-modal" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="payrollModalLabel"> <i
                                                    class="fas fa-search mr-2"></i>البحث
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
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">إغلاق</button>
                                                    <button type="submit" class="btn btn-primary search-btn">بحث</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center text-white">
                                    <div class="card-title">
                                        <form action="" method="GET" id="yearForm"
                                            class="d-flex align-items-center gap-2">
                                            <i class="fas fa-calendar ml-2"></i>
                                            <select name="year" class="form-control col-12"
                                                onchange="document.getElementById('yearForm').submit()">
                                                @foreach ($years as $year)
                                                    <option value="{{ $year }}"
                                                        {{ request('year', date('Y')) == $year ? 'selected' : '' }}>
                                                        {{ $year }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    @forelse ($migrations as $migration)
                                        <div class="d-flex justify-content-between align-items-center border-bottom p-1">
                                            <h5>
                                                شهر
                                                {{ \Carbon\Carbon::createFromDate(null, $migration->MONTH)->locale('ar_DZ')->translatedFormat('F') }}
                                            </h5>
                                            <div class="btn-group">
                                                <a href="{{ url('/paie/salary_details') }}?month={{ $migration->MONTH }}&year={{ $migration->YEAR }}&adm={{ $firstAdm->ADM ?? '' }}"
                                                    class="btn btn-outline-danger btn-salary-details">
                                                    <i class="fa-solid fa-check"></i> الكشف المجمل
                                                </a>
                                                <button type="button"
                                                    class="btn btn-outline-warning ml-2 open-payroll-modal"
                                                    data-month="{{ $migration->MONTH }}"
                                                    data-year="{{ $migration->YEAR }}" data-toggle="modal"
                                                    data-target="#payrollModal">
                                                    <i class="fas fa-search mr-2"></i> الكشف الفردي
                                                </button>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="p-3 text-center text-muted">
                                           لا توجد  رواتب متاحة حاليا
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="rndm" role="tabpanel" aria-labelledby="rndmTab">
                         @include('paie.showRndm')
                    </div>
                        <div class="tab-pane fade" id="arrears" role="tabpanel" aria-labelledby="arrears-tab">
                        <div class="p-3 text-center text-muted">
                            لا توجد مخلفات متاحة حاليا
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $('.open-payroll-modal').on('click', function() {
            var month = $(this).data('month');
                var year = $(this).data('year');
                $('#hiddenMonth').val(month);
                $('#hiddenYear').val(year);
            });

            $('#payrollModal').on('hidden.bs.modal', function() {
                $('#searchForm')[0].reset();
            });
    </script>
@endsection
