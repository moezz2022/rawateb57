@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    .scan-container {
        min-height: 400px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 3rem;
        color: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }

    .scan-input-wrapper {
        position: relative;
        margin: 2rem 0;
    }

    .scan-input {
        font-size: 1.5rem;
        padding: 1.5rem;
        text-align: center;
        border: 3px solid rgba(255,255,255,0.3);
        background: rgba(255,255,255,0.1);
        color: white;
        border-radius: 15px;
        transition: all 0.3s ease;
    }

    .scan-input:focus {
        background: rgba(255,255,255,0.2);
        border-color: white;
        box-shadow: 0 0 20px rgba(255,255,255,0.3);
        outline: none;
    }

    .scan-input::placeholder {
        color: rgba(255,255,255,0.6);
    }

    .scan-icon {
        font-size: 5rem;
        animation: pulse 2s infinite;
        text-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.1); opacity: 0.8; }
    }

    .stats-box {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
        height: 100%;
    }

    .stats-box:hover {
        transform: translateY(-5px);
    }

    .scan-history {
        max-height: 400px;
        overflow-y: auto;
        background: white;
        border-radius: 15px;
        padding: 1rem;
    }

    .scan-history-item {
        padding: 0.75rem;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background 0.2s ease;
    }

    .scan-history-item:hover {
        background: #f8f9fa;
    }

    .scan-history-item:last-child {
        border-bottom: none;
    }

    .success-animation {
        animation: successPulse 0.5s ease;
    }

    @keyframes successPulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); background: rgba(212, 237, 218, 0.3); }
        100% { transform: scale(1); }
    }

    .error-animation {
        animation: errorShake 0.5s ease;
    }

    @keyframes errorShake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }

    .status-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-left: 8px;
        animation: blink 1s infinite;
    }

    @keyframes blink {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.3; }
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

    .custom-toast.toast-success {
        border-left-color: #28a745;
    }

    .custom-toast.toast-error {
        border-left-color: #dc3545;
    }

    .custom-toast.toast-info {
        border-left-color: #0dcaf0;
    }

    .custom-toast.toast-warning {
        border-left-color: #ffc107;
    }

    @keyframes slideInLeft {
        from {
            transform: translateX(-100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutLeft {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(-100%);
            opacity: 0;
        }
    }

    .custom-toast.hiding {
        animation: slideOutLeft 0.3s ease;
    }

    .toast-icon {
        font-size: 1.5rem;
        margin-left: 1rem;
    }

    .toast-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        cursor: pointer;
        opacity: 0.5;
        transition: opacity 0.2s;
        margin-right: 0.5rem;
    }

    .toast-close:hover {
        opacity: 1;
    }
</style>
@endsection

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">المستخدمين</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ ماسح الباركود</span>
        </div>
    </div>
</div>
@endsection

@section('content')
<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<div class="row">
    <!-- Right Side: Scanner & Stats -->
    <div class="col-lg-7 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header d-flex justify-content-between align-items-center bg-light">
                <h3 class="card-title mb-0 d-flex align-items-center">
                    <i class="bi bi-upc-scan me-2"></i>
                    ماسح الباركود 
                </h3>
                 <div class="btn-group">
                        <a href="{{ route('attendance.scan.camera') }}" class="btn btn-danger btn-sm">
                            <i class="bi bi-upc-scan"></i> ماسح الباركود بالكاميرا
                        </a>
                        <a href="{{ route('attendance.records') }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-list-ul"></i> عرض السجلات
                        </a>
                        <a href="{{ route('attendance.reports') }}" class="btn btn-success btn-sm">
                            <i class="bi bi-file-earmark-bar-graph"></i> التقارير
                        </a>
                    </div>
            </div>

            <div class="card-body p-0">
                <!-- Scan Container -->
                <div class="scan-container">
                    <div class="text-center mb-4">
                        <i class="bi bi-upc-scan scan-icon" id="scanIcon"></i>
                        <h4 class="mt-3">جاهز للمسح</h4>
                        <p class="opacity-75">
                            <span class="status-indicator bg-success"></span>
                            النظام يعمل - امسح الباركود الآن
                        </p>
                    </div>

                    <form id="scanForm" action="{{ route('attendance.scan.post') }}" method="POST">
                        @csrf
                        <div class="scan-input-wrapper">
                            <input 
                                autofocus 
                                autocomplete="off" 
                                type="text" 
                                name="code" 
                                id="code"
                                class="form-control scan-input" 
                                placeholder="مرر القارئ هنا..." 
                                required 
                                minlength="1"
                            />
                            <div class="text-center mt-3">
                                <small class="opacity-75">
                                    <i class="bi bi-lightning-fill"></i>
                                    التسجيل تلقائي عند المسح
                                </small>
                            </div>
                        </div>

                        <button class="btn btn-light btn-lg w-100 fw-bold" type="submit" id="submitBtn">
                            <i class="bi bi-check-lg"></i> تسجيل الحضور يدوياً
                        </button>
                    </form>

                    <!-- Quick Info -->
                    <div class="row mt-4 text-center">
                        <div class="col-4">
                            <i class="bi bi-lightning-charge fs-3"></i>
                            <p class="small mb-0 mt-2 opacity-75">سريع</p>
                        </div>
                        <div class="col-4">
                            <i class="bi bi-shield-check fs-3"></i>
                            <p class="small mb-0 mt-2 opacity-75">آمن</p>
                        </div>
                        <div class="col-4">
                            <i class="bi bi-clock-history fs-3"></i>
                            <p class="small mb-0 mt-2 opacity-75">دقيق</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Row -->
        <div class="row mt-4">
            <div class="col-md-4 mb-3">
                <div class="stats-box text-center">
                    <i class="bi bi-check-circle text-success fs-2"></i>
                    <h3 class="mt-2 mb-0" id="successCount">0</h3>
                    <p class="text-muted mb-0">عمليات ناجحة</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stats-box text-center">
                    <i class="bi bi-x-circle text-danger fs-2"></i>
                    <h3 class="mt-2 mb-0" id="errorCount">0</h3>
                    <p class="text-muted mb-0">محاولات فاشلة</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stats-box text-center">
                    <i class="bi bi-graph-up text-primary fs-2"></i>
                    <h3 class="mt-2 mb-0" id="totalCount">0</h3>
                    <p class="text-muted mb-0">إجمالي العمليات</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Left Side: Scan History -->
    <div class="col-lg-5 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history"></i> سجل المسح
                </h5>
                <button class="btn btn-sm btn-outline-danger" onclick="clearHistory()">
                    <i class="bi bi-trash"></i> مسح
                </button>
            </div>
            <div class="card-body p-0">
                <div class="scan-history" id="scanHistory">
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-1"></i>
                        <p class="mt-2">لا توجد عمليات مسح بعد</p>
                        <small>سيتم عرض آخر 10 عمليات هنا</small>
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
    const input = document.getElementById('code');
    const form = document.getElementById('scanForm');
    const submitBtn = document.getElementById('submitBtn');
    const scanIcon = document.getElementById('scanIcon');
    const scanHistory = document.getElementById('scanHistory');
    const toastContainer = document.getElementById('toastContainer');

    let isSubmitting = false;
    let scanTimeout = null;
    let scanBuffer = '';
    let sessionStats = {
        success: parseInt(localStorage.getItem('session_success') || '0'),
        error: parseInt(localStorage.getItem('session_error') || '0'),
        total: parseInt(localStorage.getItem('session_total') || '0')
    };

    // Load history and stats
    loadHistory();
    updateStatsDisplay();

    // Show toast for session messages
    @if (session()->has('success'))
        showToast('{{ session("success") }}', 'success');
        updateStats('success', '{{ session("success") }}');
        playBeep(true);
        input.classList.add('success-animation');
        setTimeout(() => {
            input.value = '';
            input.focus();
            resetSubmitButton();
            input.classList.remove('success-animation');
        }, 1000);
    @endif

    @if (session()->has('error'))
        showToast('{{ session("error") }}', 'error');
        updateStats('error', '{{ session("error") }}');
        playBeep(false);
        input.classList.add('error-animation');
        setTimeout(() => {
            input.value = '';
            input.focus();
            resetSubmitButton();
            input.classList.remove('error-animation');
        }, 1000);
    @endif

    @if (session()->has('info'))
        showToast('{{ session("info") }}', 'info');
        addToHistory('info', '{{ session("info") }}');
        setTimeout(() => {
            input.value = '';
            input.focus();
            resetSubmitButton();
        }, 1000);
    @endif

    // Form submit
    form.addEventListener('submit', function(e) {
        if (isSubmitting) {
            e.preventDefault();
            return false;
        }

        const code = input.value.trim();
        if (!code) {
            e.preventDefault();
            showToast('الرجاء مسح الباركود أو إدخال الكود', 'warning');
            return false;
        }

        isSubmitting = true;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>جاري التسجيل...';
        scanIcon.className = 'bi bi-hourglass-split scan-icon';
    });

    // Detect fast scanning
    input.addEventListener('keydown', function(e) {
        clearTimeout(scanTimeout);
        if (e.key === 'Enter') {
            scanBuffer = '';
            return;
        }
        scanBuffer += e.key;
        scanTimeout = setTimeout(() => scanBuffer = '', 50);
    });

    // Auto-focus management
    document.addEventListener('click', function(e) {
        if (!form.contains(e.target) && !e.target.closest('.btn')) {
            setTimeout(() => input.focus(), 100);
        }
    });

    // Keep input focused
    setInterval(() => {
        if (document.activeElement !== input && !isSubmitting) {
            input.focus();
        }
    }, 1000);

    // Show Toast Notification
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        const iconMap = {
            success: 'bi-check-circle-fill text-success',
            error: 'bi-x-circle-fill text-danger',
            info: 'bi-info-circle-fill text-info',
            warning: 'bi-exclamation-triangle-fill text-warning'
        };

        toast.className = `custom-toast toast-${type}`;
        toast.innerHTML = `
            <i class="bi ${iconMap[type]} toast-icon"></i>
            <div class="flex-grow-1">${message}</div>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="bi bi-x"></i>
            </button>
        `;

        toastContainer.appendChild(toast);

        // Auto remove after 5 seconds
        setTimeout(() => {
            toast.classList.add('hiding');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    // Reset submit button
    function resetSubmitButton() {
        isSubmitting = false;
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="bi bi-check-lg"></i> تسجيل الحضور يدوياً';
        scanIcon.className = 'bi bi-upc-scan scan-icon';
    }

    // Update statistics
    function updateStats(type, message) {
        sessionStats.total++;
        if (type === 'success') sessionStats.success++;
        if (type === 'error') sessionStats.error++;

        localStorage.setItem('session_success', sessionStats.success);
        localStorage.setItem('session_error', sessionStats.error);
        localStorage.setItem('session_total', sessionStats.total);

        updateStatsDisplay();
        addToHistory(type, message);
    }

    // Update stats display
    function updateStatsDisplay() {
        document.getElementById('successCount').textContent = sessionStats.success;
        document.getElementById('errorCount').textContent = sessionStats.error;
        document.getElementById('totalCount').textContent = sessionStats.total;
    }

    // Add to history
    function addToHistory(type, message) {
        const history = JSON.parse(localStorage.getItem('scan_history') || '[]');
        const now = new Date();
        
        history.unshift({
            type: type,
            message: message,
            time: now.toLocaleTimeString('ar-EG'),
            timestamp: now.getTime()
        });

        if (history.length > 10) history.pop();
        localStorage.setItem('scan_history', JSON.stringify(history));
        loadHistory();
    }

    // Load history
    function loadHistory() {
        const history = JSON.parse(localStorage.getItem('scan_history') || '[]');
        
        if (history.length === 0) {
            scanHistory.innerHTML = `
                <div class="text-center text-muted py-5">
                    <i class="bi bi-inbox fs-1"></i>
                    <p class="mt-2">لا توجد عمليات مسح بعد</p>
                    <small>سيتم عرض آخر 10 عمليات هنا</small>
                </div>
            `;
            return;
        }

        scanHistory.innerHTML = history.map(item => {
            const iconClass = item.type === 'success' ? 'bi-check-circle-fill text-success' :
                            item.type === 'error' ? 'bi-x-circle-fill text-danger' :
                            'bi-info-circle-fill text-info';
            
            return `
                <div class="scan-history-item">
                    <div>
                        <i class="bi ${iconClass} me-2"></i>
                        <span class="small">${item.message}</span>
                    </div>
                    <small class="text-muted">${item.time}</small>
                </div>
            `;
        }).join('');
    }

    // Clear history
    window.clearHistory = function() {
        if (confirm('هل تريد مسح سجل المسح؟')) {
            localStorage.removeItem('scan_history');
            loadHistory();
            showToast('تم مسح السجل بنجاح', 'success');
        }
    };

    // Play beep sound
    function playBeep(success = true) {
        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);

            oscillator.frequency.value = success ? 800 : 400;
            oscillator.type = 'sine';

            gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);

            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.1);
        } catch (e) {
            console.log('Audio not supported');
        }
    }
});
</script>
@endsection