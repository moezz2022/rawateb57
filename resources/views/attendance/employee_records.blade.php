@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
.employee-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.stat-box {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.stat-box:hover {
    transform: translateY(-5px);
}

.calendar-badge {
    display: inline-block;
    width: 40px;
    height: 40px;
    line-height: 40px;
    border-radius: 50%;
    text-align: center;
    font-weight: bold;
    font-size: 0.9rem;
}

.day-present { 
    background: #d4edda; 
    color: #155724;
    border: 2px solid #28a745;
}

.day-late { 
    background: #fff3cd; 
    color: #856404;
    border: 2px solid #ffc107;
}

.day-absent { 
    background: #f8d7da; 
    color: #721c24;
    border: 2px solid #dc3545;
}

.avatar-xxl {
    width: 100px;
    height: 100px;
    font-size: 3rem;
    line-height: 100px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.table-hover tbody tr {
    transition: all 0.2s ease;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
    cursor: pointer;
}

.progress {
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    font-weight: bold;
}
</style>
@endsection

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">سجلات الموظف</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ $employee->NOMA }} {{ $employee->PRENOMA }}</span>
        </div>
    </div>
    <div class="d-flex my-xl-auto right-content">
        <a href="{{ route('attendance.records') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-right"></i> رجوع
        </a>
    </div>
</div>
@endsection

@section('content')

<!-- Employee Header Card -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="employee-header">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="avatar-xxl bg-white text-primary rounded-circle">
                        {{ substr($employee->NOMA ?? 'N', 0, 1) }}
                    </div>
                </div>
                <div class="col">
                    <h2 class="mb-1">{{ $employee->NOMA }} {{ $employee->PRENOMA }}</h2>
                    <p class="mb-2 opacity-75">
                        <i class="bi bi-person-badge"></i> رقم التسجيل: <strong>{{ $employee->MATRI }}</strong>
                        @if($employee->barcode)
                            <span class="mx-2">|</span>
                            <i class="bi bi-upc-scan"></i> الباركود: <strong>{{ $employee->barcode }}</strong>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics -->
<div class="row mb-4">
    @php
        $totalDays = $attendances->total();
        $presentCount = $attendances->where('status', 'present')->count();
        $lateCount = $attendances->where('status', 'late')->count();
        $absentCount = $attendances->where('status', 'absent')->count();
        $totalWorkHours = 0;
        
        foreach($attendances as $att) {
            if($att->check_in && $att->check_out) {
                $start = \Carbon\Carbon::parse($att->check_in);
                $end = \Carbon\Carbon::parse($att->check_out);
                $totalWorkHours += $start->diffInMinutes($end) / 60;
            }
        }
    @endphp

    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-box">
            <i class="bi bi-calendar-check text-primary" style="font-size: 2.5rem;"></i>
            <h3 class="mt-2 mb-0">{{ $totalDays }}</h3>
            <p class="text-muted mb-0">إجمالي الأيام</p>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-box">
            <i class="bi bi-check-circle text-success" style="font-size: 2.5rem;"></i>
            <h3 class="mt-2 mb-0">{{ $presentCount }}</h3>
            <p class="text-muted mb-0">حاضر في الوقت</p>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-box">
            <i class="bi bi-clock-history text-warning" style="font-size: 2.5rem;"></i>
            <h3 class="mt-2 mb-0">{{ $lateCount }}</h3>
            <p class="text-muted mb-0">متأخر</p>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-box">
            <i class="bi bi-stopwatch text-info" style="font-size: 2.5rem;"></i>
            <h3 class="mt-2 mb-0">{{ number_format($totalWorkHours, 1) }}</h3>
            <p class="text-muted mb-0">ساعات العمل</p>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-funnel"></i> تصفية حسب الفترة</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('attendance.employee.records', $employee->id) }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">من تاريخ</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">إلى تاريخ</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> بحث
                            </button>
                            <button type="button" class="btn btn-success" onclick="exportData()" title="تصدير البيانات">
                                <i class="bi bi-download"></i>
                            </button>
                            <button type="button" class="btn btn-info" onclick="window.print()" title="طباعة">
                                <i class="bi bi-printer"></i>
                            </button>
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
                    <i class="bi bi-table"></i> سجلات الحضور
                    <small class="text-danger">({{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }})</small>
                </h4>
                <span class="badge bg-primary">{{ $attendances->total() }} سجل</span>
            </div>
            <div class="card-body">
                @if($attendances->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="table-primary">
                                <tr>
                                    <th>#</th>
                                    <th>التاريخ</th>
                                    <th>اليوم</th>
                                    <th>وقت الدخول</th>
                                    <th>وقت الخروج</th>
                                    <th>ساعات العمل</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $index => $attendance)
                                    <tr>
                                        <td>{{ $attendances->firstItem() + $index }}</td>
                                        <td>
                                            <strong>{{ \Carbon\Carbon::parse($attendance->date)->format('Y-m-d') }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($attendance->date)->locale('ar')->isoFormat('dddd') }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="calendar-badge 
                                                @if($attendance->status == 'present') day-present
                                                @elseif($attendance->status == 'late') day-late
                                                @else day-absent
                                                @endif">
                                                {{ \Carbon\Carbon::parse($attendance->date)->format('d') }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($attendance->check_in)
                                                <span class="badge bg-success">
                                                    <i class="bi bi-box-arrow-in-right"></i>
                                                    {{ \Carbon\Carbon::parse($attendance->check_in)->format('h:i A') }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attendance->check_out)
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-box-arrow-right"></i>
                                                    {{ \Carbon\Carbon::parse($attendance->check_out)->format('h:i A') }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($attendance->check_in && $attendance->check_out)
                                                @php
                                                    $start = \Carbon\Carbon::parse($attendance->check_in);
                                                    $end = \Carbon\Carbon::parse($attendance->check_out);
                                                    $diff = $start->diff($end);
                                                    $hours = $diff->h + ($diff->days * 24);
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
                                            @if($attendance->status == 'present')
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle"></i> حاضر
                                                </span>
                                            @elseif($attendance->status == 'late')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-clock-history"></i> متأخر
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle"></i> غائب
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('attendance.records.show', $attendance->id) }}" 
                                               class="btn btn-sm btn-info"
                                               title="عرض التفاصيل">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="5" class="text-end"><strong>المجموع:</strong></td>
                                    <td><strong class="text-primary">{{ number_format($totalWorkHours, 1) }} ساعة</strong></td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
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
                        <p class="text-muted">لم يتم العثور على سجلات حضور في هذه الفترة</p>
                        <a href="{{ route('attendance.scan.camera') }}" class="btn btn-primary mt-3">
                            <i class="bi bi-camera-video"></i> تسجيل حضور جديد
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Summary Card -->
@if($attendances->count() > 0)
<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card border-primary shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-clipboard-data"></i> ملخص الأداء</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <h6 class="text-muted mb-2">معدل الحضور</h6>
                        @php
                            $attendanceRate = $totalDays > 0 ? (($presentCount + $lateCount) / $totalDays) * 100 : 0;
                        @endphp
                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $attendanceRate }}%">
                                {{ number_format($attendanceRate, 1) }}%
                            </div>
                        </div>
                        <small class="text-muted mt-1 d-block">
                            {{ $presentCount + $lateCount }} من {{ $totalDays }} يوم
                        </small>
                    </div>
                    <div class="col-md-3 mb-3">
                        <h6 class="text-muted mb-2">معدل الالتزام بالوقت</h6>
                        @php
                            $punctualityRate = $totalDays > 0 ? ($presentCount / $totalDays) * 100 : 0;
                        @endphp
                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $punctualityRate }}%">
                                {{ number_format($punctualityRate, 1) }}%
                            </div>
                        </div>
                        <small class="text-muted mt-1 d-block">
                            {{ $presentCount }} حضور في الوقت
                        </small>
                    </div>
                    <div class="col-md-3 mb-3">
                        <h6 class="text-muted mb-2">متوسط ساعات العمل/يوم</h6>
                        @php
                            $avgHours = $totalDays > 0 ? $totalWorkHours / $totalDays : 0;
                        @endphp
                        <h3 class="text-primary mb-0">
                            <i class="bi bi-clock"></i>
                            {{ number_format($avgHours, 1) }} ساعة
                        </h3>
                        <small class="text-muted">
                            من إجمالي {{ number_format($totalWorkHours, 1) }} ساعة
                        </small>
                    </div>
                    <div class="col-md-3 mb-3">
                        <h6 class="text-muted mb-2">التقييم العام</h6>
                        @php
                            $rating = ($attendanceRate + $punctualityRate) / 2;
                            if($rating >= 90) {
                                $ratingText = 'ممتاز';
                                $ratingColor = 'success';
                                $ratingIcon = 'emoji-smile';
                            } elseif($rating >= 75) {
                                $ratingText = 'جيد جداً';
                                $ratingColor = 'primary';
                                $ratingIcon = 'emoji-laughing';
                            } elseif($rating >= 60) {
                                $ratingText = 'جيد';
                                $ratingColor = 'info';
                                $ratingIcon = 'emoji-neutral';
                            } else {
                                $ratingText = 'يحتاج تحسين';
                                $ratingColor = 'warning';
                                $ratingIcon = 'emoji-frown';
                            }
                        @endphp
                        <h3>
                            <span class="badge bg-{{ $ratingColor }}">
                                <i class="bi bi-{{ $ratingIcon }}"></i>
                                {{ $ratingText }}
                            </span>
                        </h3>
                        <small class="text-muted">
                            بناءً على {{ number_format($rating, 1) }}%
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('js')
<script>
function exportData() {
    const employeeId = {{ $employee->id }};
    const startDate = '{{ $startDate }}';
    const endDate = '{{ $endDate }}';
    const url = `{{ route('attendance.export') }}?employee_id=${employeeId}&start_date=${startDate}&end_date=${endDate}&format=csv`;
    window.location.href = url;
}

// Table row click
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('tbody tr').forEach(row => {
        row.addEventListener('click', function(e) {
            if (!e.target.closest('.btn')) {
                const viewBtn = this.querySelector('.btn-info');
                if (viewBtn) {
                    window.location.href = viewBtn.href;
                }
            }
        });
    });
});
</script>

<style>
@media print {
    .btn, .card-header, .no-print {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endsection