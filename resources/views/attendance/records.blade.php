@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .stats-card {
            border-radius: 10px;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .badge-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        .avatar {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .card-header h3,
        .card-header h4 {
            color: white !important;
        }

        .btn-icon {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .number-font {
            font-size: 2.5rem;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .btn-group {
                flex-direction: column;
                width: 100%;
            }

            .btn-group .btn {
                width: 100%;
                margin-bottom: 5px;
            }
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المستخدمين</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ الحضور والانصراف</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Header Card with Actions -->
    <div class="row ">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="bi bi-calendar-check ml-2"></i>
                        نظام الحضور والانصراف
                    </h3>
                    <div class="btn-group">
                        <a href="{{ route('attendance.scan.camera') }}" class="btn btn-success">
                            <i class="bi bi-camera-video"></i> مسح بالكاميرا
                        </a>
                        <a href="{{ route('attendance.scan.barcode') }}" class="btn btn-info">
                            <i class="bi bi-upc-scan"></i> مسح باركود
                        </a>
                        <a href="{{ route('attendance.reports') }}" class="btn btn-warning">
                            <i class="bi bi-file-earmark-bar-graph"></i> التقارير
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card overflow-hidden stats-card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="mt-2">
                                            <h6 class="text-white">إجمالي الحضور</h6>
                                            <h2 class="mb-0 number-font">{{ $statistics['total'] }}</h2>
                                        </div>
                                        <div class="ms-auto">
                                            <i class="bi bi-people" style="font-size: 3rem; opacity: 0.3;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card overflow-hidden stats-card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="mt-2">
                                            <h6 class="text-white">حاضر في الوقت</h6>
                                            <h2 class="mb-0 number-font">{{ $statistics['present'] }}</h2>
                                        </div>
                                        <div class="ms-auto">
                                            <i class="bi bi-check-circle" style="font-size: 3rem; opacity: 0.3;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card overflow-hidden stats-card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="mt-2">
                                            <h6 class="text-white">متأخر</h6>
                                            <h2 class="mb-0 number-font">{{ $statistics['late'] }}</h2>
                                        </div>
                                        <div class="ms-auto">
                                            <i class="bi bi-clock-history" style="font-size: 3rem; opacity: 0.3;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
                            <div class="card overflow-hidden stats-card bg-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="mt-2">
                                            <h6 class="text-white">غائب</h6>
                                            <h2 class="mb-0 number-font">{{ $statistics['absent'] }}</h2>
                                        </div>
                                        <div class="ms-auto">
                                            <i class="bi bi-x-circle" style="font-size: 3rem; opacity: 0.3;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters Card -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-light">
                                    <h4 class="card-title mb-0">
                                        <i class="bi bi-filter"></i> البحث والفلترة
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <form method="GET" action="{{ route('attendance.records') }}" class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label">التاريخ</label>
                                            <input type="date" name="date" class="form-control"
                                                value="{{ $date }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">بحث</label>
                                            <input type="text" name="search" class="form-control"
                                                placeholder="اسم الموظف أو رقم التسجيل" value="{{ request('search') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">الحالة</label>
                                            <select name="status" class="form-select">
                                                <option value="">الكل</option>
                                                <option value="present"
                                                    {{ request('status') == 'present' ? 'selected' : '' }}>
                                                    حاضر
                                                </option>
                                                <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>
                                                    متأخر
                                                </option>
                                                <option value="absent"
                                                    {{ request('status') == 'absent' ? 'selected' : '' }}>
                                                    غائب
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">&nbsp;</label>
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="bi bi-search"></i> بحث
                                                </button>
                                                <a href="{{ route('attendance.records') }}" class="btn btn-secondary">
                                                    <i class="bi bi-arrow-clockwise"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Records Table -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow-sm">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="card-title mb-0">
                                        <i class="bi bi-table"></i> سجلات الحضور -
                                        {{ \Carbon\Carbon::parse($date)->locale('ar')->isoFormat('dddd، D MMMM YYYY') }}
                                    </h4>
                                    <div class="btn-group">
                                        <a href="{{ route('attendance.export', ['date' => $date, 'format' => 'csv']) }}"
                                            class="btn btn-sm btn-success">
                                            <i class="bi bi-file-earmark-excel"></i> CSV
                                        </a>
                                        <a href="{{ route('attendance.export', ['date' => $date, 'format' => 'json']) }}"
                                            class="btn btn-sm btn-info">
                                            <i class="bi bi-file-earmark-code"></i> JSON
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if ($attendances->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped text-md-nowrap">
                                                <thead class="table-primary">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>الموظف</th>
                                                        <th>رقم التسجيل</th>
                                                        <th>وقت الدخول</th>
                                                        <th>وقت الخروج</th>
                                                        <th>ساعات العمل</th>
                                                        <th>الحالة</th>
                                                        <th>الجهاز</th>
                                                        <th>الإجراءات</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($attendances as $index => $attendance)
                                                        <tr>
                                                            <td>{{ $attendances->firstItem() + $index }}</td>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div
                                                                        class="avatar avatar-md me-2 bg-primary text-white rounded-circle">
                                                                        {{ substr($attendance->employee->NOMA ?? 'N', 0, 1) }}
                                                                    </div>
                                                                    <div>
                                                                        <strong>{{ $attendance->employee->NOMA ?? '' }}
                                                                            {{ $attendance->employee->PRENOMA ?? '' }}</strong>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td><span
                                                                    class="badge bg-secondary">{{ $attendance->employee->MATRI ?? 'N/A' }}</span>
                                                            </td>
                                                            <td>
                                                                @if ($attendance->check_in)
                                                                    <span class="badge bg-info">
                                                                        <i class="bi bi-box-arrow-in-right"></i>
                                                                        {{ \Carbon\Carbon::parse($attendance->check_in)->format('h:i A') }}
                                                                    </span>
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($attendance->check_out)
                                                                    <span class="badge bg-danger">
                                                                        <i class="bi bi-box-arrow-right"></i>
                                                                        {{ \Carbon\Carbon::parse($attendance->check_out)->format('h:i A') }}
                                                                    </span>
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($attendance->check_in && $attendance->check_out)
                                                                    @php
                                                                        $start = \Carbon\Carbon::parse(
                                                                            $attendance->check_in,
                                                                        );
                                                                        $end = \Carbon\Carbon::parse(
                                                                            $attendance->check_out,
                                                                        );
                                                                        $diff = $start->diff($end);
                                                                        $hours = $diff->h + $diff->days * 24;
                                                                        $minutes = $diff->i;
                                                                    @endphp
                                                                    <strong class="text-primary">
                                                                        <i class="bi bi-clock-history"></i>
                                                                        {{ $hours }}س {{ $minutes }}د
                                                                    </strong>
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($attendance->status == 'present')
                                                                    <span class="badge badge-status bg-success">
                                                                        <i class="bi bi-check-circle"></i> حاضر
                                                                    </span>
                                                                @elseif($attendance->status == 'late')
                                                                    <span class="badge badge-status bg-warning text-dark">
                                                                        <i class="bi bi-clock-history"></i> متأخر
                                                                    </span>
                                                                @else
                                                                    <span class="badge badge-status bg-danger">
                                                                        <i class="bi bi-x-circle"></i> غائب
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <small class="text-muted">
                                                                    <i class="bi bi-pc-display"></i>
                                                                    {{ $attendance->device ?? 'N/A' }}
                                                                </small>
                                                            </td>
                                                            <td>
                                                                <div class="btn-group">
                                                                    <a href="{{ route('attendance.records.show', $attendance->id) }}"
                                                                        class="btn btn-sm btn-info" title="عرض التفاصيل">
                                                                        <i class="bi bi-eye"></i>
                                                                    </a>
                                                                    <a href="{{ route('attendance.employee.records', $attendance->employee_id) }}"
                                                                        class="btn btn-sm btn-primary"
                                                                        title="سجلات الموظف">
                                                                        <i class="bi bi-person-lines-fill"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Pagination -->
                                        <div class="mt-4 d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-muted">
                                                    عرض {{ $attendances->firstItem() }} إلى {{ $attendances->lastItem() }}
                                                    من أصل {{ $attendances->total() }} سجل
                                                </small>
                                            </div>
                                            <div>
                                                {{ $attendances->appends(request()->query())->links() }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <i class="bi bi-inbox" style="font-size: 5rem; opacity: 0.2;"></i>
                                            <h4 class="mt-3 text-muted">لا توجد سجلات</h4>
                                            <p class="text-muted">لم يتم تسجيل أي حضور في هذا التاريخ</p>
                                            <a href="{{ route('attendance.scan.camera') }}" class="btn btn-primary mt-3">
                                                <i class="bi bi-camera-video"></i> ابدأ المسح الآن
                                            </a>
                                        </div>
                                    @endif
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-submit form on date change
            const dateInput = document.querySelector('input[name="date"]');
            if (dateInput) {
                dateInput.addEventListener('change', function() {
                    this.form.submit();
                });
            }

            // Auto-submit form on status change
            const statusSelect = document.querySelector('select[name="status"]');
            if (statusSelect) {
                statusSelect.addEventListener('change', function() {
                    this.form.submit();
                });
            }

            // Add tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Table row click
            document.querySelectorAll('tbody tr').forEach(row => {
                row.style.cursor = 'pointer';
                row.addEventListener('click', function(e) {
                    if (!e.target.closest('.btn-group')) {
                        const viewBtn = this.querySelector('.btn-info');
                        if (viewBtn) {
                            window.location.href = viewBtn.href;
                        }
                    }
                });
            });
        });
    </script>
@endsection
