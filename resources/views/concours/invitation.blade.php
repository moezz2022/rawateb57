<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="{{ asset('assets/img/brand/logo57.png')}}" type="image/x-icon"/>
    <link href="https://fonts.googleapis.com/css2?family=Mirza:wght@400;700&display=swap" rel="stylesheet">
    <title>استدعاء</title>
    <style>
       @media print {
            body { margin: 0; -webkit-print-color-adjust: exact; }
        }
        @page { margin: 5mm; size: portrait; }
        body {
            font-family:  'Mirza', sans-serif;
            line-height: 1.8;
            text-align: right;
            direction: rtl;
        }
        .container {
            background-color: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
            font-weight: bold;
        }
        .header h3 {
            font-size: 20px;
            margin: 5px 0;
        }
        .content {
            margin-bottom: 10px;
            font-weight: bold;
        }
        .content p {
            font-size: 22px;
            line-height: 1.6;
            font-weight: bold;
        }
        .header2 h1 {
            font-size: 28px;
            margin-top: 55px;
            text-align: center;
            font-weight: bold;
        }
        .footer p {
            font-size: 22px;
            line-height: 1.6;
            margin-bottom: 5px;
            font-weight: bold;
        }       
        .footer h3 {
            font-size: 18px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table td {
            padding: 8px;
            vertical-align: top;
            font-size: 18px;
            text-align: center;
        }
        table td h3 {
            margin: 0;
            font-size: 18px;
            font-weight: normal;
        }
        td[colspan="3"] {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>الجمهورية الجزائرية الديمقراطية الشعبية</h1>
            <h1>وزارة التربية الوطنية</h1>
        </div>
        <div class="header1">
            <table>
                <tr>
                    <td>
                        <h3>مديرية التربية لولاية المغير</h3>
                    </td>
                    <td>
                        <h3>مدير التربية</h3>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h3>مصلحة التمدرس والامتحانات</h3>
                    </td>
                    <td>
                        <h3>إلى السيد(ة): <span class="highlight">{{ $data['NomArF'] }} {{ $data['PrenomArF'] }}</span>
                        </h3>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h3>مكتب التوجيه والامتحانات</h3>
                    </td>
                    <td>
                        <h3>{{ $data['personalAddress'] }} - {{ $data['residenceMunicipality'] }}</h3>
                    </td>
                </tr>
            </table>
        </div>
        <div class="header2">
            <h1>استـدعـاء</h1>
        </div>
        <div class="content">
            <p> المطلـوب منـكم الحضـور إلى <span >مركـز التكـوين المهنـي المتخـصص زغـاد محمـد المغيـر</span>
                وذلك يـوم <span >الأربعاء الموافق لـ 25 جـانـفي 2025</span>
                عـلى الســاعة <span >الثامنة ونصـف صباحـا</span> من أجـل إجـراء
                <span >الاختـبار المهـني</span> قـصد الإلتـحاق بمنصـب شـغل
                <span class=>{{ $data['con_grade'] }} بمديرية التربية. </span>
            </p>
        </div>
        <div class="footer">
            <p>يرجى الحضور في الموعد المحدد مع إحضار الوثائق اللازمة.</p>
            <h3>* الاستدعاء</h3>
            <h3>* بطاقة التعريف الوطنية</h3>
        </div>
    </div>  
</body>
</html>
