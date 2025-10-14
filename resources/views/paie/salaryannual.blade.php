<html dir="rtl" lang="ar">

<head>
    <meta charset="utf-8">
    <title>كشف الراتب السنوي</title>
    <style>
        .page_pay {
            font-family: 'Cairo', sans-serif;
            margin: 5px;
            line-height: 1;
            color: #000;
        }

        .text-center {
            text-align: center;
        }

        .header h4,
        .header h3 {
            margin: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px
        }

        table th,
        table td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: center;
        }

        .details-table td {
            text-align: right;
            border: none;
        }

        .table-spacing {
            margin-top: 20px;
        }


        .salary-section {
            display: flex;
            justify-content: space-around;
        }

        .salary-section .table-wrapper {
            width: 48%;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
        }

        .footer p {
            margin: 5px 0;
        }

        .highlight {
            font-weight: bold;
            background: #edeff7;
            border: 1px solid #ccc;

        }

        .pink-text {
            color: #d63384;
        }

        @media print {
            body {
                font-family: 'Cairo', sans-serif;
                margin: 0;
                padding: 5px;
                direction: rtl;
            }

            #salary-slip {
                font-family: 'cairo';
                margin: auto;
                width: 100%;
                text-align: right;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                border: 1px solid black;
                padding: 5px;
                text-align: right;
            }

            .text-center {
                text-align: center;
            }

            .highlight {
                font-weight: bold;
                background: #edeff7;
            }
        }
    </style>
    <div class="page_pay">
        <!-- Header Section -->
        <div class="text-center">
            <h4>الجمهورية الجزائرية الديمقراطية الشعبية</h4>
            <h4>وزارة التربية الوطنية</h4>
        </div>
        <div>
            <h4>مديرية التربية لولاية المغير</h4>
            <h4>{{ auth()->user()->subGroup ? auth()->user()->subGroup->name : 'لا توجد مجموعة فرعية' }} </h4>
        </div>
        <div class="text-center mb-5">
            <h2>كشف الراتب السنوي </h2>
            <h3>لسنة {{ $year }}</h3>
        </div>

        <!-- Employee Details -->
        <table class="details-table">
            <tr>
                <td style="border-bottom: 1px solid #CCC;">
                    <strong>اللقب:</strong> &nbsp;&nbsp;&nbsp;&nbsp;{{ $employee->NOMA }}
                </td>
                <td style="border-bottom: 1px solid #CCC; ">
                    <strong>الاسم:</strong>&nbsp;&nbsp;&nbsp;&nbsp;{{ $employee->PRENOMA }}
                </td>
                <td style="border-bottom: 1px solid #CCC; ">
                    <strong>الوظيفة:</strong>&nbsp;&nbsp;&nbsp;&nbsp; {{ $employee->grade->name ?? 'غير متوفر' }}
                </td>

            </tr>
            <tr>
                <td style="border-bottom: 1px solid #CCC; ">
                    <strong>الحالة العائلية:</strong>&nbsp;&nbsp;&nbsp;&nbsp; {{ $employee->SITFAM }}
                </td>
                <td style="border-bottom: 1px solid #CCC;">
                    <strong>الصنف:</strong> &nbsp;&nbsp;&nbsp;&nbsp;{{ $rwPaper->CATEG }}
                </td>
                <td style="border-bottom: 1px solid #CCC;">
                    <strong>الدرجة:</strong>&nbsp;&nbsp;&nbsp;&nbsp;{{ $employee->ECH }}
                </td>
            </tr>
            <tr>
                <td style="border-bottom: 1px solid #CCC;">
                    <strong>رقم الحساب:</strong> &nbsp;&nbsp;&nbsp;&nbsp;{{ $employee->MATRI }}
                </td>
                <td style="border-bottom: 1px solid #CCC;">
                    <strong>رقم الضمان الاجتماعي:</strong> &nbsp;&nbsp;&nbsp;&nbsp;{{ $employee->NUMSS }}
                </td>
                <td style="border-bottom: 1px solid #CCC;">
                    <strong>تاريخ التوظيف:</strong>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    {{ \Carbon\Carbon::parse($employee->DATENT)->format('Y/m/d') }}
                </td>
            </tr>
        </table>

        <!-- Salary Details -->
        <div class="salary-section">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th class="highlight">مكونات الراتب</th>
                            <th class="highlight">المبلغ (دج)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salaryDetails as $detail)
                            <tr>
                                <td>{{ $detail['ElementName'] }}</td>
                                <td>{{ number_format($detail['MONTANT'] * 12, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="highlight">مجموع المبلغ الخام</td>
                            <td class="highlight">{{ number_format($rwPaper['TOTGAIN'] * 12, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th class="highlight">الاقتطاعات</th>
                            <th class="highlight">المبلغ (دج)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>إقتطاع الضمان الاجتماعي</td>
                            <td>{{ number_format($rwPaper['RETSS'] * 12, 2) }}</td>
                        </tr>
                        <tr>
                            <td>إقتطاع الضريبة الدخل</td>
                            <td>{{ number_format($rwPaper['RETITS'] * 12, 2) }}</td>
                        </tr>
                        <tr>
                            <td>مجموع الاقتطاعات</td>
                            <td>{{ number_format($rwPaper['RETITS'] * 12 + $rwPaper['RETSS'] * 12, 2) }}</td>
                        </tr>
                        @foreach ($socialServicesDetails as $detail)
                            <tr>
                                <td>{{ $detail['ElementName'] }}</td>
                                <td>{{ number_format($detail['MONTANT'] * 12, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="highlight"> المبلغ الصافي</td>
                            <td class="highlight">{{ number_format($rwPaper['NETPAI'] * 12, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
                <!-- Footer -->
                <div class="footer text-center">
                    <p>المغير في: {{ \Carbon\Carbon::now()->format('Y/m/d') }}</p>
                    <p>المسير المالي</p>
                </div>
            </div>
        </div>

    </div>
