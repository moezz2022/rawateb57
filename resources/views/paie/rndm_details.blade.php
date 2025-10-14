@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        /* الشاشة العادية */
        .report-header-print {
            display: none;
            /* لا يظهر على الشاشة */
        }

        /* عند الطباعة */
        @media print {
            .report-header-print {
                display: block;
                /* يظهر فقط في الطباعة */
            }

            /* يمكنك إخفاء عناصر أخرى غير مطلوبة */
            #rndm_details .card-body> :not(.report-header-print) {
                /* عناصر التقرير العادي */
            }
        }
    </style>
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الأجور</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ المردودية</span>
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
                        تقرير تفصيلي للمردودية
                    </h3>
                </div>
                <div class="card-body">
                    <div class="card-header d-flex align-items-center text-white gap-1 justify-content-between">
                        <div class="card-title justify-content-start">
                            {{-- اختيار السنة --}}
                            <form action="" method="GET" id="yearTrimestreForm"
                                class="d-flex align-items-center gap-2">
                                <i class="fas fa-calendar ml-2"></i>

                                <select name="year" class="form-control"
                                    onchange="document.getElementById('yearTrimestreForm').submit()">
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}"
                                            {{ request('year', date('Y')) == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>

                                {{-- اختيار الفصل --}}
                                <select name="trimestre" class="form-control"
                                    onchange="document.getElementById('yearTrimestreForm').submit()">
                                    @php
                                        $trimestres = ['الأول', 'الثاني', 'الثالث', 'الرابع'];
                                    @endphp

                                    @foreach (range(1, 4) as $m)
                                        <option value="{{ $m }}"
                                            {{ request('trimestre', 1) == $m ? 'selected' : '' }}>
                                            {{ $trimestres[$m - 1] }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        <div>
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
                                        <button type="button" class="btn btn-outline-primary btn-admRndm"
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
                <div id="rndm_details" class="card mt-0" style="display: none;">
                    <div class="card-body" id="rndm_report_content">
                        <div class="report-header-print">
                            <div class="report-header text-center">
                                <h3>الجمهورية الجزائرية الديمقراطية الشعبية</h3>
                                <h3>وزارة التربية الوطنية</h3>
                            </div>
                            <h4>مديرية التربية لولاية المغير</h4>
                            <h4>{{ auth()->user()->subGroup ? auth()->user()->subGroup->name : 'لا توجد مجموعة فرعية' }}
                            </h4>
                            <div class="report-title">
                                <h2>
                                    علاوة تحسين الإداء التسيري والتربوي وعلاوة المردودية:
                                    للثلاثي <span id="report_trimestre"></span>
                                    <span id="report_year"></span>
                                </h2>
                            </div>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle ml-2"></i>
                                تاريخ إنشاء التقرير: {{ date('d-m-Y') }}
                            </div>
                        </div>
                        <div id="results" class="table-responsive mt-3">
                            <table class="table text-md-nowrap" id="rndmdetails">
                                <thead>
                                    <tr>
                                        <th>رمز الموظف</th>
                                        <th>اسم الموظف</th>
                                        <th>الصنف<br>
                                            الدرجة</th>
                                        <th>ايام العمل </th>
                                        <th> العلامة </th>
                                        <th>الأجر القاعدي</th>
                                        <th> خاضع.ض.الاجتماعي </th>
                                        <th>الخام</th>
                                        <th>إق.ض.الاجتماعي </th>
                                        <th>إق.الضريبة </th>
                                        <th>الصافي</th>
                                    </tr>
                                </thead>
                                <tbody id="rndm_table_body"></tbody>

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
            trimestre: "{{ request('trimestre') }}",
            year: "{{ request('year') }}",
            adm: "{{ request('adm') ?? ($firstAdm->ADM ?? '') }}",
            admName: "{{ request('adm_name') ?? ($firstAdm->name ?? '') }}"
        };
    </script>
@endsection
