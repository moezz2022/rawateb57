@extends('layouts.master')
@section('css')
    <!-- DataTables Styles -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <style>

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
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header bg-light">
                    <h3 class="card-title">
                        <i class="fas fa-file-invoice-dollar ml-2"></i>
                        الكشوف السنوية
                    </h3>
                </div>
                <div class="card-body">
                    <div class="action-toolbar">
                        <div>
                            <!-- زر فتح نافذة البحث -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#payrollModal">
                                <i class="fas fa-search mr-2"></i> البحث عن كشف الراتب السنوي
                            </button>
                        </div>
                    </div>

                    <!-- نافذة البحث عن الموظف -->
                    <div class="modal fade" id="payrollModal" tabindex="-1" role="dialog"
                        aria-labelledby="payrollModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg custom-modal" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="payrollModalLabel">البحث عن كشف الراتب السنوي</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="searchFormAnnual" method="GET" action="{{ route('paie.searchannual') }}">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="year">السنة</label>
                                            <select class="form-control" id="year" name="year" required>
                                                <option value="" disabled selected>اختر السنة</option>
                                                @for ($i = 2020; $i <= date('Y'); $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="search">البحث عن الموظف (الاسم واللقب أو رقم الحساب
                                                البريدي)</label>
                                            <input type="text" class="form-control" id="searchannual" name="search"
                                                placeholder="أدخل الاسم أو رقم الحساب" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                                        <button type="submit" class="btn search-btn">بحث</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- عرض نتائج البحث -->
                    <div id="results" class="table-responsive mt-3">
                        <table class="table text-md-nowrap" id="payannual">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>رقم الحساب البريدي</th>
                                    <th>كشف الراتب</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- سيتم تحميل النتائج ديناميكياً -->
                            </tbody>
                        </table>
                    </div>

                    <!-- قسم عرض كشف الراتب -->
                    <div id="payroll-annual" class="card" style="display: none;">
                        <div class="card-footer text-left">
                            <button type="button" class="btn print-btn mb-3" id="printSlipannualButton">
                                <i class="fas fa-print mr-2"></i> طباعة
                            </button>
                            <button type="button" class="btn download-btn mb-3" id="downloadPdfBtn">
                                <i class="fas fa-file-pdf mr-2"></i> حفظ PDF
                            </button>
                        </div>
                        <div class="card-body" id="salary-annual">
                            <!-- تفاصيل كشف الراتب ستظهر هنا -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
@endsection
