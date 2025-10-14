@extends('layouts.master')

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الأجور</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ إعدادات الغيابات الشهرية</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                    <h3 class="card-title mb-2 mb-sm-0">
                        <i class="fas fa-cog ml-2"></i>إعدادات الغيابات الشهرية
                    </h3>
                </div>
                <div class="card-body">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <!-- اختيار السنة (يمين) -->
                        <form method="GET" action="{{ route('monthly_absences.months') }}" class="mb-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <select name="year" class="form-control col-8" onchange="this.form.submit()">
                                    @for ($i = date('Y') - 2; $i <= date('Y') + 2; $i++)
                                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </form>

                        <!-- زر إضافة (يسار) -->
                        <button class="btn btn-success" data-toggle="modal" data-target="#addMonthModal">
                            <i class="fas fa-plus ml-2"></i> إضافة
                        </button>
                    </div>

                    <div class="table-responsive border rounded mt-4">
                        <table class="table key-buttons text-md-nowrap" id="usersTable">
                            <thead>
                                <tr class="text-center">
                                    <th style="width: 120px;">السنة</th>
                                    <th style="width: 220px;">الشهر</th>
                                    <th style="width: 140px;">الحالة</th>
                                    <th style="width: 260px;">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($monthSettings as $setting)
                                    <tr class="text-center">
                                        <td class="font-weight-bold">{{ $setting->year }}</td>
                                        <td>{{ $months[$setting->month] ?? $setting->month }}</td>
                                        <td>
                                            @if ($setting->is_open)
                                                <span class="badge badge-success px-3 py-2">
                                                    <i class="fas fa-unlock ml-1"></i> مفتوح
                                                </span>
                                            @else
                                                <span class="badge badge-danger px-3 py-2">
                                                    <i class="fas fa-lock ml-1"></i> مغلق
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="actions">
                                                <form action="{{ route('monthly_absences.toggle', $setting->id) }}"
                                                    method="POST" class="toggle-form" style="display:inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="btn btn-sm {{ $setting->is_open ? 'btn-warning' : 'btn-success' }}"
                                                        data-toggle="tooltip"
                                                        title="{{ $setting->is_open ? 'إغلاق الشهر' : 'فتح الشهر' }}">
                                                        <i
                                                            class="fas {{ $setting->is_open ? 'fa-lock' : 'fa-unlock' }}"></i>
                                                        {{ $setting->is_open ? 'إغلاق' : 'فتح' }}
                                                    </button>
                                                </form>

                                                <a href="{{ route('monthly_absences.index', ['year' => $setting->year, 'month' => $setting->month]) }}"
                                                    class="btn btn-sm btn-primary" data-toggle="tooltip">
                                                    <i class="fas fa-edit"></i> حجز
                                                </a>
                                                <button type="button" class="btn btn-info btn-sm show-details"
                                                    data-year="{{ $setting->year }}" data-month="{{ $setting->month }}"
                                                    data-name="{{ $months[$setting->month] ?? $setting->month }}"
                                                    data-url="{{ route('monthly_absences.details', ['year' => $setting->year, 'month' => $setting->month]) }}"
                                                    data-toggle="tooltip">
                                                    <i class="fas fa-eye"></i> تفصيل
                                                </button>
                                                <a href="{{ route('monthly_absences.export', ['year' => $setting->year, 'month' => $setting->month]) }}"
                                                    class="btn btn-danger">
                                                    <i class="fas fa-file-excel ml-2"></i> تصدير البيانات
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                @if ($monthSettings->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5">
                                            <i class="far fa-folder-open fa-2x d-block mb-2"></i>
                                            لا توجد بيانات
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Modal إضافة شهر -->
    <div class="modal fade" id="addMonthModal" tabindex="-1" role="dialog" aria-labelledby="addMonthModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('monthly_absences.storeSetting') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addMonthModalLabel">إضافة شهر جديد</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>السنة</label>
                            <select name="year" class="form-control" required>
                                @for ($i = date('Y') - 2; $i <= date('Y') + 2; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <label>الشهر</label>
                            <select name="month" class="form-control" required>
                                @foreach ($months as $num => $name)
                                    <option value="{{ $num }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-success">حفظ</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="detailsModalLabel">تفاصيل الغيابات</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="إغلاق">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="detailsContent" class="text-center">
                        <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                        <p>جاري تحميل البيانات...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            @if (session('success'))
                alertify.success('{{ session('success') }}');
            @endif

            @if (session('error'))
                alertify.error('{{ session('error') }}');
            @endif
        });
        $(document).ready(function() {
            $('.show-details').on('click', function() {
                let year = $(this).data('year');
                let month = $(this).data('month');
                let name = $(this).data('name');
                let url = $(this).data('url'); // استخدام الرابط الجاهز من الراوت

                $('#detailsModalLabel').text('تفاصيل الغيابات - ' + name + ' ' + year);
                $('#detailsContent').html(
                    '<div class="py-4"><i class="fas fa-spinner fa-spin fa-2x text-primary mb-2"></i><p class="mb-0">جاري تحميل البيانات...</p></div>'
                );
                $('#detailsModal').modal('show');

                $.get(url, function(data) {
                    $('#detailsContent').html(data);
                }).fail(function() {
                    $('#detailsContent').html(
                        '<p class="text-danger mb-0">حدث خطأ أثناء تحميل البيانات.</p>');
                });
            });
        });
    </script>
@endsection
