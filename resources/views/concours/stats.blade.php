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
        .stat-card {
            transition: transform 0.2s;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 30px;
        }
    </style>
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المستخدمين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    احصائيات</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h2 class="text-center mb-4">📊 إحصائيات المترشحين</h2>
                </div>
                <div class="card-body">
                    <!-- الجدول الأول: إحصائيات حسب البلديات -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                                    <h4 class="mb-0">📍 إحصائيات المترشحين حسب البلديات</h4>
                                    <button class="btn btn-sm btn-light"
                                        onclick="$('#communeTable').DataTable().button('.buttons-excel').trigger();">
                                        <i class="fa fa-download"></i> تصدير Excel
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="communeTable" class="table table-striped text-md-nowrap">
                                            <thead class="thead">
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th>البلدية</th>
                                                    <th class="text-center">عدد المسجلين</th>
                                                    <th class="text-center">الملفات المطابقة</th>
                                                    <th class="text-center">الملفات غير المطابقة</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($byCommune as $index => $item)
                                                    @php
                                                        $percentage = $total > 0 ? round(($item->total / $total) * 100, 1) : 0;
                                                        $concoursModel = new \App\Models\Concours();
                                                        $communeName = $concoursModel->getResidenceMunicipality($item->residenceMunicipality);
                                                    @endphp
                                                    <tr>
                                                        <td class="text-center">{{ $index + 1 }}</td>
                                                        <td><strong>{{ $communeName }}</strong></td>
                                                        <td class="text-center">
                                                            <span class="badge badge-primary badge-pill" style="font-size: 14px;">{{ $item->total }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge badge-success badge-pill" style="font-size: 14px;">{{ $item->accepted }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge badge-danger badge-pill" style="font-size: 14px;">{{ $item->rejected }}</span>
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

                    <!-- الجدول الثاني: إحصائيات حسب الرتب -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center bg-success text-white">
                                    <h4 class="mb-0">🏆 إحصائيات المترشحين حسب الرتب</h4>
                                    <button class="btn btn-sm btn-light"
                                        onclick="$('#gradeTable').DataTable().button('.buttons-excel').trigger();">
                                        <i class="fa fa-download"></i> تصدير Excel
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="gradeTable" class="table table-striped text-md-nowrap">
                                            <thead class="thead">
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th>الرتبة</th>
                                                    <th class="text-center">عدد المسجلين</th>
                                                    <th class="text-center">الملفات المطابقة</th>
                                                    <th class="text-center">الملفات غير المطابقة</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($byGrade as $index => $item)
                                                    @php
                                                        $percentage = $total > 0 ? round(($item->total / $total) * 100, 1) : 0;
                                                        $gradeName = \App\Models\Concours::getGradeLabel($item->con_grade);
                                                    @endphp
                                                    <tr>
                                                        <td class="text-center">{{ $index + 1 }}</td>
                                                        <td><strong>{{ $gradeName }}</strong></td>
                                                        <td class="text-center">
                                                            <span class="badge badge-primary badge-pill" style="font-size: 14px;">{{ $item->total }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge badge-success badge-pill" style="font-size: 14px;">{{ $item->accepted }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge badge-danger badge-pill" style="font-size: 14px;">{{ $item->rejected }}</span>
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
                </div>
            </div>
        </div>
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
            // Initialize DataTable for Commune
            $('#communeTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json'
                },
                order: [[2, 'desc']],
                pageLength: 25
            });

            // Initialize DataTable for Grade
            $('#gradeTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json'
                },
                order: [[2, 'desc']],
                pageLength: 25
            });
        });
    </script>
@endsection