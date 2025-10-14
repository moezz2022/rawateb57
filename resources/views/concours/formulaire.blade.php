formulaire
<!DOCTYPE html>
<html dir="rtl" lang="ar">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استمارة المعلومات - رقم {{ str_pad($id, 6, '0', STR_PAD_LEFT) }}</title>
    <link rel="icon" href="{{ asset('assets/img/brand/logo57.png') }}" type="image/x-icon" />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @media print {
            body {
                margin: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .no-print {
                display: none !important;
            }
            
            .page-break {
                page-break-after: always;
            }
        }

        @page {
            margin: 10mm;
            size: A4 portrait;
        }

        body {
            font-family: 'Cairo', 'Arial', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            text-align: right;
            direction: rtl;
            padding: 15px;
            background-color: #fff;
            color: #000;
        }

        .container {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            padding: 20px;
        }

        /* Header Styles */
        .header {
            text-align: center;
            border-bottom: 3px solid #2575fc;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header-logo {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .logo {
            width: 80px;
            height: 80px;
        }

        .header-text {
            flex: 1;
            text-align: center;
        }

        .republic-name {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 5px;
        }

        .ministry-name {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .department-name {
            font-size: 15px;
            font-weight: 600;
            color: #555;
        }

        .header-title {
            font-size: 24px;
            font-weight: 700;
            color: #2575fc;
            margin-top: 15px;
            padding: 10px;
            background: linear-gradient(to right, #f0f7ff, #e6f2ff, #f0f7ff);
            border-radius: 5px;
            border-right: 5px solid #2575fc;
        }

        /* File Number Box */
        .file-number {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .file-number strong {
            font-size: 20px;
            display: block;
            margin-bottom: 5px;
        }

        .file-number span {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 2px;
        }

        /* Section Styles */
        .section {
            margin: 25px 0;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
        }

        .section-title {
            background: linear-gradient(to right, #2575fc, #6a11cb);
            color: white;
            font-size: 16px;
            font-weight: 700;
            padding: 12px 20px;
            text-align: center;
            border-bottom: 3px solid #1a5fd9;
        }

        .section-content {
            padding: 20px;
            background: #fafafa;
        }

        /* Info Table */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 12px;
        }

        .info-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .info-item {
            flex: 1;
            min-width: 200px;
            padding: 8px 12px;
            background: white;
            margin: 5px;
            border-radius: 5px;
            border-right: 3px solid #2575fc;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .info-label {
            font-weight: 700;
            color: #2575fc;
            font-size: 13px;
            display: inline-block;
            margin-left: 8px;
        }

        .info-value {
            color: #1a1a1a;
            font-weight: 600;
            font-size: 14px;
        }

        /* Grade and Position Info */
        .grade-info {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin: 15px 0;
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .grade-item {
            text-align: center;
            padding: 10px;
            min-width: 150px;
        }

        .grade-label {
            font-size: 12px;
            opacity: 0.9;
            display: block;
            margin-bottom: 5px;
        }

        .grade-value {
            font-size: 16px;
            font-weight: 700;
        }

        /* Credentials Box */
        .credentials-box {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .credential-item {
            text-align: center;
        }

        .credential-label {
            font-size: 12px;
            color: #856404;
            font-weight: 600;
            display: block;
            margin-bottom: 5px;
        }

        .credential-value {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a1a;
            background: white;
            padding: 8px 15px;
            border-radius: 5px;
            border: 1px solid #ffc107;
        }

        /* Checklist Styles */
        .checklist {
            list-style: none;
            padding: 0;
        }

        .checklist-item {
            background: white;
            padding: 12px 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            border-right: 4px solid #28a745;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .checklist-item:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: translateX(-5px);
        }

        .checkbox-square {
            width: 20px;
            height: 20px;
            border: 2px solid #28a745;
            border-radius: 3px;
            margin-left: 12px;
            flex-shrink: 0;
            background: white;
        }

        .checklist-text {
            flex: 1;
            color: #1a1a1a;
            font-size: 13px;
            line-height: 1.5;
        }

        /* Barcode Section */
        .barcode-section {
            text-align: center;
            margin: 30px 0 20px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 2px dashed #6c757d;
        }

        .barcode-placeholder {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 5px;
            margin: 10px auto;
            max-width: 300px;
            font-family: 'Courier New', monospace;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 3px;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e0e0e0;
            color: #666;
            font-size: 12px;
        }

        .footer-website {
            font-weight: 700;
            color: #2575fc;
            font-size: 14px;
            margin-top: 5px;
        }

        /* Print Button */
        .print-button {
            position: fixed;
            top: 20px;
            left: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .print-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        /* Alert Box */
        .alert-box {
            background: #d1ecf1;
            border: 2px solid #bee5eb;
            border-right: 5px solid #17a2b8;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            color: #0c5460;
            font-size: 13px;
            line-height: 1.6;
        }

        .alert-title {
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 8px;
            color: #0c5460;
        }

        /* Responsive */
        @media screen and (max-width: 768px) {
            body {
                padding: 10px;
            }

            .container {
                padding: 15px;
            }

            .info-item {
                min-width: 100%;
                margin: 5px 0;
            }

            .header-title {
                font-size: 18px;
            }

            .grade-info, .credentials-box {
                flex-direction: column;
            }

            .grade-item, .credential-item {
                width: 100%;
                margin: 5px 0;
            }
        }
    </style>
</head>

<body>
    <!-- Print Button -->
    <button class="print-button no-print" onclick="window.print()">
        <i class="fas fa-print"></i> طباعة الاستمارة
    </button>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-logo">
                <img src="{{ asset('assets/img/brand/logo57.png') }}" alt="Logo" class="logo">
                <div class="header-text">
                    <div class="republic-name">الجمهورية الجزائرية الديمقراطية الشعبية</div>
                    <div class="ministry-name">وزارة التربية الوطنية</div>
                    <div class="department-name">مديرية التربية لولاية المغير</div>
                </div>
                <img src="{{ asset('assets/img/brand/logo57.png') }}" alt="Logo" class="logo">
            </div>
            <div class="header-title">
                استمارة معلومات مترشح - مسابقة التوظيف 2024
            </div>
        </div>

        <!-- File Number -->
        <div class="file-number">
            <strong>رقم الملف</strong>
            <span>{{ str_pad($id, 6, '0', STR_PAD_LEFT) }}</span>
        </div>

        <!-- Grade and Position Information -->
        <div class="grade-info">
            <div class="grade-item">
                <span class="grade-label">الرتبة المستهدفة</span>
                <div class="grade-value">
                    @switch($con_grade)
                        @case(1) عامل مهني من المستوى الأول @break
                        @case(2) عامل مهني من المستوى الثاني @break
                        @case(3) عامل مهني من المستوى الثالث @break
                        @case(4) عون خدمة من المستوى الثالث @break
                        @case(5) سائق سيارة من المستوى الأول @break
                        @default {{ $con_grade }}
                    @endswitch
                </div>
            </div>
            @if($specialty)
            <div class="grade-item">
                <span class="grade-label">التخصص</span>
                <div class="grade-value">
                    @switch($specialty)
                        @case(1) طبخ الجماعات @break
                        @case(2) نجارة الألمنيوم @break
                        @case(3) تركيب صحي وغاز @break
                        @case(4) تركيب وصيانة أجهزة التبريد والتكييف @break
                        @case(5) الكهرباء المعمارية @break
                        @case(6) تلحيم @break
                        @case(7) بستنة @break
                        @default {{ $specialty }}
                    @endswitch
                </div>
            </div>
            @endif
        </div>

        <!-- Login Credentials -->
        <div class="credentials-box">
            <div class="credential-item">
                <span class="credential-label">اسم المستخدم</span>
                <div class="credential-value">{{ $username }}</div>
            </div>
            <div class="credential-item">
                <span class="credential-label">كلمة المرور</span>
                <div class="credential-value">{{ $password }}</div>
            </div>
        </div>

        <!-- Alert Box -->
        <div class="alert-box no-print">
            <div class="alert-title">⚠️ تنبيه مهم</div>
            احتفظ بهذه الاستمارة في مكان آمن. ستحتاج إلى اسم المستخدم وكلمة المرور للدخول إلى حسابك والاطلاع على نتائج المسابقة.
        </div>

        <!-- Section 1: Personal Information -->
        <div class="section">
            <div class="section-title">
                <i class="fas fa-user"></i> 1- المعلومات الشخصية
            </div>
            <div class="section-content">
                <div class="info-row">
                    <div class="info-item">
                        <span class="info-label">اللقب:</span>
                        <span class="info-value">{{ $NomArF }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">الاسم:</span>
                        <span class="info-value">{{ $PrenomArF }}</span>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-item">
                        <span class="info-label">رقم شهادة الميلاد:</span>
                        <span class="info-value">{{ $birthNum }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">الجنس:</span>
                        <span class="info-value">{{ $gender == 1 ? 'ذكر' : 'أنثى' }}</span>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-item">
                        <span class="info-label">تاريخ الميلاد:</span>
                        <span class="info-value">{{ $DateNaiF }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">مكان الميلاد:</span>
                        <span class="info-value">{{ $LieuNaiArF }}</span>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-item">
                        <span class="info-label">الحالة العائلية:</span>
                        <span class="info-value">
                            @switch($familyStatus)
                                @case(1) متزوج(ة) @break
                                @case(2) أعزب (عزباء) @break
                                @case(3) مطلق(ة) @break
                                @case(4) أرمل(ة) @break
                                @default {{ $familyStatus }}
                            @endswitch
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">عدد الأولاد:</span>
                        <span class="info-value">{{ $childrenNumber ?? '0' }}</span>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-item">
                        <span class="info-label">بلدية الإقامة:</span>
                        <span class="info-value">{{ $residenceMunicipality }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">رقم الهاتف:</span>
                        <span class="info-value">{{ $phoneNumber }}</span>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-item" style="flex: 1 1 100%;">
                        <span class="info-label">العنوان:</span>
                        <span class="info-value">{{ $personalAddress }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Educational Information -->
        <div class="section">
            <div class="section-title">
                <i class="fas fa-graduation-cap"></i> 2- معلومات الشهادة (أو المؤهل) المتحصل عليه
            </div>
            <div class="section-content">
                <div class="info-row">
                    @if($diploma)
                    <div class="info-item">
                        <span class="info-label">نوع الشهادة:</span>
                        <span class="info-value">
                            @switch($diploma)
                                @case(1) شهادة الكفاءة المهنية @break
                                @case(2) شهادة التكوين المهني المتخصص @break
                                @default {{ $diploma }}
                            @endswitch
                        </span>
                    </div>
                    @endif
                    @if($specialty)
                    <div class="info-item">
                        <span class="info-label">التخصص:</span>
                        <span class="info-value">
                            @switch($specialty)
                                @case(1) طبخ الجماعات @break
                                @case(2) نجارة الألمنيوم @break
                                @case(3) تركيب صحي وغاز @break
                                @case(4) تركيب وصيانة أجهزة التبريد والتكييف @break
                                @case(5) الكهرباء المعمارية @break
                                @case(6) تلحيم @break
                                @case(7) بستنة @break
                                @default {{ $specialty }}
                            @endswitch
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Section 3: Required Documents Checklist -->
        <div class="section">
            <div class="section-title">
                <i class="fas fa-clipboard-list"></i> 3- تكوين ملف المترشح
            </div>
            <div class="section-content">
                <ul class="checklist">
                    <li class="checklist-item">
                        <div class="checkbox-square"></div>
                        <span class="checklist-text">طلب خطي مدون عليه رقم الهاتف والعنوان</span>
                    </li>
                    <li class="checklist-item">
                        <div class="checkbox-square"></div>
                        <span class="checklist-text">نسخة من بطاقة التعريف الوطنية</span>
                    </li>
                    <li class="checklist-item">
                        <div class="checkbox-square"></div>
                        <span class="checklist-text">شهادة طبية (طب عام - أمراض صدرية) تثبت تأهيل المترشح لشغل منصب العمل المقصود</span>
                    </li>
                    <li class="checklist-item">
                        <div class="checkbox-square"></div>
                        <span class="checklist-text">نسخة من المؤهل أو شهادة إدارية تثبت فقط مدة التكوين</span>
                    </li>
                    <li class="checklist-item">
                        <div class="checkbox-square"></div>
                        <span class="checklist-text">نسخة مدرسية تثبت المستوى الدراسي المطلوب</span>
                    </li>
                    <li class="checklist-item">
                        <div class="checkbox-square"></div>
                        <span class="checklist-text">شهادة إقامة</span>
                    </li>
                    <li class="checklist-item">
                        <div class="checkbox-square"></div>
                        <span class="checklist-text">شهادة إثبات الوضعية إزاء الخدمة الوطنية (بالنسبة للذكور)</span>
                    </li>
                    <li class="checklist-item">
                        <div class="checkbox-square"></div>
                        <span class="checklist-text">وصل حقوق التسجيل: 200 دج (تدفع لدى مقتصد المؤسسة) + حافظة أوراق</span>
                    </li>
                    @if($con_grade == 5)
                    <li class="checklist-item">
                        <div class="checkbox-square"></div>
                        <span class="checklist-text">نسخة من رخصة السياقة سارية المفعول</span>
                    </li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- Barcode Section -->
        <div class="barcode-section">
            <div class="barcode-placeholder">
                {{ str_pad($id, 10, '0', STR_PAD_LEFT) }}
            </div>
            <div style="margin-top: 10px; color: #6c757d; font-size: 12px;">
                رقم التسجيل الإلكتروني
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div>مديرية التربية لولاية المغير</div>
            <div class="footer-website">de-elmeghaier.education.dz</div>
            <div style="margin-top: 10px; font-size: 11px;">
                تاريخ الطباعة: {{ date('Y-m-d H:i:s') }}
            </div>
        </div>
    </div>

    <!-- Font Awesome for Icons -->
</body>

</html>