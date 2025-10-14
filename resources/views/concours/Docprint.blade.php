<html dir="rtl" lang="ar">

<head>
    <meta charset="utf-8">
    <title>استمارة المعلومات</title>
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
            margin: 3.5mm;
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
            color: #000000;
            margin-top: 0px;
            padding: 10px;
            background: #ddd;
            border-radius: 5px;
            border-right: 5px solid #f0f7ff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 5px;
            vertical-align: top;
            border: 0px solid #ddd;
        }


        .section-title {
            text-align: center;
            background-color: #f0f0f0;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            border-bottom: 1px solid black;
            padding: 5px 0;
        }
    </style>
</head>

<body onload="window.print();">
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
    <table border="0" style="margin:25 0 25 0; font-size: 16px;" width="100%" dir="rtl">
        <tr>
            <td width="30%"><strong>رقم الملف:</strong> 000{{ $id }}</td>
            <td width="30%"><strong>الرتبة:</strong> {{ $con_grade }}</td>
            <td width="40%"><strong>التخصص:</strong> {{ $specialty }}</td>
        </tr>
        <tr>
            <td><strong>اسم المستخدم:</strong> {{ $username }}</td>
            <td><strong>كلمة المرور:</strong> {{ $password }}</td>
        </tr>
        <tr>
            <td class="section-title" colspan="4">1- المعلومات الشخصية</td>
        </tr>
        <tr>
            <td><strong>اللقب:</strong> {{ $NomArF }}</td>
            <td><strong>الاسم:</strong> {{ $PrenomArF }}</td>
            <td><strong>الجنس:</strong> {{ $gender == 1 ? 'ذكر' : 'أنثى' }}</td>
        </tr>
        <tr>
            <td><strong>تاريخ الميلاد:</strong> {{ $DateNaiF }}</td>
            <td><strong>مكان الميلاد:</strong> {{ $LieuNaiArF }}</td>
            <td><strong>رقم شهادة الميلاد:</strong> {{ $birthNum }}</td>
        </tr>
        <tr>
            <td><strong>الوضعية العائلية:</strong> {{ $familyStatus }}</td>
            <td><strong>عدد الأولاد:</strong> {{ $childrenNumber }}</td>
        </tr>
        <tr>
            <td><strong>العنوان:</strong> {{ $personalAddress }}</td>
            <td><strong>بلدية الإقامة:</strong> {{ $residenceMunicipality }}</td>
            <td><strong>رقم الهاتف:</strong> {{ $phoneNumber }}</td>
        </tr>
        <tr>
            <td class="section-title" colspan="4">2- معلومات الشهادة (أو المؤهل) المتحصل عليه</td>
        </tr>
        <tr>
            <td><strong>نوع الشهادة:</strong>{{ $diploma }} </td>
            <td><strong>التخصص:</strong> {{ $specialty }} </td>
        </tr>
    </table>
    <table border="0" style="margin:0; font-size: 16px;" width="100%" dir="rtl">
        <tr>
            <td class="section-title" colspan="4">3- تكوين ملف المترشح </td>
        </tr>
        <tr colspan="4">
            <td>☐ طلب خطي مدون عليه رقم الهاتف والعنوان</td>
        </tr>
        <tr colspan="4">
            <td width="100%">☐ نسخة من بطاقة التعريف الوطنية</td>
        </tr>
        <tr colspan="4">
            <td width="100%">☐ شهادة طبية (طب عام - أمراض صدرية)</td>
        </tr>
        <tr colspan="4">
            <td width="100%">☐ نسخة من المؤهل أو شهادة إدارية تثبت مدة التكوين</td>
        </tr>
        <tr colspan="6">
            <td width="100%">☐ نسخة مدرسية تثبت المستوى الدراسي المطلوب</td>
        </tr>
        <tr colspan="4">
            <td width="100%">☐ شهادة إقامة</td>
        </tr>
        <tr colspan="4">
            <td width="100%">☐ شهادة إثبات الوضعية إزاء الخدمة الوطنية</td>
        </tr>
        <tr colspan="6">
            <td width="100%">☐ وصل حقوق التسجيل (200 دج) + حافظة أوراق</td>
        </tr>
        </tr>
    </table>
    <table border="0" style="margin-top:0px; font-size: 10px;" width="100%" dir="rtl">
        <tr>
            <td></td>
            <td align="center" style="font-size: 14px; font-weight: bold;" width="60%">
                مديرية التربية لولاية المغير - de-elmeghaier.education.dz
            </td>
            <td></td>
        </tr>
    </table>
</body>

</html>
