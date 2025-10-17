@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
.report-card {
    border-radius: 15px;
    transition: all 0.3s;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.report-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

.icon-box {
    width: 70px;
    height: 70px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
}

.chart-container {
    position: relative;
    height: 300px;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 1.5rem;
    transition: all 0.3s;
}

.stat-card:hover {
    transform: translateY(-3px);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #00d4ff 100%) !important;
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%) !important;
}

.export-btn {
    border-radius: 12px;
    padding: 1.5rem;
    transition: all 0.3s;
    border: 2px solid transparent;
}

.export-btn:hover {
    transform: translateY(-5px);
    border-color: currentColor;
}

.insight-badge {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}

.progress-modern {
    height: 12px;
    border-radius: 10px;
    overflow: hidden;
    background: #e9ecef;
}

.progress-bar-animated {
    animation: progress-animation 1s ease-out;
}

@keyframes progress-animation {
    from { width: 0; }
}

/* Toast Notifications */
.toast-container {
    position: fixed;
    top: 20px;
    left: 20px;
    z-index: 9999;
    max-width: 400px;
}

.custom-toast {
    display: flex;
    align-items: center;
    background: white;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    margin-bottom: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    animation: slideInLeft 0.3s ease;
    border-left: 4px solid;
}

.custom-toast.toast-success { border-left-color: #28a745; }
.custom-toast.toast-info { border-left-color: #0dcaf0; }

@keyframes slideInLeft {
    from { transform: translateX(-100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

.custom-toast.hiding {
    animation: slideOutLeft 0.3s ease;
}

@keyframes slideOutLeft {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(-100%); opacity: 0; }
}

@media print {
    .no-print, .btn, .breadcrumb-header, .card-header {
        display: none !important;
    }
    .card {
        box-shadow: none !important;
        page-break-inside: avoid;
    }
}
</style>
@endsection

@section('page-header')
<div class="breadcrumb-header justify-content-between no-print">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">التقارير والإحصائيات</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ الحضور والانصراف</span>
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
<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<!-- Date Range Selector -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card report-card">
            <div class="card-header bg-gradient-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-range"></i> اختيار الفترة الزمنية
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('attendance.reports') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-calendar-event text-primary"></i> من تاريخ
                        </label>
                        <input type="date" name="start_date" class="form-control form-control-lg" value="{{ $startDate }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-calendar-check text-success"></i> إلى تاريخ
                        </label>
                        <input type="date" name="end_date" class="form-control form-control-lg" value="{{ $endDate }}" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-search"></i> عرض التقرير
                        </button>
                    </div>
                </form>
                <div class="mt-3 text-center">
                    <small class="text-muted">
                        <i class="bi bi-info-circle"></i>
                        التقرير الحالي من <strong>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</strong> 
                        إلى <strong>{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</strong>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Statistics -->
<div class="row mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="card report-card bg-gradient-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">عدد الأيام</h6>
                        <h2 class="mb-0 fw-bold">{{ $statistics['total_days'] }}</h2>
                        <small class="text-white-50">يوم عمل</small>
                    </div>
                    <div class="icon-box bg-white bg-opacity-25">
                        <i class="bi bi-calendar3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="card report-card bg-gradient-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">إجمالي الموظفين</h6>
                        <h2 class="mb-0 fw-bold">{{ $statistics['total_employees'] }}</h2>
                        <small class="text-white-50">موظف</small>
                    </div>
                    <div class="icon-box bg-white bg-opacity-25">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="card report-card bg-gradient-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">سجلات الحضور</h6>
                        <h2 class="mb-0 fw-bold">{{ $statistics['total_attendances'] }}</h2>
                        <small class="text-white-50">سجل</small>
                    </div>
                    <div class="icon-box bg-white bg-opacity-25">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 mb-3">
        <div class="card report-card bg-gradient-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-2">معدل الحضور</h6>
                        @php
                            $attendanceRate = $statistics['total_attendances'] > 0 
                                ? (($statistics['present_count'] + $statistics['late_count']) / $statistics['total_attendances']) * 100 
                                : 0;
                        @endphp
                        <h2 class="mb-0 fw-bold">{{ number_format($attendanceRate, 1) }}%</h2>
                        <small class="text-white-50">نسبة الحضور</small>
                    </div>
                    <div class="icon-box bg-white bg-opacity-25">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mb-4">
    <!-- Bar Chart -->
    <div class="col-lg-8 mb-3">
        <div class="card report-card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-bar-chart-line"></i> توزيع حالات الحضور
                </h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="attendanceChart"></canvas>
                </div>
                
                <div class="row mt-4 g-3">
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded-3">
                            <div class="icon-box bg-success text-white mx-auto mb-2" style="width: 50px; height: 50px; font-size: 1.5rem;">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <h4 class="mb-0 text-success">{{ $statistics['present_count'] }}</h4>
                            <small class="text-muted">حاضر في الوقت</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded-3">
                            <div class="icon-box bg-warning text-white mx-auto mb-2" style="width: 50px; height: 50px; font-size: 1.5rem;">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <h4 class="mb-0 text-warning">{{ $statistics['late_count'] }}</h4>
                            <small class="text-muted">متأخر</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded-3">
                            <div class="icon-box bg-danger text-white mx-auto mb-2" style="width: 50px; height: 50px; font-size: 1.5rem;">
                                <i class="bi bi-x-circle"></i>
                            </div>
                            <h4 class="mb-0 text-danger">{{ $statistics['absent_count'] }}</h4>
                            <small class="text-muted">غائب</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pie Chart -->
    <div class="col-lg-4 mb-3">
        <div class="card report-card h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="bi bi-pie-chart"></i> التوزيع النسبي
                </h5>
            </div>
            <div class="card-body">
                <div style="height: 200px;">
                    <canvas id="pieChart"></canvas>
                </div>
                
                @php
                    $total = $statistics['present_count'] + $statistics['late_count'] + $statistics['absent_count'];
                @endphp
                
                <div class="mt-4">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-circle-fill text-success"></i> حاضر</span>
                            <strong class="text-success">{{ $total > 0 ? number_format(($statistics['present_count'] / $total) * 100, 1) : 0 }}%</strong>
                        </div>
                        <div class="progress progress-modern">
                            <div class="progress-bar bg-success progress-bar-animated" style="width: {{ $total > 0 ? ($statistics['present_count'] / $total) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-circle-fill text-warning"></i> متأخر</span>
                            <strong class="text-warning">{{ $total > 0 ? number_format(($statistics['late_count'] / $total) * 100, 1) : 0 }}%</strong>
                        </div>
                        <div class="progress progress-modern">
                            <div class="progress-bar bg-warning progress-bar-animated" style="width: {{ $total > 0 ? ($statistics['late_count'] / $total) * 100 : 0 }}%"></div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-circle-fill text-danger"></i> غائب</span>
                            <strong class="text-danger">{{ $total > 0 ? number_format(($statistics['absent_count'] / $total) * 100, 1) : 0 }}%</strong>
                        </div>
                        <div class="progress progress-modern">
                            <div class="progress-bar bg-danger progress-bar-animated" style="width: {{ $total > 0 ? ($statistics['absent_count'] / $total) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Insights -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card report-card border-primary">
            <div class="card-header bg-gradient-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-lightbulb"></i> رؤى وتحليلات سريعة
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-success mb-3">
                            <i class="bi bi-trophy-fill"></i> نقاط القوة
                        </h6>
                        <ul class="list-unstyled">
                            <li class="mb-3 d-flex align-items-center">
                                <span class="insight-badge bg-success text-white me-3">
                                    <i class="bi bi-check-lg"></i>
                                </span>
                                <div>
                                    <strong>معدل الحضور الممتاز</strong>
                                    <br>
                                    <small class="text-muted">{{ number_format($attendanceRate, 1) }}% من الموظفين ملتزمون</small>
                                </div>
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <span class="insight-badge bg-info text-white me-3">
                                    <i class="bi bi-graph-up"></i>
                                </span>
                                <div>
                                    <strong>إجمالي السجلات</strong>
                                    <br>
                                    <small class="text-muted">{{ $statistics['total_attendances'] }} سجل مسجل</small>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-warning mb-3">
                            <i class="bi bi-exclamation-triangle-fill"></i> نقاط تحتاج متابعة
                        </h6>
                        <ul class="list-unstyled">
                            @if($statistics['late_count'] > 0)
                            <li class="mb-3 d-flex align-items-center">
                                <span class="insight-badge bg-warning text-white me-3">
                                    <i class="bi bi-clock"></i>
                                </span>
                                <div>
                                    <strong>حالات التأخير</strong>
                                    <br>
                                    <small class="text-muted">{{ $statistics['late_count'] }} حالة تأخير تحتاج معالجة</small>
                                </div>
                            </li>
                            @endif
                            @if($statistics['absent_count'] > 0)
                            <li class="mb-3 d-flex align-items-center">
                                <span class="insight-badge bg-danger text-white me-3">
                                    <i class="bi bi-x-lg"></i>
                                </span>
                                <div>
                                    <strong>حالات الغياب</strong>
                                    <br>
                                    <small class="text-muted">{{ $statistics['absent_count'] }} حالة غياب مسجلة</small>
                                </div>
                            </li>
                            @endif
                            @if($statistics['late_count'] == 0 && $statistics['absent_count'] == 0)
                            <li class="mb-3 d-flex align-items-center">
                                <span class="insight-badge bg-success text-white me-3">
                                    <i class="bi bi-emoji-smile"></i>
                                </span>
                                <div>
                                    <strong>أداء ممتاز!</strong>
                                    <br>
                                    <small class="text-muted">لا توجد مشاكل في الحضور</small>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Options -->
<div class="row no-print">
    <div class="col-lg-12">
        <div class="card report-card">
            <div class="card-header bg-gradient-info text-white">
                <h5 class="mb-0">
                    <i class="bi bi-download"></i> تصدير ومشاركة التقارير
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">
                    <i class="bi bi-info-circle"></i>
                    اختر صيغة التقرير المناسبة للتصدير أو المشاركة
                </p>
                <div class="row g-3">
                    <div class="col-md-3">
                        <a href="{{ route('attendance.export', ['start_date' => $startDate, 'end_date' => $endDate, 'format' => 'csv']) }}" 
                           class="btn btn-success export-btn w-100">
                            <i class="bi bi-file-earmark-excel fs-2 d-block mb-2"></i>
                            <strong>Excel / CSV</strong>
                            <br>
                            <small class="opacity-75">للتحليل المتقدم</small>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('attendance.export', ['start_date' => $startDate, 'end_date' => $endDate, 'format' => 'json']) }}" 
                           class="btn btn-primary export-btn w-100">
                            <i class="bi bi-file-earmark-code fs-2 d-block mb-2"></i>
                            <strong>JSON</strong>
                            <br>
                            <small class="opacity-75">للمطورين</small>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <button onclick="window.print()" class="btn btn-secondary export-btn w-100">
                            <i class="bi bi-printer fs-2 d-block mb-2"></i>
                            <strong>طباعة</strong>
                            <br>
                            <small class="opacity-75">نسخة ورقية</small>
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button onclick="shareReport()" class="btn btn-info export-btn w-100">
                            <i class="bi bi-share fs-2 d-block mb-2"></i>
                            <strong>مشاركة</strong>
                            <br>
                            <small class="opacity-75">إرسال الرابط</small>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Toast function
function showToast(message, type = 'info') {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    const iconMap = {
        success: 'bi-check-circle-fill text-success',
        info: 'bi-info-circle-fill text-info'
    };

    toast.className = `custom-toast toast-${type}`;
    toast.innerHTML = `
        <i class="bi ${iconMap[type]} me-3 fs-4"></i>
        <div class="flex-grow-1">${message}</div>
        <button class="btn-close" onclick="this.parentElement.remove()"></button>
    `;

    container.appendChild(toast);
    setTimeout(() => {
        toast.classList.add('hiding');
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

// Bar Chart - Attendance Distribution
const ctx = document.getElementById('attendanceChart');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['حاضر', 'متأخر', 'غائب'],
            datasets: [{
                label: 'عدد الحالات',
                data: [
                    {{ $statistics['present_count'] }},
                    {{ $statistics['late_count'] }},
                    {{ $statistics['absent_count'] }}
                ],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.8)',
                    'rgba(255, 193, 7, 0.8)',
                    'rgba(220, 53, 69, 0.8)'
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 2,
                borderRadius: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed.y + ' حالة';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    },
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

// Pie Chart - Distribution
const pieCtx = document.getElementById('pieChart');
if (pieCtx) {
    new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: ['حاضر', 'متأخر', 'غائب'],
            datasets: [{
                data: [
                    {{ $statistics['present_count'] }},
                    {{ $statistics['late_count'] }},
                    {{ $statistics['absent_count'] }}
                ],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.9)',
                    'rgba(255, 193, 7, 0.9)',
                    'rgba(220, 53, 69, 0.9)'
                ],
                borderWidth: 3,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });
}

// Share Report
function shareReport() {
    const url = window.location.href;
    const title = 'تقرير الحضور والانصراف';
    const text = 'تقرير الحضور من {{ \Carbon\Carbon::parse($startDate)->format("d/m/Y") }} إلى {{ \Carbon\Carbon::parse($endDate)->format("d/m/Y") }}';
    
    if (navigator.share) {
        navigator.share({
            title: title,
            text: text,
            url: url
        }).then(() => {
            showToast('تم مشاركة التقرير بنجاح', 'success');
        }).catch((err) => {
            console.log('Error sharing:', err);
            copyToClipboard(url);
        });
    } else {
        copyToClipboard(url);
    }
}

// Copy to Clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('تم نسخ رابط التقرير إلى الحافظة', 'success');
    }).catch(() => {
        showToast('تعذر النسخ، جرب متصفح آخر', 'info');
    });
}

// Print handling
window.addEventListener('beforeprint', function() {
    document.querySelectorAll('.no-print').forEach(el => {
        el.style.display = 'none';
    });
});

window.addEventListener('afterprint', function() {
    document.querySelectorAll('.no-print').forEach(el => {
        el.style.display = '';
    });
});
</script>
@endsection