@extends('layouts.master')
@section(section: 'css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
.avatar-circle {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.avatar-circle i {
    font-size: 3rem;
    color: white;
}

.info-box {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
}

.info-box i {
    font-size: 1.5rem;
}

.time-card {
    padding: 20px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.time-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    position: relative;
    padding-right: 40px;
    margin-bottom: 30px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    right: 0;
    top: 5px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 0 0 3px #f8f9fa;
}

.timeline-item::before {
    content: '';
    position: absolute;
    right: 9px;
    top: 25px;
    width: 2px;
    height: calc(100% + 5px);
    background: #e9ecef;
}

.timeline-item:last-child::before {
    display: none;
}

.timeline-content {
    background: white;
    padding: 15px;
    border-radius: 10px;
    border: 1px solid #e9ecef;
}

@media print {
    .btn, .card-header {
        display: none !important;
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

    <!-- Header -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="bi bi-person-badge"></i>
                                تفاصيل سجل الحضور
                            </h4>
                            <p class="text-muted mb-0">معلومات كاملة عن سجل الحضور والانصراف</p>
                        </div>
                        <a href="{{ route('attendance.records') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-right"></i> رجوع
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- معلومات الموظف -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="avatar-circle mb-3">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <h5 class="mb-1">{{ $attendance->employee->NOMA }} {{ $attendance->employee->PRENOMA }}</h5>
                    <p class="text-muted mb-3">
                        <i class="bi bi-credit-card"></i> 
                        {{ $attendance->employee->MATRI }}
                    </p>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('attendance.employee.records', $attendance->employee->id) }}" 
                           class="btn btn-primary">
                            <i class="bi bi-list-ul"></i> جميع السجلات
                        </a>
                    </div>

                    <hr class="my-3">

                    <div class="text-start">
                        <p class="mb-2">
                            <i class="bi bi-envelope text-primary"></i>
                            <strong>البريد:</strong>
                            <span class="text-muted">{{ $attendance->employee->email ?? 'غير متوفر' }}</span>
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-telephone text-primary"></i>
                            <strong>الهاتف:</strong>
                            <span class="text-muted">{{ $attendance->employee->phone ?? 'غير متوفر' }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- تفاصيل الحضور -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-calendar-event"></i>
                        معلومات الحضور والانصراف
                    </h5>
                </div>
                <div class="card-body">
                    <!-- التاريخ والحالة -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-calendar3 text-primary"></i>
                                <div>
                                    <small class="text-muted d-block">التاريخ</small>
                                    <strong>{{ \Carbon\Carbon::parse($attendance->date)->locale('ar')->isoFormat('dddd، D MMMM YYYY') }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi bi-flag text-primary"></i>
                                <div>
                                    <small class="text-muted d-block">الحالة</small>
                                    @if($attendance->status == 'present')
                                        <span class="badge bg-success">حاضر</span>
                                    @elseif($attendance->status == 'late')
                                        <span class="badge bg-warning">متأخر</span>
                                    @else
                                        <span class="badge bg-danger">غائب</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الدخول والخروج -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="time-card bg-light">
                                <div class="time-icon bg-success">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">وقت الدخول</small>
                                    <h4 class="mb-0">
                                        {{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('h:i A') : 'لم يسجل' }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="time-card bg-light">
                                <div class="time-icon bg-danger">
                                    <i class="bi bi-box-arrow-right"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">وقت الخروج</small>
                                    <h4 class="mb-0">
                                        {{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('h:i A') : 'لم يسجل' }}
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ساعات العمل -->
                    @if($attendance->check_in && $attendance->check_out)
                    <div class="alert alert-info border-0">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-clock-history fs-4 me-3"></i>
                            <div>
                                <strong>إجمالي ساعات العمل:</strong>
                                @php
                                    $checkIn = \Carbon\Carbon::parse($attendance->check_in);
                                    $checkOut = \Carbon\Carbon::parse($attendance->check_out);
                                    $duration = $checkIn->diff($checkOut);
                                @endphp
                                <span class="fs-5">
                                    {{ $duration->h }} ساعة و {{ $duration->i }} دقيقة
                                </span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- معلومات الجهاز -->
                    @if($attendance->device)
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="bi bi-pc-display"></i>
                            تم التسجيل من: <strong>{{ $attendance->device }}</strong>
                        </small>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i>
                        سجل الأحداث
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @if($attendance->check_in)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1">تسجيل الدخول</h6>
                                        <p class="text-muted mb-0">
                                            وصل الموظف وسجل حضوره
                                        </p>
                                    </div>
                                    <span class="badge bg-success">
                                        {{ \Carbon\Carbon::parse($attendance->check_in)->format('h:i A') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($attendance->check_out)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-danger"></div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1">تسجيل الخروج</h6>
                                        <p class="text-muted mb-0">
                                            غادر الموظف وسجل انصرافه
                                        </p>
                                    </div>
                                    <span class="badge bg-danger">
                                        {{ \Carbon\Carbon::parse($attendance->check_out)->format('h:i A') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if(!$attendance->check_in && !$attendance->check_out)
                        <div class="text-center py-4">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="text-muted mt-2">لا توجد أحداث مسجلة</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- معلومات إضافية -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <i class="bi bi-calendar-plus text-primary fs-3"></i>
                            <p class="mb-0 mt-2"><strong>تاريخ الإنشاء</strong></p>
                            <small class="text-muted">
                                {{ $attendance->created_at->locale('ar')->diffForHumans() }}
                            </small>
                        </div>
                        <div class="col-md-3">
                            <i class="bi bi-arrow-repeat text-warning fs-3"></i>
                            <p class="mb-0 mt-2"><strong>آخر تحديث</strong></p>
                            <small class="text-muted">
                                {{ $attendance->updated_at->locale('ar')->diffForHumans() }}
                            </small>
                        </div>
                        <div class="col-md-3">
                            <i class="bi bi-hash text-info fs-3"></i>
                            <p class="mb-0 mt-2"><strong>رقم السجل</strong></p>
                            <small class="text-muted">#{{ $attendance->id }}</small>
                        </div>
                        <div class="col-md-3">
                            <i class="bi bi-database text-success fs-3"></i>
                            <p class="mb-0 mt-2"><strong>رقم الموظف</strong></p>
                            <small class="text-muted">#{{ $attendance->employee_id }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection