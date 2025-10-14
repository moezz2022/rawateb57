@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" />
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الأجور</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ إدارة ملفات الرواتب</span>
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
                        <i class="fas fa-file-invoice-dollar ml-2"></i> ملفات الأجور
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
                <!-- Tab Content -->
                <div class="tab-content" id="payrollTabsContent">
                    <!-- الراتب -->
                    <div class="tab-pane fade show active" id="salary" role="tabpanel" aria-labelledby="salartab">
                        <div class="card-body">
                            <button type="button" class="btn btn-upload" data-toggle="modal" data-target="#salaryModal">
                                <i class="fas fa-upload"></i> تحميل ملف راتب جديد
                            </button>
                        </div>
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center text-white">
                                <div class="card-title">
                                    <form action="{{ route('paie.index') }}" method="GET" id="salaryYearForm"
                                        class="d-flex align-items-center gap-2">
                                        <i class="fas fa-calendar ml-2"></i>
                                        <select name="year" class="form-control"
                                            onchange="document.getElementById('salaryYearForm').submit()">
                                            @foreach ($years as $year)
                                                <option value="{{ $year }}"
                                                    {{ $selectedYear == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                            </div>
                            <div class="table-body mt-3">
                                <div class="table-responsive">
                                    <table class="table text-md-nowrap" id="pay1">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>اسم الملف</th>
                                                <th>الشهر</th>
                                                <th>السنة</th>
                                                <th>الحالة</th>
                                                <th>الإجراء</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($migrations as $migration)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-file-excel text-success ml-2"></i>
                                                            <span>{{ $migration->LOT ?? 'غير متوفر' }}</span>
                                                        </div>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::createFromDate(null, $migration->MONTH)->locale('ar_DZ')->translatedFormat('F') }}
                                                    </td>
                                                    <td>{{ $migration->YEAR }}</td>
                                                    <td>
                                                        @if ($migration->STATUS == 1)
                                                            <span class="status-badge success">
                                                                <i class="fas fa-check-circle"></i> تم التنفيذ
                                                            </span>
                                                        @else
                                                            <span class="status-badge danger">
                                                                <i class="fas fa-times-circle"></i> لم يتم التنفيذ
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group action-dropdown">
                                                            <button class="btn btn-purple btn-sm dropdown-toggle"
                                                                data-toggle="dropdown">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item"
                                                                    onclick="submitForm('{{ route('execute.file') }}', '{{ $migration->ID_MIGRATION }}', '{{ $migration->LOT }}')">
                                                                    <i class="fas fa-upload"></i> تنفيذ
                                                                </a>
                                                                <a class="dropdown-item"
                                                                    onclick="confirmDelete('{{ route('delete.file') }}', '{{ $migration->ID_MIGRATION }}', '{{ $migration->LOT }}')">
                                                                    <i class="fas fa-trash-alt"></i> حذف
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6">
                                                        <div class="empty-state">
                                                            <div class="empty-state-icon">
                                                                <i class="fas fa-file-excel"></i>
                                                            </div>
                                                            <h5 class="empty-state-text">لا توجد ملفات رواتب متاحة حاليا
                                                            </h5>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('paie.uploadpayroll')
                    <!-- المردودية -->
                    <div class="tab-pane fade" id="rndm" role="tabpanel" aria-labelledby="rndmTab">
                        <div class="card-body">
                            <button type="button" class="btn btn-upload" data-toggle="modal" data-target="#bonusModal">
                                <i class="fas fa-upload"></i> تحميل ملف مردودية جديد
                            </button>
                        </div>
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center text-white">
                                <div class="card-title">
                                    <form action="" method="GET" id="rndmYearForm"
                                        class="d-flex align-items-center gap-2">
                                        <i class="fas fa-calendar ml-2"></i>
                                        <select name="yearrndm" class="form-control"
                                            onchange="document.getElementById('rndmYearForm').submit()">
                                            @foreach ($yearsrndm as $year)
                                                <option value="{{ $year }}"
                                                    {{ $selectedYearRndm == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                            </div>
                            <div class="table-body">
                                <div class="table-responsive">
                                    <table class="table text-md-nowrap" id="pay2">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>العنوان</th>
                                                <th>السنة</th>
                                                <th>الثلاثي</th>
                                                <th>الحالة</th>
                                                <th>الإجراء</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($migrationsrndm as $migrationrndm)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-file-excel text-success ml-2"></i>
                                                            <span>{{ $migrationrndm->LOT ?? 'غير متوفر' }}</span>
                                                        </div>
                                                    </td>
                                                    <td>{{ $migrationrndm->YEAR }}</td>
                                                    <td>{{ $migrationrndm->TRIMESTER }}</td>
                                                    <td>
                                                        @if ($migrationrndm->STATUS == 1)
                                                            <span class="status-badge success">
                                                                <i class="fas fa-check-circle"></i> تم التنفيذ
                                                            </span>
                                                        @else
                                                            <span class="status-badge danger">
                                                                <i class="fas fa-times-circle"></i> لم يتم التنفيذ
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group action-dropdown">
                                                            <button class="btn btn-purple btn-sm dropdown-toggle"
                                                                data-toggle="dropdown">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item"
                                                                    onclick="submitForm('{{ route('execute.file_rndm') }}', '{{ $migrationrndm->ID_MIGRATION }}', '{{ $migrationrndm->LOT }}')">
                                                                    <i class="fas fa-upload"></i> تنفيذ
                                                                </a>
                                                                <a class="dropdown-item"
                                                                    onclick="confirmDelete('{{ route('delete.file_rndm') }}', '{{ $migrationrndm->ID_MIGRATION }}', '{{ $migrationrndm->LOT }}')">
                                                                    <i class="fas fa-trash-alt"></i> حذف
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6">
                                                        <div class="empty-state">
                                                            <div class="empty-state-icon">
                                                                <i class="fas fa-file-excel"></i>
                                                            </div>
                                                            <h5 class="empty-state-text">لا توجد ملفات مردودية متاحة حاليا
                                                            </h5>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @include('paie.upload_rndm')
                    <div class="tab-pane fade" id="arrears" role="tabpanel" aria-labelledby="arrears-tab">
                        <div class="p-3 text-center text-muted">
                            لا توجد مخلفات متاحة حاليا
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form id="actionForm" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="migration_id">
    </form>
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
        document.addEventListener("DOMContentLoaded", function() {
            alertify.set('notifier', 'position', 'bottom-left');

            @if (session('success'))
                alertify.success(@json(session('success')));
            @endif

            @if (session('error'))
                alertify.error(@json(session('error')));
            @endif
        });
    </script>
@endsection
