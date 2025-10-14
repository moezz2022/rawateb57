<style>
    .page_pay {
        font-family: 'Cairo', sans-serif;
        line-height: 1;
        color: #000;
        direction: rtl;
        position: relative;

    }

    #salary-slip {
        padding: 15px;
    }


    .page-footer {
        margin-top: 20px;
        text-align: center;
    }

    .barcode-stamp {
        margin-bottom: 10px;
    }

    .barcode-stamp img {
        height: 60px;
        width: auto;
        display: block;
        margin: 0 auto;
    }

    .barcode-stamp .code {
        margin-top: 6px;
        font-size: 14px;
        font-weight: bold;
        letter-spacing: 3px;
    }

    .footer-pay {
        font-size: 14px;
        color: #777;
        border-top: 1px dashed #ccc;
        padding-top: 5px;
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
        padding: 5px;
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
        gap: 1%;
    }

    .salary-column {
        width: 49.5%;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .salary-column .table-wrapper {
        width: 100%;
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
        background: #e0e1e6;
        border: 1px solid #ccc;
    }

    @media print {
        body {
            font-family: 'Cairo', sans-serif;
            margin-bottom: 120px;
            padding: 5px;
            direction: rtl;
        }

        .page-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            text-align: center;
            padding: 10px 0;
            border-top: 1px dashed #ccc;
            background: #fff;
        }

        .barcode-stamp img {
            height: 55px;
            width: auto;
            margin: 0 auto;
        }

        .barcode-stamp .code {
            margin-top: 6px;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .footer-pay {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }

        img,
        svg {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        #salary-slip {
            font-family: 'cairo';
            width: 100%;
            text-align: right;
        }


        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #CCC;
            padding: 2px;
            text-align: right;
        }

        table th,
        table td {
            border: 1px solid #ccc;
            padding: 1px;
            text-align: center;
        }

        th,
        td {
            border: 1px solid #CCC;
            padding: 1px;
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .mt-5,
        .my-5 {
            margin-top: 3rem !important;
        }

        .highlight {
            background: none !important;
            font-weight: bold;
            border: 1px solid #CCC;
        }
    }
</style>
<div class="page_pay">
    <div class="text-center">
        <h4>الجمهورية الجزائرية الديمقراطية الشعبية</h4>
        <h4>وزارة التربية الوطنية</h4>
    </div>
    <div>
        <h4>مديرية التربية لولاية المغير</h4>
        <h4>{{ auth()->user()->subGroup ? auth()->user()->subGroup->name : 'لا توجد مجموعة فرعية' }} </h4>
    </div>
    <div class="text-center mb-5">
        <h2>كشف الراتب لشهر {{ $monthName }} {{ $year }}</h2>
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
                <strong>الدرجة:</strong>&nbsp;&nbsp;&nbsp;&nbsp;{{ $rwPaper->ECH }}
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
                <strong>عدد أيام العمل:</strong> &nbsp;&nbsp;&nbsp;&nbsp;{{ number_format($rwPaper['NBRTRAV']) }}
            </td>
        </tr>
    </table>
    <!-- Salary Details -->
    <div class="salary-section">
        <div class="salary-column">
            <div class="table-wrapper">
                <table id="salary-slip">
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
                                <td>{{ number_format($detail['MONTANT'], 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="mrdiyya-row" style="display:none;">
                            @if (!empty($mrdiyya) && $mrdiyya > 0)
                                <td>منحة تحسين الآداء التربوي</td>
                                <td>{{ number_format($BRUTMENS, 2) }}</td>
                            @endif
                        </tr>
                        <tr class="net-salary-only">
                            <td class="highlight"> الخاض للضمان الاجتماعي</td>
                            <td class="highlight">{{ number_format($rwPaper['BRUTSS'], 2) }}</td>
                        </tr>
                        <!-- صف الراتب + المردودية (مخفي افتراضياً) -->
                        @if (!empty($mrdiyya) && $mrdiyya > 0)
                            <tr class="mrdiyya-row" style="display:none;">
                                <td class="highlight">الخاض ض.إ (الراتب + المردودية)</td>
                                <td class="highlight">
                                    {{ number_format($rwPaper['BRUTSS'] + $BRUTSS, 2) }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="table-wrapper">
                <table id="salary-slip">
                    @if (!empty($allocationFamiliales) && count($allocationFamiliales) > 0)
                        <thead>
                            <tr>
                                <th class="highlight">المنح العائلية</th>
                                <th class="highlight">المبلغ (دج)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allocationFamiliales as $allocationFamiliale)
                                <tr>
                                    <td>{{ $allocationFamiliale['ElementName'] }}</td>
                                    <td>{{ number_format($allocationFamiliale['MONTANT'], 2) }}</td>
                                </tr>
                            @endforeach
                            <tr class="net-salary-only">
                                <td class="highlight"> المبلغ الخام</td>
                                <td class="highlight">{{ number_format($rwPaper['TOTGAIN'], 2) }}</td>
                            </tr>
                            @if (!empty($mrdiyya) && $mrdiyya > 0)
                                <tr class="mrdiyya-row" style="display:none;">
                                    <td class="highlight">المبلغ الخام (الراتب + المردودية)</td>
                                    <td class="highlight">
                                        {{ number_format($rwPaper['TOTGAIN'] + $TOTGAIN, 2) }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    @else
                        <tbody>
                            <tr class="net-salary-only">
                                <td class="highlight"> المبلغ الخام</td>
                                <td class="highlight">{{ number_format($rwPaper['TOTGAIN'], 2) }}</td>
                            </tr>
                            @if (!empty($mrdiyya) && $mrdiyya > 0)
                                <tr class="mrdiyya-row" style="display:none;">
                                    <td class="highlight">المبلغ الخام (الراتب + المردودية)</td>
                                    <td class="highlight">
                                        {{ number_format($rwPaper['TOTGAIN'] + $TOTGAIN, 2) }}
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    @endif
                </table>
            </div>
        </div>
        <div class="salary-column">
            <div class="table-wrapper">
                <table id="salary-slip">
                    <thead>
                        <tr>
                            <th class="highlight">الاقتطاعات</th>
                            <th class="highlight">المبلغ (دج)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="net-salary-only">
                            <td>إقتطاع الضمان الاجتماعي</td>
                            <td>{{ number_format($rwPaper['RETSS'], 2) }}</td>
                        </tr>
                        @if (!empty($mrdiyya) && $mrdiyya > 0)
                            <tr class="mrdiyya-row" style="display:none;">
                                <td>إ.الضمان الاجتماعي(الراتب + المردودية)</td>
                                <td>
                                    {{ number_format($rwPaper['RETSS'] + $RETSS / 3, 2) }}
                                </td>
                            </tr>
                        @endif
                        <tr class="net-salary-only">
                            <td>إقتطاع الضريبة على الدخل</td>
                            <td>{{ number_format($rwPaper['RETITS'], 2) }}</td>
                        </tr>
                        @if (!empty($mrdiyya) && $mrdiyya > 0)
                            <tr class="mrdiyya-row" style="display:none;">
                                <td>إ.الضريبة على الدخل(الراتب + المردودية)</td>
                                <td>
                                    {{ number_format($rwPaper['RETITS'] + $RETITS / 3, 2) }}
                                </td>
                            </tr>
                        @endif
                        @foreach ($socialServicesDetails as $detail)
                            <tr>
                                <td>{{ $detail['ElementName'] }}</td>
                                <td>{{ number_format($detail['MONTANT'], 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="net-salary-only">
                            <td class="highlight">مجموع الاقتطاعات</td>
                            <td class="highlight">
                                {{ number_format($rwPaper['RETITS'] + $rwPaper['RETSS'] + $socialServicesDetails->sum('MONTANT'), 2) }}
                            </td>
                        </tr>
                        @if (!empty($mrdiyya) && $mrdiyya > 0)
                            <tr class="mrdiyya-row" style="display:none;">
                                <td class="highlight">م.الاقتطاعات (الراتب + المردودية)</td>
                                <td class="highlight">
                                    {{ number_format($rwPaper['RETITS'] + $rwPaper['RETSS'] + $socialServicesDetails->sum('MONTANT') + $RETITS / 3 + $RETSS / 3, 2) }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div class="table-wrapper">
                    <table id="salary-slip" class="mt-5">
                        <!-- صف الراتب الأساسي -->
                        <tr class="net-salary-only">
                            <td class="highlight">المبلغ الصافي</td>
                            <td class="highlight">
                                {{ number_format($rwPaper['NETPAI'], 2) }}
                            </td>
                        </tr>

                        <!-- صف الراتب + المردودية (مخفي افتراضياً) -->
                        @if (!empty($mrdiyya) && $mrdiyya > 0)
                            <tr class="mrdiyya-row" style="display:none;">
                                <td class="highlight">المبلغ الصافي (الراتب + المردودية)</td>
                                <td class="highlight">
                                    {{ number_format($rwPaper['NETPAI'] + $mrdiyya / 3, 2) }}
                                </td>
                            </tr>
                        @endif
                    </table>

                    <div class="footer text-center">
                        <p>المغير في: {{ \Carbon\Carbon::now()->format('Y/m/d') }}</p>
                        @if (auth()->check() && auth()->user()->role === 'director')
                            <p>المدير</p>
                        @elseif (auth()->check() && auth()->user()->role === 'manager')
                            <p>المسير المالي</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-footer">
        <div class="barcode-stamp">
            <img
                src="data:image/png;base64,{{ DNS1D::getBarcodePNG('00799999000' . $employee->MATRI . $employee->CLECPT, 'C128', 2, 50) }}" />
            <div class="code">{{ '00799999000' . $employee->MATRI . $employee->CLECPT }}</div>
        </div>
        <div class="footer-pay text-center mt-5">
            مديرية التربية لولاية المغير M©B خلية الاعلام الآلي {{ now()->year }}
        </div>
    </div>

</div>
