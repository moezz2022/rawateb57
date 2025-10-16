@extends('layouts.master')
@section('css')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* إعدادات أساسية */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: #f5f5f5;
            direction: rtl;
        }

        .document {
            position: relative !important;
            width: 100% !important;
            margin: 0 auto !important;
            background: white !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        /* شبكة البطاقات */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(2, 120.6mm);
            grid-auto-rows: 75mm;
            gap: 3mm 20.7mm;
            justify-content: center;
            align-content: start;
            margin: 0 auto !important;
            background: white !important;
        }

        /* حاوية البطاقة */
        .card-container {
            width: 120.6mm;
            height: 75mm;
            background: #ffffff;
            border: 2px solid #006233;
            border-radius: 8px;
            position: relative;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }

        /* زخرفة العلم الجزائري - باستخدام SVG */
        .flag-decoration {
            position: absolute;
            top: 0;
            left: -6mm;
            width: 55mm;
            height: 55mm;
            overflow: hidden;
            z-index: 2;
            pointer-events: none;
        }

        .flag-decoration svg {
            display: block;
            width: 100%;
            height: 100%;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .card-header-section {
            position: relative;
            z-index: 2;
            text-align: right;
            padding: 0 1mm 0 1mm;
        }

        .card-header-title {
            font-size: 14pt;
            font-weight: 700;
            color: #000;
            line-height: 1.2;
            text-align: center;
            margin-bottom: 0.5mm;
        }

        .ministry-info {
            font-size: 11.5pt;
            color: #000;
            line-height: 1.3;
            font-weight: 600;
            text-align: right;
        }

        /* الشعار */
        .logo-section {
            position: absolute;
            top: 4.5mm;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            z-index: 2;
        }

        .logo {
            width: 15mm;
            height: 15mm;
            margin: 0 auto;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            opacity: 1;
        }

        .watermark-logo {
            position: absolute;
            top: 55%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.2;
            width: 60mm;
            height: 60mm;
            z-index: 1;
            pointer-events: none;
        }

        .watermark-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .card-type {
            font-size: 18pt;
            font-weight: 700;
            color: #d52b1e;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        /* محتوى البطاقة */
        .content {
            position: relative;
            z-index: 2;
            display: flex;
            padding: 1mm 1mm;
            gap: 2mm;
            align-items: flex-start;
        }

        /* قسم المعلومات */
        .info-section {
            flex: 1;
            flex-direction: column;
            gap: 1mm;
        }

        .info-row {
            display: flex;
            line-height: 1.1;
            border-bottom: 0.5pt solid #ddd;
            padding: 0.4mm;
        }

        .info-label {
            font-weight: 600;
            color: #000;
            font-size: 12pt;
        }

        .info-value {
            color: #333;
            font-weight: 500;
            font-size: 13pt;
            padding-right: 2mm;
        }

        .photo-section {
            flex-shrink: 0;
            width: 28mm;
            display: flex;
            flex-direction: column;
            gap: 1mm;
        }

        .photo {
            width: 28mm;
            height: 36mm;
            border: 1px solid #000;
            background-color: #f9f9f9;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 5;
        }

        .photo-placeholder {
            font-size: 10pt;
            color: #999;
            text-align: center;
            z-index: 1;
            position: relative;
        }

        .upload-icon {
            position: absolute;
            bottom: 3px;
            right: 3px;
            background-color: rgba(0, 0, 0, 0.6);
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 8mm;
            height: 8mm;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            transition: background-color 0.3s;
        }

        .upload-icon:hover {
            background-color: #006233;
        }

        .page-footer {
            position: absolute;
            bottom: 0;
            left: 18px;
            right: 0;
            display: flex;
            align-items: center;
            background: #fff;
            height: 9mm;
            z-index: 2;
        }

        .barcode-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1mm 2mm;
        }

        .barcode-section img {
            height: 6mm;
            width: auto;
            max-width: 100%;
        }

        .qr-code {
            width: 20mm;
            height: 10mm;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-bottom: 1mm;
        }

        .qr-code img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .edit-btn {
            color: red;
            animation: blinker 2s linear infinite;
        }

        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }

        .btn-fill {
            transition: all 0.3s ease;
        }

        .btn-fill:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* ============================================ */
        /* إعدادات الطباعة - الحل النهائي */
        /* ============================================ */

        @media print {

            /* إعدادات الصفحة */
            @page {
                size: A4 portrait;
                margin: 10mm 15mm 0 0 !important;
            }

            /* إزالة جميع الهوامش والحشوات */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            html {
                margin: 0 !important;
                padding: 0 !important;
                width: 210mm;
                height: 297mm;
            }

            body {
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
                width: 210mm;
                min-height: 297mm;
            }

            /* ✅ الحل الرئيسي: إزالة الصفحة الفارغة الأولى */
            .document {
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
                width: 100% !important;
                align-items: center !important;
            }

            .document::before,
            .document::after {
                content: none !important;
                display: none !important;
            }

            /* شبكة البطاقات */
            .cards-grid {
                display: grid !important;
                grid-template-columns: repeat(2, 120.6mm) !important;
                grid-auto-rows: 75mm !important;
                gap: 3mm 20.7mm !important;
                justify-content: center !important;
                align-content: start !important;
                margin: 5mm !important;
                padding: 2mm 0 !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                background: #fff !important;
            }

            /* أول شبكة تبدأ من أعلى الصفحة بدون فراغ */
            .cards-grid:first-child {
                margin-top: 0 !important;
                padding-top: 2mm !important;
                page-break-before: avoid !important;
                break-before: avoid !important;
            }

            /* باقي الشبكات تبدأ من صفحة جديدة */
            .cards-grid:not(:first-child) {
                page-break-before: always !important;
                break-before: page !important;
                margin-top: 0 !important;
                padding-top: 2mm !important;
            }

            /* البطاقات */
            .card-container {
                width: 120.6mm !important;
                height: 75mm !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                box-shadow: none !important;
            }

            /* إخفاء عناصر الواجهة */
            .btn-group,
            .breadcrumb-header,
            .card-header,
            .main-footer,
            .header-icon,
            .main-header,
            .main-header-center,
            .responsive-logo,
            .main-profile-menu,
            .profile-user img,
            .d-print-none,
            .upload-icon,
            .edit-btn,
            aside,
            nav,
            header,
            footer,
            .sidebar,
            .navbar,
            .app-sidebar,
            .main-container,
            .page-header {
                display: none !important;
                visibility: hidden !important;
                height: 0 !important;
                width: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* التأكد من ظهور الألوان */
            .flag-decoration svg rect,
            .card-type {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            /* إزالة الظلال في الطباعة */
            * {
                box-shadow: none !important;
                text-shadow: none !important;
            }

            /* التأكد من عدم وجود مسافات إضافية */
            .row,
            .col-lg-12,
            .col-md-12,
            .card {
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }
        }

        /* إعدادات الشاشة */
        @media screen {
            .cards-grid {
                padding-top: 8mm !important;
                padding-bottom: 8mm !important;
            }

            .card-container {
                width: 120.6mm !important;
            }
        }
    </style>
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المستخدمين</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ البطاقات المهنية</span>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <h3 class="card-title mb-0 d-flex align-items-center">
                        <i class="fa-solid fa-id-card ml-2"></i>
                        البطاقة المهنية - الوجه الأمامي
                    </h3>
                    <div class="btn-group">
                        <button onclick="window.location.href='{{ url('/cards/select-grade') }}'" type="button"
                            class="btn btn-info btn-lg btn-fill">
                            <i class="fas fa-users ml-2"></i> الإعدادات
                        </button>
                        @if (isset($employees))
                            <a href="{{ route('employees.cardsback') }}" class="btn btn-success btn-lg btn-fill">
                                <i class="fa-solid fa-address-card ml-1"></i> الوجه الخلفي
                            </a>
                        @endif
                        <button type="button" id="printCardBtn" class="btn btn-warning btn-lg btn-fill">
                            <i class="fas fa-print mr-2"></i> طباعة
                        </button>
                    </div>
                </div>

                <div class="document">
                    @foreach ($employees->chunk(10) as $group)
                        @php
                            // إذا كان عدد البطاقات في الصفحة فردي نضيف بطاقة فارغة لتكملة العدد
                            if ($group->count() % 2 != 0) {
                                $group->push((object) []);
                            }
                        @endphp

                        <div class="cards-grid">
                            @foreach ($group as $employee)
                                <div class="card-container">
                                    @if (isset($employee->id))
                                        {{-- ================== بطاقة الموظف ================== --}}
                                        <div class="flag-decoration">
                                            <svg width="100%" height="100%" viewBox="0 0 300 100"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <rect x="0" y="0" width="200" height="20" fill="#006e2e"
                                                    transform="rotate(-45 0 0)" />
                                                <rect x="0" y="20" width="200" height="20" fill="#d50000"
                                                    transform="rotate(-45 0 0)" />
                                            </svg>
                                        </div>

                                        <!-- رأس البطاقة -->
                                        <div class="card-header-section">
                                            <div class="card-header-title">الجمهورية الجزائرية الديمقراطية الشعبية</div>
                                            <div class="ministry-info">
                                                وزارة التربية الوطنية<br>
                                                مديرية التربية لولاية المغير
                                            </div>
                                        </div>

                                        <!-- الشعار -->
                                        <div class="logo-section">
                                            <div class="logo">
                                                <img src="{{ asset('assets/img/brand/logo57.png') }}" alt="Logo">
                                            </div>
                                        </div>

                                        <!-- نوع البطاقة -->
                                        <div class="card-type">بطاقة مهنية</div>

                                        <div class="watermark-logo">
                                            <img src="{{ asset('assets/img/brand/logo57.png') }}" alt="Watermark">
                                        </div>

                                        <!-- المحتوى -->
                                        <div class="content">
                                            <div class="info-section">
                                                <div class="info-row">
                                                    <span class="info-label">رقم البطاقة :</span>
                                                    <span class="info-value">{{ '00' . $employee->id }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">رقم التعريف الوطني:</span>
                                                    <span class="info-value">{{ $employee->MATRI }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">اللقب والاسم:</span>
                                                    <span class="info-value">{{ $employee->NOMA }}
                                                        {{ $employee->PRENOMA }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">تاريخ الميلاد:</span>
                                                    <span class="info-value">{{ $employee->DATNAIS }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">الوظيفة:</span>
                                                    <span class="info-value">{{ $employee->grade->name ?? '' }}</span>
                                                </div>
                                                <div class="info-row">
                                                    <span class="info-label">تاريخ التوظيف:</span>
                                                    <span class="info-value">{{ $employee->DATENT }}</span>
                                                </div>
                                            </div>

                                            <div class="photo-section">
                                                <div class="photo" id="photo-container-{{ $employee->id }}">
                                                    <span class="photo-placeholder">صورة<br>الموظف</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- التذييل -->
                                        <div class="page-footer">
                                            <div class="barcode-section">
                                                @if (isset($employee->MATRI) && isset($employee->CLECPT))
                                                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG('00799999000' . $employee->MATRI . $employee->CLECPT, 'C128', 1.5, 40) }}"
                                                        alt="Barcode" />
                                                @endif
                                            </div>
                                            <div class="qr-code">
                                                @if (isset($employee->MATRI) && isset($employee->CLECPT))
                                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode('00799999000' . $employee->MATRI . $employee->CLECPT) }}"
                                                        alt="QR Code" />
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        {{-- ================== بطاقة فارغة ================== --}}
                                        <div class="content"
                                            style="border: 1px dashed #ccc; opacity: 0.3; display: flex; justify-content: center; align-items: center; height: 100%;">
                                            <span>بطاقة فارغة لتكملة الصفحة</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        window.previewImage = function(event, id) {
            try {
                const file = event.target.files[0];
                if (!file || !file.type.startsWith('image/')) {
                    alert('الرجاء اختيار صورة فقط');
                    return;
                }

                const reader = new FileReader();

                reader.onload = function(e) {
                    const photoDiv = document.getElementById(`photo-container-${id}`);
                    if (!photoDiv) return;

                    const placeholder = photoDiv.querySelector('.photo-placeholder');
                    if (placeholder) placeholder.style.display = 'none';

                    let img = photoDiv.querySelector('img[id^="photo-img-"]');
                    if (!img) {
                        img = document.createElement('img');
                        img.id = `photo-img-${id}`;
                        img.alt = 'صورة الموظف';
                        photoDiv.prepend(img);
                    }

                    img.style.cssText = `
                position: absolute !important;
                top: 0 !important;
                right: 2mm !important;
                width: 100% !important;
                height: 100% !important;
                object-fit: cover !important;
                z-index: 5 !important;
                display: block !important;
                opacity: 1 !important;
            `;

                    img.src = e.target.result;
                };

                reader.readAsDataURL(file);
            } catch (error) {
                alert('حدث خطأ أثناء تحميل الصورة');
            }
        };

        window.uploadImage = function(id) {
            const fileInput = document.getElementById(`file-input-${id}`);
            if (fileInput) fileInput.click();
        };

        window.makeEditable = function(icon) {
            const span = icon.parentElement.querySelector(".info-value");
            if (!span) return;

            const currentText = span.innerText.trim();
            const input = document.createElement("input");
            input.type = "text";
            input.value = currentText;
            input.className = span.className;
            input.style.cssText = `
        width: ${span.offsetWidth + 20}px;
        border: 1px solid #006233;
        padding: 2px 5px;
        font-size: inherit;
        font-family: inherit;
        direction: rtl;
    `;

            input.addEventListener("blur", function() {
                span.innerText = input.value.trim();
                input.replaceWith(span);
            });

            input.addEventListener("keypress", function(e) {
                if (e.key === "Enter") input.blur();
            });

            span.replaceWith(input);
            input.focus();
            input.select();
        };

        document.addEventListener('DOMContentLoaded', function() {
            const fileInputs = document.querySelectorAll('[id^="file-input-"]');
            fileInputs.forEach(function(input) {
                const id = input.id.replace('file-input-', '');
                input.addEventListener('change', function(event) {
                    if (event.target.files.length === 0) return;
                    const file = event.target.files[0];
                    if (!file || !file.type.startsWith('image/')) {
                        alert('الرجاء اختيار صورة فقط');
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const photoDiv = document.getElementById(`photo-container-${id}`);
                        if (!photoDiv) return;

                        const placeholder = photoDiv.querySelector('.photo-placeholder');
                        if (placeholder) placeholder.style.display = 'none';

                        let img = photoDiv.querySelector('img[id^="photo-img-"]');
                        if (!img) {
                            img = document.createElement('img');
                            img.id = `photo-img-${id}`;
                            img.alt = 'صورة الموظف';
                            photoDiv.prepend(img);
                        }
                        img.style.cssText = `
                    position: absolute !important;
                    top: 0 !important;
                    left: 0 !important;
                    width: 100% !important;
                    height: 100% !important;
                    object-fit: cover !important;
                    z-index: 5 !important;
                    display: block !important;
                    opacity: 1 !important;
                `;
                        img.src = e.target.result;
                    };

                    reader.readAsDataURL(file);
                });
            });
            const printBtn = document.getElementById("printCardBtn");
            if (printBtn) {
                printBtn.addEventListener("click", function() {
                    window.print();
                });
            }
        });
    </script>
@endsection
