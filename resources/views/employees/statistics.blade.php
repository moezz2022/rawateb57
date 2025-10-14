@extends('layouts.master')
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/morris.js/morris.css') }}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"> الموظفين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    إحصائيات</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- بطاقات الإحصائيات -->
    <div class="row row-sm">
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="stat-card stat-card-primary">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="number">{{ $totalEmployees ?? $gradesWithCounts->sum('employees_count') }}</h4>
                        <p class="label mb-0">إجمالي الموظفين</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="stat-card stat-card-success">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="number">{{ $gradesWithCounts->count() }}</h4>
                        <p class="label mb-0">عدد الرتب</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-tag text-success"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="stat-card stat-card-info">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="number">{{ $groupsWithEmployees->count() }}</h4>
                        <p class="label mb-0">عدد المؤسسات</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-building text-info"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="stat-card stat-card-warning">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="number">
                            {{ $avgEmployeesPerGrade ?? round($gradesWithCounts->sum('employees_count') / ($gradesWithCounts->count() ?: 1)) }}
                        </h4>
                        <p class="label mb-0">متوسط الموظفين لكل رتبة</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-line text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الرسوم البيانية -->
    <div class="row row-sm">
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">توزيع الموظفين حسب الرتب</h4>
                </div>
                <div class="card-body">
                    <div id="gradesChart" class="chart-container"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">توزيع الموظفين حسب المؤسسات</h4>
                </div>
                <div class="card-body">
                    <div id="groupsChart" class="chart-container"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- جداول البيانات -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">إحصائيات عدد الموظفين حسب الرتب</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-md-nowrap" id="gradesTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الرتبة</th>
                                    <th>عدد الموظفين</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($gradesWithCounts as $index => $grade)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $grade->name }}</td>
                                        <td>{{ $grade->employees_count }}</td>
                                    </tr>
                                @endforeach
                                @if ($gradesWithCounts->isEmpty())
                                    <tr>
                                        <td colspan="3" class="text-center">لا توجد بيانات لعرضها</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">إحصائيات عدد الموظفين بالمؤسسات</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        @if ($groupsWithEmployees->isNotEmpty())
                            <table class="table text-md-nowrap" id="groupsTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>المؤسسة </th>
                                        <th>عدد الموظفين</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($groupsWithEmployees as $index => $group)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $group->name }}</td>
                                            <td>{{ $group->employees_count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-center">لا توجد مجموعات تحتوي على موظفين</p>
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
    <script src="{{ asset('assets/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/morris.js/morris.min.js') }}"></script>
    <script>
        window.groupsData = @json(
            $groupsWithEmployees->map(function ($group) {
                return [
                    'y' => $group->name,
                    'a' => $group->employees_count,
                ];
            }));
        window.gradesData = @json(
            $gradesWithCounts->map(function ($grade) {
                return [
                    'label' => $grade->name,
                    'value' => $grade->employees_count,
                ];
            }));
    </script>
@endsection
