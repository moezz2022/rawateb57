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
        .card-container-back {
            width: 120.6mm;
            height: 75mm;
            background: #ffffff;
            border: 2px solid #006233;
            border-radius: 8px;
            position: relative;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* زخرفة العلم الجزائري - في الزاوية اليمنى العلوية */
        .flag-decoration-back {
            position: absolute;
            top: 0;
            left: -6mm;
            width: 55mm;
            height: 55mm;
            overflow: hidden;
            z-index: 2;
            pointer-events: none;
        }

        .flag-decoration-back svg {
            display: block;
            width: 100%;
            height: 100%;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* رأس البطاقة الخلفية */
        .back-header {
            position: relative;
            z-index: 1;
            color: white;
        }

        .dz-logo {
            width: 55mm;
            height: auto;
        }

        .dz-logo img {
            width: 55mm;
            height: auto;
            object-fit: contain;
            opacity: 2;
            /* شفافية بسيطة حتى لا تطغى على اللون */
        }

        .left-logo {
            order: 1;
        }

        .back-subtitle {
            order: 2;
            font-size: 11pt;
            font-weight: 100;
            text-align: center;
            color: #575757;
            flex-grow: 1;
            margin: 0 5mm;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
            letter-spacing: 1px;
        }

        .right-logo {
            order: 3;
        }


        /* محتوى البطاقة الخلفية */
        .back-content {
            flex: 1;
            padding: 3mm 4mm;
            position: relative;
            z-index: 2;
        }


        /* قسم الملاحظات */
        .notes-section {
            margin-bottom: 3mm;
        }

        .notes-title {
            font-size: 12pt;
            font-weight: 700;
            color: #006233;
            margin-bottom: 1.5mm;
            border-right: 3px solid #d52b1e;
            padding-right: 2mm;
        }

        .notes-content {
            background: #f9f9f9;
            padding: 2mm;
            border-radius: 4px;
            min-height: 15mm;
            border: 1px solid #ddd;
        }

        .note-item {
            font-size: 9pt;
            color: #333;
            line-height: 1.4;
            margin-bottom: 1mm;
            padding-right: 3mm;
            position: relative;
        }

        .note-item:before {
            content: "•";
            position: absolute;
            right: 0;
            color: #006233;
            font-weight: bold;
        }

        /* قسم معلومات الطوارئ */
        .emergency-section {
            background: #fff9f0;
            border: 1px solid #ffb84d;
            border-radius: 4px;
            padding: 2mm;
            margin-bottom: 2mm;
        }

        .emergency-title {
            font-size: 10pt;
            font-weight: 700;
            color: #d52b1e;
            margin-bottom: 1mm;
        }

        .emergency-info {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 1mm 2mm;
            font-size: 9pt;
        }

        .emergency-label {
            font-weight: 600;
            color: #333;
        }

        .emergency-value {
            color: #666;
            border-bottom: 0.5pt dotted #ccc;
        }

        /* تذييل البطاقة الخلفية */
        .back-footer {
            position: relative;
            z-index: 2;
            background: linear-gradient(to top, #f5f5f5, white);
            padding: 0mm 3mm;
            border-top: 2px solid #006233;
        }

        .footer-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 8pt;
            color: #666;
        }

        .footer-contact {
            display: flex;
            gap: 3mm;
            align-items: center;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 1mm;
        }

        .contact-icon {
            color: #006233;
            font-size: 10pt;
        }

        .validity-info {
            text-align: left;
            font-weight: 600;
            color: #d52b1e;
        }

        /* الشعار الصغير في الخلفية */
        .watermark-logo {
            position: absolute;
            top: 20%;
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
        /* إعدادات الطباعة */
        /* ============================================ */

        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm 15mm 0 0 !important;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            html,
            body {
                margin: 0 !important;
                padding: 0 !important;
                width: 210mm;
                height: 297mm;
                background: #fff !important;
            }

            .document {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                background: #fff !important;
            }

            .document::before,
            .document::after {
                content: none !important;
                display: none !important;
            }

            /* ✅ نفس إعدادات الوجه الأمامي لضمان التطابق التام */
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

            .cards-grid:first-child {
                margin-top: 0 !important;
                padding-top: 2mm !important;
                page-break-before: avoid !important;
                break-before: avoid !important;
            }

            .cards-grid:not(:first-child) {
                page-break-before: always !important;
                break-before: page !important;
                margin-top: 0 !important;
                padding-top: 2mm !important;
            }

            .card-container-back {
                width: 120.6mm !important;
                height: 75mm !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                box-shadow: none !important;
            }

            .flag-decoration-back svg rect,
            .back-header,
            .back-title,
            .signature-label,
            .notes-title {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            * {
                box-shadow: none !important;
                text-shadow: none !important;
            }

            .row,
            .col-lg-12,
            .col-md-12,
            .card {
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }

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
        }

        @media screen {
            .cards-grid {
                padding-top: 8mm !important;
                padding-bottom: 8mm !important;
            }

            .card-container-back {
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
                        <i class="fa-solid fa-address-card ml-2"></i>
                        البطاقة المهنية - الوجه الخلفي
                    </h3>
                    <div class="btn-group">
                        <button onclick="window.location.href='{{ url('/cards/select-grade') }}'" type="button"
                            class="btn btn-info btn-lg btn-fill">
                            <i class="fas fa-users ml-2"></i> الإعدادات
                        </button>
                        @if (isset($employees) && count($employees) > 0)
                            <a href="{{ route('cards.print.selected', ['ids' => implode(',', $employees->pluck('id')->toArray())]) }}"
                                class="btn btn-danger btn-lg btn-fill">
                                <i class="fa-solid fa-id-badge ml-1"></i> الوجه الأمامي
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
                            // إذا كان عدد البطاقات فردي نضيف بطاقة فارغة لتكملة الصفحة
                            if ($group->count() % 2 != 0) {
                                $group->push((object) []);
                            }
                        @endphp

                        <div class="cards-grid">
                            @foreach ($group->chunk(2) as $row)
                                {{-- نقلب ترتيب الأعمدة داخل كل صف --}}
                                @foreach ($row->reverse() as $employee)
                                    <div class="card-container-back">
                                        @if (isset($employee->MATRI))
                                            <!-- ================== بطاقة الموظف ================== -->
                                            <div class="flag-decoration-back">
                                                <svg width="100%" height="100%" viewBox="0 0 300 100"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="0" y="0" width="200" height="20" fill="#006e2e"
                                                        transform="rotate(-45 0 0)" />
                                                    <rect x="0" y="20" width="200" height="20" fill="#d50000"
                                                        transform="rotate(-45 0 0)" />
                                                </svg>
                                            </div>

                                            <div class="back-header d-flex justify-content-between align-items-center">
                                                <div class="dz-logo left-logo">
                                                    <img src="{{ asset('assets/img/brand/Algeria.png') }}"
                                                        alt="DZ Logo Left">
                                                </div>

                                                <div class="back-subtitle text-center flex-grow-1">
                                                    IDDZA{{ $employee->MATRI ?? '' }}
                                                    >>> {{ $employee->NOM ?? '' }}
                                                    <<< {{ $employee->PRENOM ?? '' }} <<<
                                                        {{ $employee->DATNAIS ? \Carbon\Carbon::parse($employee->DATNAIS)->format('Ymd') : '' }}
                                                        <<<< </div>

                                                        <div class="dz-logo right-logo"></div>
                                                </div>

                                                <div class="back-content">
                                                    <div class="notes-section">
                                                        <div class="notes-title">ملاحظات هامة</div>
                                                        <div class="notes-content">
                                                            <div class="note-item">هذه البطاقة صالحة لمدة 10 سنوات</div>
                                                            <div class="note-item">يمنع استعمال هذه البطاقة من طرف الغير
                                                            </div>
                                                            <div class="note-item">في حالة الضياع أو السرقة يجب التبليغ فورا
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="watermark-logo">
                                                        <img src="{{ asset('assets/img/brand/logo57.png') }}"
                                                            alt="Watermark">
                                                    </div>
                                                </div>

                                                <div class="back-footer">
                                                    <div class="footer-info">
                                                        <div class="footer-contact">
                                                            <div class="contact-item">
                                                                <i class="fas fa-phone contact-icon"></i>
                                                                <span>032.XX.XX.XX</span>
                                                            </div>
                                                            <div class="contact-item">
                                                                <i class="fas fa-envelope contact-icon"></i>
                                                                <span>elmeghaier@education.dz</span>
                                                            </div>
                                                        </div>
                                                        <div class="validity-info">
                                                            تاربخ الإصدار: {{ now()->format('Y/m/d') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="back-content"
                                                    style="border: 1px dashed #ccc; opacity: 0.3; display: flex; justify-content: center; align-items: center; height: 100%;">
                                                    <span>بطاقة فارغة لتكملة الصفحة</span>
                                                </div>
                                        @endif
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const printBtn = document.getElementById("printCardBtn");
            if (printBtn) {
                printBtn.addEventListener("click", function() {
                    window.print();
                });
            }
        });
    </script>
@endsection
