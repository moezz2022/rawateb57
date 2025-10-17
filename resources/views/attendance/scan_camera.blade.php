@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* Scanner Frame Corners */
        .corner {
            position: absolute;
            width: 25px;
            height: 25px;
            border: 4px solid rgba(0, 255, 0, 0.8);
            transition: all 0.3s;
        }

        .corner-tl {
            top: -2px;
            left: -2px;
            border-right: none;
            border-bottom: none;
        }

        .corner-tr {
            top: -2px;
            right: -2px;
            border-left: none;
            border-bottom: none;
        }

        .corner-bl {
            bottom: -2px;
            left: -2px;
            border-right: none;
            border-top: none;
        }

        .corner-br {
            bottom: -2px;
            right: -2px;
            border-left: none;
            border-top: none;
        }

        /* Animated Scan Line */
        .scan-line {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            width: 70%;
            height: 3px;
            background: linear-gradient(90deg, transparent, rgba(0, 255, 0, 0.8), transparent);
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
            top: 20%;
            animation: scanAnimation 2s ease-in-out infinite;
            display: none;
        }

        @keyframes scanAnimation {

            0%,
            100% {
                top: 20%;
            }

            50% {
                top: 60%;
            }
        }

        .scanning .scan-line {
            display: block;
        }

        .scanning .scan-frame {
            border-color: rgba(0, 255, 0, 0.8);
            animation: pulse 1.5s ease-in-out infinite;
        }

        .scanning .corner {
            border-color: rgba(0, 255, 0, 1);
            box-shadow: 0 0 10px rgba(0, 255, 0, 0.5);
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.6;
            }
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
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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

        /* List Group Items */
        .list-group-item {
            border-left: 3px solid transparent;
            transition: all 0.3s;
            border-radius: 8px !important;
            margin-bottom: 8px;
        }

        .list-group-item.success {
            border-left-color: #28a745;
            background-color: #f0f9f4;
        }

        .list-group-item.error {
            border-left-color: #dc3545;
            background-color: #fcf0f1;
        }

        .list-group-item:hover {
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Card Animations */
        .card {
            transition: all 0.3s;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        /* Stats Cards */
        .stats-card {
            transition: transform 0.3s;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        /* Scanner Container */
        #scanner-container {
            position: relative;
            width: 100%;
            height: 480px;
            border: 3px solid #ddd;
            border-radius: 15px;
            background: #000;
            overflow: hidden;
            transition: border-color 0.3s;
        }

        #reader {
            width: 100%;
            height: 100%;
        }

        /* Hide html5-qrcode default buttons */
        #reader__dashboard_section {
            display: none !important;
        }

        .scan-frame {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            border: 3px dashed rgba(0, 255, 0, 0.5);
            width: 70%;
            height: 40%;
            border-radius: 12px;
            pointer-events: none;
            z-index: 10;
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الحضور والانصراف</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ ماسح بالكاميرا</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <div class="row">
        <!-- Left Side: Camera Scanner -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <h3 class="card-title ml-3 d-flex align-items-center mb-0">
                        <i class="bi bi-camera-video me-2"></i>
                        ماسح الباركود بالكاميرا
                    </h3>
                    <span class="badge ms-auto me-2" id="statusBadge">غير نشط</span>
                    <div class="btn-group">
                        <a href="{{ route('attendance.scan.barcode') }}" class="btn btn-danger btn-sm">
                            <i class="bi bi-upc-scan"></i> ماسح الباركود
                        </a>
                        <a href="{{ route('attendance.records') }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-list-ul"></i> السجلات
                        </a>
                        <a href="{{ route('attendance.reports') }}" class="btn btn-success btn-sm">
                            <i class="bi bi-file-earmark-bar-graph"></i> التقارير
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Scanner Container -->
                    <div id="scanner-container">
                        <div id="reader"></div>

                        <!-- Overlay Frame -->
                        <div id="overlay"
                            style="position:absolute; left:0; right:0; top:0; bottom:0; pointer-events:none;">
                            <div class="scan-frame">
                                <div class="corner corner-tl"></div>
                                <div class="corner corner-tr"></div>
                                <div class="corner corner-bl"></div>
                                <div class="corner corner-br"></div>
                            </div>
                            <div id="scanLine" class="scan-line"></div>
                        </div>

                        <!-- Loading Indicator -->
                        <div id="loadingIndicator" class="text-center text-white"
                            style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); display:none; z-index: 20;">
                            <div class="spinner-border" role="status"></div>
                            <p class="mt-2">جاري تحميل الكاميرا...</p>
                        </div>

                        <!-- No Camera Message -->
                        <div id="noCameraMsg" class="text-center text-white p-4"
                            style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); display:none; z-index: 20;">
                            <i class="bi bi-camera-video-off" style="font-size:4rem;"></i>
                            <p class="mt-3 mb-2">لم يتم العثور على كاميرا</p>
                            <small class="opacity-75">تأكد من السماح بالوصول للكاميرا في إعدادات المتصفح</small>
                        </div>
                    </div>

                    <!-- Controls -->
                    <div class="mt-3 d-flex flex-wrap gap-2 align-items-center">
                        <button id="startBtn" class="btn btn-success">
                            <i class="bi bi-play-fill"></i> تشغيل
                        </button>
                        <button id="stopBtn" class="btn btn-danger" disabled>
                            <i class="bi bi-stop-fill"></i> إيقاف
                        </button>
                        <select id="cameraSelect" class="form-select w-auto">
                            <option value="">الكاميرا الافتراضية</option>
                        </select>
                        <button id="switchCameraBtn" class="btn btn-outline-primary" title="تبديل الكاميرا">
                            <i class="bi bi-arrow-repeat"></i>
                        </button>
                        <div class="ms-auto d-flex align-items-center gap-2">
                            <label class="form-label mb-0" for="soundToggle">
                                <i class="bi bi-volume-up"></i> صوت
                            </label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="soundToggle" checked>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="row mt-4" id="statistics" style="display:none;">
                        <div class="col-md-4 mb-2">
                            <div class="card bg-success text-white stats-card">
                                <div class="card-body text-center py-3">
                                    <h2 class="mb-0" id="successCount">0</h2>
                                    <small>نجح</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="card bg-danger text-white stats-card">
                                <div class="card-body text-center py-3">
                                    <h2 class="mb-0" id="errorCount">0</h2>
                                    <small>فشل</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="card bg-primary text-white stats-card">
                                <div class="card-body text-center py-3">
                                    <h2 class="mb-0" id="totalCount">0</h2>
                                    <small>إجمالي</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Right Side: Log & Settings -->
    <div class="col-lg-4 mb-4">
        <!-- Session Log -->
        <div class="card mb-3">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="bi bi-clock-history"></i> سجل المسح
                    </h6>
                    <button id="clearLogBtn" class="btn btn-sm btn-light" title="مسح السجل">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                <div class="card-body p-2" style="max-height:400px; overflow-y:auto;">
                    <div id="sessionLog" class="list-group list-group-flush">
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3"></i>
                            <p class="mb-0 mt-2 small">لا توجد عمليات مسح</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0">
                        <i class="bi bi-gear"></i> الإعدادات
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small">سرعة المسح (FPS)</label>
                        <select id="fpsSelect" class="form-select form-select-sm">
                            <option value="5">5 FPS (بطيء)</option>
                            <option value="10" selected>10 FPS (موصى)</option>
                            <option value="15">15 FPS (سريع)</option>
                            <option value="20">20 FPS (سريع جداً)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">تأخير إعادة المسح</label>
                        <input type="range" class="form-range" id="cooldownRange" min="0.3" max="2"
                            step="0.1" value="0.6">
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">0.3s</small>
                            <small class="text-primary fw-bold" id="cooldownValue">0.6s</small>
                            <small class="text-muted">2.0s</small>
                        </div>
                    </div>
                    <hr>
                    <div class="alert alert-warning alert-sm mb-2 py-2">
                        <i class="bi bi-shield-check"></i>
                        <small>يتطلب <strong>HTTPS</strong> أو <strong>localhost</strong></small>
                    </div>
                    <div id="browserInfo" class="text-muted small"></div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="card mt-3">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('attendance.scan.barcode') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-upc-scan"></i> ماسح الباركود
                        </a>
                        <a href="{{ route('attendance.records') }}" class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-list-ul"></i> عرض السجلات
                        </a>
                        <a href="{{ route('attendance.reports') }}" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-file-earmark-bar-graph"></i> التقارير
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startBtn = document.getElementById('startBtn');
            const stopBtn = document.getElementById('stopBtn');
            const cameraSelect = document.getElementById('cameraSelect');
            const switchCameraBtn = document.getElementById('switchCameraBtn');
            const sessionLog = document.getElementById('sessionLog');
            const clearLogBtn = document.getElementById('clearLogBtn');
            const soundToggle = document.getElementById('soundToggle');
            const fpsSelect = document.getElementById('fpsSelect');
            const cooldownRange = document.getElementById('cooldownRange');
            const cooldownValue = document.getElementById('cooldownValue');
            const statusBadge = document.getElementById('statusBadge');
            const scannerContainer = document.getElementById('scanner-container');
            const loadingIndicator = document.getElementById('loadingIndicator');
            const noCameraMsg = document.getElementById('noCameraMsg');
            const statistics = document.getElementById('statistics');
            const toastContainer = document.getElementById('toastContainer');

            let html5QrCode = null;
            let isScanning = false;
            let lastCode = null;
            let cooldownTimer = null;
            let cooldownDuration = 600;
            let cameras = [];
            let currentCameraIndex = 0;
            let stats = {
                success: 0,
                error: 0,
                total: 0
            };

            // Initialize
            detectBrowser();
            populateCameras();

            // Detect Browser
            function detectBrowser() {
                const info = document.getElementById('browserInfo');
                const ua = navigator.userAgent;
                let browser = 'Unknown';

                if (ua.indexOf('Chrome') > -1) browser = 'Chrome';
                else if (ua.indexOf('Firefox') > -1) browser = 'Firefox';
                else if (ua.indexOf('Safari') > -1) browser = 'Safari';
                else if (ua.indexOf('Edge') > -1) browser = 'Edge';

                const isHttps = location.protocol === 'https:' || location.hostname === 'localhost';
                info.innerHTML = `
            <div class="d-flex justify-content-between">
                <span>المتصفح:</span> 
                <strong>${browser}</strong>
            </div>
            <div class="d-flex justify-content-between">
                <span>HTTPS:</span> 
                <strong class="${isHttps ? 'text-success' : 'text-danger'}">${isHttps ? 'نعم ✓' : 'لا ✗'}</strong>
            </div>
        `;
            }

            // Populate Cameras
            async function populateCameras() {
                try {
                    const devices = await Html5Qrcode.getCameras();
                    cameras = devices || [];

                    cameraSelect.innerHTML = '<option value="">الكاميرا الافتراضية</option>';
                    cameras.forEach((cam, idx) => {
                        const option = document.createElement('option');
                        option.value = cam.id;
                        option.text = cam.label || `كاميرا ${idx + 1}`;
                        cameraSelect.appendChild(option);
                    });

                    switchCameraBtn.disabled = cameras.length <= 1;

                    if (cameras.length === 0) {
                        noCameraMsg.style.display = 'block';
                    }
                } catch (err) {
                    console.error('Cannot enumerate cameras', err);
                    noCameraMsg.style.display = 'block';
                }
            }

            // Show Toast
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

                setTimeout(() => {
                    toast.classList.add('hiding');
                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            }

            // Log Session
            function logSession(msg, type = 'info', details = '') {
                const emptyState = sessionLog.querySelector('.text-muted');
                if (emptyState) emptyState.remove();

                const el = document.createElement('div');
                el.className = `list-group-item ${type}`;
                el.innerHTML = `
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <strong class="text-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'muted'}">${msg}</strong>
                    ${details ? `<br><small class="text-muted">${details}</small>` : ''}
                </div>
                <small class="text-muted">${new Date().toLocaleTimeString('ar-DZ')}</small>
            </div>
        `;

                sessionLog.insertBefore(el, sessionLog.firstChild);

                while (sessionLog.children.length > 20) {
                    sessionLog.removeChild(sessionLog.lastChild);
                }

                statistics.style.display = 'block';
            }

            // Update Stats
            function updateStats(type) {
                if (type) {
                    stats.total++;
                    if (type === 'success') stats.success++;
                    if (type === 'error') stats.error++;
                }

                document.getElementById('successCount').textContent = stats.success;
                document.getElementById('errorCount').textContent = stats.error;
                document.getElementById('totalCount').textContent = stats.total;
            }

            // Play Beep
            function playBeep(success = true) {
                if (!soundToggle.checked) return;

                try {
                    const audioCtx = new(window.AudioContext || window.webkitAudioContext)();
                    const o = audioCtx.createOscillator();
                    const g = audioCtx.createGain();
                    o.connect(g);
                    g.connect(audioCtx.destination);
                    o.type = 'sine';
                    o.frequency.value = success ? 1000 : 400;
                    g.gain.setValueAtTime(0.2, audioCtx.currentTime);
                    g.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.15);
                    o.start();
                    o.stop(audioCtx.currentTime + 0.15);
                } catch (e) {
                    console.warn('Audio not supported');
                }
            }

            // Send Code to Server
            function sendCodeToServer(code) {
                if (cooldownTimer) {
                    console.log('Cooldown active, skipping...');
                    return;
                }

                cooldownTimer = setTimeout(() => {
                    cooldownTimer = null;
                    lastCode = null;
                }, cooldownDuration);

                // استبدل ROUTE_URL بالـ route الصحيح من Laravel
                fetch("{{ route('attendance.scan.api') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({
                            code
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'success') {
                            const typeAr = data.type === 'check_in' ? 'دخول' : 'خروج';
                            const empName = `${data.employee.NOMA || ''} ${data.employee.PRENOMA || ''}`.trim();
                            showToast(`✓ ${typeAr}: ${empName} — ${data.time || ''}`, 'success');
                            logSession(`${typeAr}`, 'success', `${empName} — ${data.time || ''}`);
                            playBeep(true);
                            updateStats('success');

                            scannerContainer.style.borderColor = '#28a745';
                            setTimeout(() => scannerContainer.style.borderColor = '#ddd', 1000);
                        } else {
                            showToast(data.message || 'خطأ غير معروف', 'error');
                            logSession('فشل', 'error', data.message || 'خطأ');
                            playBeep(false);
                            updateStats('error');

                            scannerContainer.style.borderColor = '#dc3545';
                            setTimeout(() => scannerContainer.style.borderColor = '#ddd', 1000);
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        showToast('خطأ في الاتصال بالسيرفر', 'error');
                        logSession('خطأ اتصال', 'error', err.message);
                        playBeep(false);
                        updateStats('error');
                    });
            }

            // Start Scanner
            async function startScanner(selectedCameraId = null) {
                if (isScanning) return;

                loadingIndicator.style.display = 'block';
                noCameraMsg.style.display = 'none';

                try {
                    html5QrCode = new Html5Qrcode("reader");
                    const cameraId = selectedCameraId || cameras[currentCameraIndex]?.id || {
                        facingMode: "environment"
                    };
                    const fps = parseInt(fpsSelect.value);

                    const config = {
                        fps: fps,
                        qrbox: {
                            width: 300,
                            height: 200
                        },
                        aspectRatio: 1.777778
                    };

                    await html5QrCode.start(
                        cameraId,
                        config,
                        (decodedText) => {
                            if (decodedText === lastCode) return;
                            lastCode = decodedText;
                            console.log("Code detected:", decodedText);
                            alert("تم التقاط الكود: " + decodedText);
                            sendCodeToServer(decodedText);
                        },
                        (errorMessage) => {
                            // Silent - ignore scan errors
                        }
                    );

                    isScanning = true;
                    startBtn.disabled = true;
                    stopBtn.disabled = false;
                    statusBadge.textContent = 'نشط';
                    statusBadge.className = 'badge bg-success ms-auto me-2';
                    scannerContainer.classList.add('scanning');
                    loadingIndicator.style.display = 'none';
                    showToast('الكاميرا تعمل - جاهز للمسح', 'success');

                } catch (err) {
                    console.error('Error starting scanner:', err);
                    loadingIndicator.style.display = 'none';
                    noCameraMsg.style.display = 'block';
                    showToast('تعذر تشغيل الكاميرا: ' + err.message, 'error');
                }
            }

            // Stop Scanner
            async function stopScanner() {
                if (!isScanning || !html5QrCode) return;

                try {
                    await html5QrCode.stop();
                    html5QrCode.clear();
                    html5QrCode = null;

                    isScanning = false;
                    startBtn.disabled = false;
                    stopBtn.disabled = true;
                    statusBadge.textContent = 'متوقف';
                    statusBadge.className = 'badge bg-secondary ms-auto me-2';
                    scannerContainer.classList.remove('scanning');
                    showToast('تم إيقاف الكاميرا', 'info');
                } catch (err) {
                    console.error('Error stopping scanner:', err);
                }
            }

            // Event Listeners
            startBtn.addEventListener('click', () => startScanner(cameraSelect.value || null));
            stopBtn.addEventListener('click', stopScanner);

            cameraSelect.addEventListener('change', function() {
                if (isScanning) { // تم التصليح: كان active الآن isScanning
                    stopScanner();
                    setTimeout(() => startScanner(cameraSelect.value || null), 300);
                }
            });

            switchCameraBtn.addEventListener('click', () => {
                if (cameras.length <= 1) return;
                currentCameraIndex = (currentCameraIndex + 1) % cameras.length;
                cameraSelect.selectedIndex = currentCameraIndex + 1;

                if (isScanning) { // تم التصليح: كان active الآن isScanning
                    stopScanner();
                    setTimeout(() => startScanner(cameras[currentCameraIndex].id),
                        300); // تم التصليح: deviceId -> id
                }
            });

            clearLogBtn.addEventListener('click', () => {
                if (confirm('هل تريد مسح سجل الجلسة؟')) {
                    sessionLog.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="bi bi-inbox fs-3"></i>
                    <p class="mb-0 mt-2 small">لا توجد عمليات مسح</p>
                </div>
            `;
                    stats = {
                        success: 0,
                        error: 0,
                        total: 0
                    };
                    updateStats();
                    statistics.style.display = 'none';
                    showToast('تم مسح السجل', 'success');
                }
            });

            // تم الحذف: qualitySelect غير موجود في HTML

            fpsSelect.addEventListener('change', () => {
                if (isScanning) { // تم التصليح
                    stopScanner();
                    setTimeout(() => startScanner(cameraSelect.value || null), 300);
                }
            });

            cooldownRange.addEventListener('input', (e) => {
                cooldownDuration = parseFloat(e.target.value) * 1000;
                cooldownValue.textContent = `${parseFloat(e.target.value).toFixed(1)}s`;
            });


            // Auto-refresh camera list
            setInterval(populateCameras, 10000);

            // Cleanup on page unload
            window.addEventListener('beforeunload', () => {
                if (isScanning) stopScanner(); // تم التصليح
            });
        });
    </script>
@endsection
