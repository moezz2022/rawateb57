@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        .report-header-print {
            display: none;
        }

        @media print {
            .report-header-print {
                display: block;
            }

            #salary_details .card-body> :not(.report-header-print) {
            }
        }
    </style>
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الأجور</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تقرير تفصيلي للرواتب الموظفين</span>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-invoice-dollar ml-2"></i>
                        تقرير تفصيلي للرواتب
                    </h3>
                </div>
                <div class="card-body">
                    <div class="card-header d-flex align-items-center text-white gap-1 justify-content-between">
                        <div class="card-title justify-content-start">
                            {{-- اختيار السنة --}}
                            <form action="" method="GET" id="yearMonthForm" class="d-flex align-items-center gap-2">
                                <i class="fas fa-calendar ml-2"></i>

                                <select name="year" class="form-control col-8"
                                    onchange="document.getElementById('yearMonthForm').submit()">
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}"
                                            {{ request('year', date('Y')) == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>

                                {{-- اختيار الشهر --}}
                                <select name="month" class="form-control col-12"
                                    onchange="document.getElementById('yearMonthForm').submit()">
                                    @foreach (range(1, 12) as $m)
                                        <option value="{{ $m }}"
                                            {{ request('month', date('m')) == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->locale('ar-tn')->translatedFormat('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        <div>
                            <a href="{{ route('paie.show') }}" class="btn btn-success ml-2">
                                <i class="fa fa-file-text ml-2"></i>الكشف الفردي
                            </a>
                            <button class="btn btn-danger" id="printdetailsButton">
                                <i class="fas fa-print ml-2"></i>
                                طباعة التقرير
                            </button>
                        </div>
                    </div>

                    <div class="action-toolbar">
                        <div>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($departments as $dept)
                                    @if ($dept->employee_count > 0)
                                        <button type="button" class="btn btn-outline-primary btn-adm"
                                            data-adm="{{ $dept->ADM }}" data-name="{{ $dept->name }}">
                                            {{ $dept->name }}
                                            <span class="badge bg-danger ms-1">{{ $dept->employee_count }}</span>
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- تقرير تفصيلي -->
                <div id="salary_details" class="card mt-0" style="display: none;">
                    <div class="card-body" id="salary_report_content">
                        <div class="report-header-print d-none">
                            <div class="report-header text-center">
                                <h3>الجمهورية الجزائرية الديمقراطية الشعبية</h3>
                                <h3>وزارة التربية الوطنية</h3>
                            </div>
                            <h4>مديرية التربية لولاية المغير</h4>
                            <h4>{{ auth()->user()->subGroup ? auth()->user()->subGroup->name : 'لا توجد مجموعة فرعية' }}
                            </h4>
                            <div class="report-title">
                                <h2>تقرير تفصيلي للرواتب الموظفين : <span id="report_department"></span></h2>
                                <h2>لشهر <span id="report_month"></span> <span id="report_year"></span></h2>
                            </div>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle ml-2"></i>
                                تاريخ إنشاء التقرير: {{ date('d-m-Y') }}
                            </div>
                        </div>
                        <div id="results" class="table-responsive mt-3">
                            <table class="table text-md-nowrap" id="salarydetails">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>اسم الموظف</th>
                                        <th>الصنف<br>
                                            الدرجة</th>
                                        <th>حالة عائلية </th>
                                        <th>الأجر القاعدي</th>
                                        <th>م.خبرة_مهنية <br>
                                            م.بيداغوجية </th>
                                        <th>م.جزافية<br>
                                            م.التوثيق<br>
                                            م.السكن </th>
                                        <th>م.المنطقة<br>
                                            م.التأهيل </th>
                                        <th>م.الجنوب <br>
                                            م.الضرر<br>
                                            م.د.م.م.بيداغوجية</th>
                                        <th>م.ت.خ.التقنية<br>
                                            م.د.ن.الإداري<br>
                                            م.خ.إ.المشتركة
                                        </th>
                                        <th>م.ت.المؤسسة<br>
                                            م.ت.م.المادي<br>
                                            م.م.العالي</th>
                                        <th>م.عائلية</th>
                                        <th>الخام</th>
                                        <th>إق.ض.الاجتماعي <br>
                                            إق.الضريبة </th>
                                        <th>إق.الخدمات </th>
                                        <th>إق.الغياب <br>
                                            إق.الاضراب</th>
                                        <th> أ.العمل</th>
                                        <th>صافي</th>
                                    </tr>
                                </thead>
                                <tbody id="salary_table_body"></tbody>

                            </table>
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
    window.payrollDefaults = {
        month: "{{ request('month') }}",
        year: "{{ request('year') }}",
        adm: "{{ request('adm') ?? ($firstAdm->ADM ?? '') }}",
        admName: "{{ request('adm_name') ?? ($firstAdm->name ?? '') }}"
    };
</script>
@endsection
