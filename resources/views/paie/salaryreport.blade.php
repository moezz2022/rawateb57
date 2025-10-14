@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الأجور</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تقرير مفصل </span>
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
                        تقرير مفصل لراتب موظف
                    </h3>
                </div>
                <div class="card-body">
                    <div class="action-toolbar">
                        <div>
                            <!-- زر فتح نافذة البحث -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#payrollModal">
                                <i class="fas fa-search ml-2"></i>
                                عرض تقرير تفصيلي
                            </button>
                        </div>
                        <div>
                            <button class="btn btn-primary" id="printreportButton" style="display: none;">
                                <i class="fas fa-print ml-2"></i>
                                طباعة التقرير
                            </button>
                        </div>
                    </div>

                    <!-- نافذة البحث عن الموظف -->
                    <div class="modal fade" id="payrollModal" tabindex="-1" role="dialog"
                        aria-labelledby="payrollModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg custom-modal" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="payrollModalLabel">
                                        <i class="fas fa-search ml-2"></i>
                                        البحث عن تقرير تفصيلي
                                    </h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="payrollreportForm">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="search">البحث عن الموظف (برقم ccp)</label>
                                            <input type="text" class="form-control" id="search" name="search"
                                                placeholder="أدخل رقم ccp" required>
                                        </div>

                                        <div class="form-group">
                                            <label>السنة</label>
                                            <select class="form-control" id="year" name="year" required>
                                                <option value="" selected disabled>اختر السنة</option>
                                                @for ($i = 2020; $i <= date('Y'); $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        @php
                                            $months = [
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

                                        <div class="form-group">
                                            <label>من الشهر</label>
                                            <select class="form-control" id="start_month" name="start_month" required>
                                                <option value="" disabled selected>اختر الشهر الأول</option>
                                                @foreach ($months as $num => $name)
                                                    <option value="{{ $num }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>إلى الشهر</label>
                                            <select class="form-control" id="end_month" name="end_month" required>
                                                <option value="" disabled selected>اختر الشهر الأخير</option>
                                                @foreach ($months as $num => $name)
                                                    <option value="{{ $num }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                                        <button type="submit" class="btn btn-primary">عرض التقرير</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- تقرير تفصيلي -->
                    <div id="salary_report" class="card mt-3" style="display: none;">
                        <div class="card-body" id="salary_report_content">
                            <div class="report-header text-center">
                                <h3>الجمهورية الجزائرية الديمقراطية الشعبية</h3>
                                <h3>وزارة التربية الوطنية</h3>
                            </div>
                            <h4>مديرية التربية لولاية المغير</h4>
                            <h4>{{ auth()->user()->subGroup ? auth()->user()->subGroup->name : 'لا توجد مجموعة فرعية' }}
                            </h4>
                            <div class="report-title">
                                <h2>تقرير تفصيلي للرواتب السيد(ة): <span id="employee_name">
                                    </span> الرتبة: <span id="employee_rank"></span></h2>
                                <h2>من شهر <span id="from_month"></span> إلى شهر <span id="to_month"></span> سنة <span
                                        id="report_year"></span></h2>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-info-circle ml-2"></i>
                                تاريخ إنشاء التقرير: {{ date('d-m-Y') }}
                            </div>

                            <div id="results" class="table-responsive mt-3">
                                <table class="table text-md-nowrap" id="example2">
                                    <thead>
                                        <tr>
                                            <th>الشهر</th>
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
                                                م.م.بيداغوجية</th>
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
    </div>
@endsection

@section('js')
    <script src="{{ URL::asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/sweet-alert/sweetalert.min.js') }}"></script>
@endsection
