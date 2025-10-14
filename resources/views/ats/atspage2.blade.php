@extends('layouts.master')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Times New Roman", serif;
            background-color: #fff;
            padding: 10px;
            line-height: 1.2;
        }

        .document {
            font-family: "Times New Roman", serif;
            width: 100%;
            margin: 0 auto;
            padding: 10px;
            background: white;
            position: relative;
        }

        #salaires {
            border-collapse: collapse;
            direction: ltr;
            width: 100%;
            border: 1px solid black;
            min-height: 360px;
        }

        #salaires th {
            border: 1px solid black;
            text-align: center;
            width: 20%;
            font-size: 8pt;
            height: 1cm;
            vertical-align: middle;
        }

        #salaires td {
            border-left: 1px solid black;
            border-right: 1px solid black;
            text-align: center;
            height: 0.25cm;
        }

        /* الطباعة */
        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }

            html,
            body {
                margin: 0 !important;
                padding: 0;
                width: 210mm;
                /* العرض */
                height: 297mm;
                /* الارتفاع */
                background: white;
            }

            .document {
                width: 287mm;
                /* أقل شوية باش ما يقطعش */
                min-height: 210mm;
                margin: 0 auto;
                padding: 0;
                background: white;
                position: relative;
            }

            body * {
                visibility: hidden !important;
                /* نخفي كل العناصر */
            }

            .document,
            .document * {
                visibility: visible !important;
                /* نرجع الوثيقة تظهر */
            }

            /* رأس */
            #ats2-head {
                height: 1cm;
                font-size: 11pt;
                margin-bottom: 10px;
            }

            /* جدول الأجور */
            #salaires {
                border-collapse: collapse;
                direction: ltr;
                width: 100%;
                border: 1px solid black;
                min-height: 360px;
            }

            #salaires th {
                border: 1px solid black;
                text-align: center;
                width: 20%;
                font-size: 8pt;
                height: 1cm;
                vertical-align: middle;
            }

            #salaires td {
                border-left: 1px solid black;
                border-right: 1px solid black;
                text-align: center;
                height: 0.25cm;
            }

            #salaires .dinars {
                text-align: right;
                padding-right: 1cm;
            }

            /* نصوص سفلية */
            #ats2-footer3,
            #ats2-footer4 {
                font-size: 9pt;
                margin-top: 15px;
            }

            .ats-text-span {
                color: black !important;
            }
        }

        /* الشاشة */
        @media screen {
            .forprint {
                display: none;
            }

            body {
                font-size: 16px !important;
                color: rgb(0, 0, 0) !important;
            }

            #ats_page2 {
                height: 210mm;
            }

            input {
                border: 1px solid red;
                text-align: center;
                color: red;
            }

            .ats-text-span {
                color: red;
            }

            .edit-btn {
                color: red;
                cursor: pointer;
                animation: blinker 2s linear infinite;
            }
        }

        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }
    </style>
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الأجور</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ شهادة العمل والأجر</span>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">
                    <i class="fa-solid fa-file-contract ml-2"></i>
                    شهادة العمل والأجر:
                    <span id="employeeName" class="badge bg-danger ms-2">
                        {{ isset($employee) ? $employee->NOMA . ' ' . $employee->PRENOMA : 'لم يتم اختيار موظف' }}
                    </span>
                </h3>
                <div class="btn-group">
                    <button onclick="window.location.href='{{ url('/ats/settings') }}'" type="button"
                        class="btn btn-info btn-lg btn-fill">
                        <i class="fas fa-users ml-2"></i> الإعدادات
                    </button>
                    @if (isset($employee))
                        <a href="{{ route('ats.generate1', ['matricule' => $employee->MATRI, 'year' => $year, 'month' => $month, 'duration' => $duration]) }}"
                            class="btn btn-danger btn-lg btn-fill">الوجه الامامي</a>
                        <a href="{{ route('ats.generate2', ['matricule' => $employee->MATRI, 'year' => $year, 'month' => $month, 'duration' => $duration]) }}"
                            class="btn btn-success btn-lg btn-fill">الوجه الخلفي</a>
                        <a href="{{ route('ats.generate3', ['matricule' => $employee->MATRI, 'year' => $year, 'month' => $month, 'duration' => $duration]) }}"
                            class="btn btn-primary btn-lg btn-fill">صفحة الإستئناف</a>
                    @endif
                    <button type="button" class="btn btn-warning btn-lg btn-fill" id="printAtsButton">
                        <i class="fas fa-print mr-2"></i> طباعة
                    </button>
                </div>
            </div>

            <!-- المستند -->
            <div id="document" class="document">
                <!-- العنوان -->
                <div id="ats2-head"
                    style="display: flex; flex-direction: column; margin-top:20px; justify-content: space-between;">
                    <div style="text-align:right;direction:rtl;margin-right:20px;font-size: 14pt;">
                        طبقا لدفتر الحساب يوجد مبلغ الأجور المقبوضة
                        والفترات المناسبة في الجدول التالي :(1)
                    </div>
                    <div style="direction:ltr;text-align:left;font-size: 14pt;">
                        Conformément au livre de paie, le montant de salaires perçus
                        et les périodes correspondantes sont portés sur le tableau ci-aprés :(1)
                    </div>
                </div>

                <!-- جدول الأجور -->
                <table id="salaires" style="margin-top: 20px; font-size: 14pt;">
                    <thead>
                        <tr>
                            <th>
                                <div>الشهر والسنة اللذان يأخذان كمرجع</div>
                                <div>Mois et année de référence</div>
                            </th>
                            <th>
                                <div>عدد أيام المعمول فيها</div>
                                <div>Nombre de jours travaillés</div>
                            </th>
                            <th>
                                <div>سبب الغياب</div>
                                <div>Motif absences</div>
                            </th>
                            <th>
                                <div>الأجر الخاضع للإشتراكات</div>
                                <div>Salaire soumis à cotisation (1)</div>
                            </th>
                            <th style="direction:rtl">
                                <div>مبلغ الإشتراك (حصة العمل)</div>
                                <div style="direction:ltr">Montant de la cotisation (part ouvrières)</div>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($salaries as $salary)
                            <tr>
                                <td>{{ $salary->migration->MONTH }}/{{ $salary->migration->YEAR }}</td>
                                <td> <ats-text class="d-inline-block ms-3"
                                        style="float: center; margin-center: 10px; text-align: center;">
                                        <div class="position-relative text-end">
                                            <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                                onclick="makeEditable(this)"></i>
                                            <span class="ats-text-span d-inline-block fw-bold">
                                                {{ number_format($salary->NBRTRAV, 0, '', '') }}
                                            </span>
                                        </div>
                                    </ats-text></td>
                                <td><ats-text class="d-inline-block ms-3"
                                        style="float: center; margin-center: 10px; text-align: center;">
                                        <div class="position-relative text-end">
                                            <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                                onclick="makeEditable(this)"></i>
                                            <span class="ats-text-span d-inline-block fw-bold">
                                                /</span>
                                        </div>
                                    </ats-text></td>
                                <td>
                                    <ats-text class="d-inline-block ms-3" style="text-align: center;">
                                        <div class="position-relative text-end d-inline-block">
                                            <!-- أيقونة التحرير -->
                                            <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                                onclick="makeEditable(this)"></i>
                                            <span class="ats-text-span d-inline-block fw-bold">
                                                {{ number_format($salary->BRUTSS + ($salary->BRUTSS_RNDM ?? 0) / 3, 2, ',', ' ') }}
                                            </span>

                                            <!-- أيقونة التفاصيل -->
                                            <div class="dropdown d-inline-block ms-1 no-print">
                                                <a class="dropdown-toggle text-primary" href="#" role="button"
                                                    id="dropdownMenuButton{{ $salary->ID_MIGRATION }}"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-info-circle"></i>
                                                </a>
                                                <ul class="dropdown-menu p-2 no-print"
                                                    aria-labelledby="dropdownMenuButton{{ $salary->ID_MIGRATION }}">
                                                    <li>
                                                        <span class="dropdown-item-text no-print">
                                                            الأجر الخاضع للاقتطاع الضمان الاجتماعي
                                                            {{ number_format($salary->BRUTSS, 2, ',', ' ') }}
                                                        </span>
                                                    </li>
                                                    <li>
                                                        <span class="dropdown-item-text no-print">
                                                            المردودية الخاضع للاقتطاع الضمان الاجتماعي
                                                            {{ number_format(($salary->BRUTSS_RNDM ?? 0) / 3, 2, ',', ' ') }}
                                                        </span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </ats-text>
                                </td>
                                <td><ats-text class="d-inline-block ms-3"
                                        style="float: center; margin-center: 10px; text-align: center;">
                                        <div class="position-relative text-end">
                                            <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                                onclick="makeEditable(this)"></i>
                                            <span class="ats-text-span d-inline-block fw-bold">
                                                {{ number_format($salary->RETSS + $salary->RETSS_RNDM / 3, 2, ',', ' ') }}
                                            </span>
                                            <!-- أيقونة التفاصيل -->
                                            <div class="dropdown d-inline-block ms-1 no-print">
                                                <a class="dropdown-toggle text-primary" href="#" role="button"
                                                    id="dropdownMenuButton{{ $salary->ID_MIGRATION }}"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fa fa-info-circle"></i>
                                                </a>
                                                <ul class="dropdown-menu p-2 no-print"
                                                    aria-labelledby="dropdownMenuButton{{ $salary->ID_MIGRATION }}">
                                                    <li>
                                                        <span class="dropdown-item-text no-print">
                                                            اقتطاع من الراتب :
                                                            {{ number_format($salary->RETSS, 2, ',', ' ') }}
                                                        </span>
                                                    </li>
                                                    <li>
                                                        <span class="dropdown-item-text no-print">
                                                            اقتطاع من المردودية:
                                                            {{ number_format(($salary->RETSS_RNDM ?? 0) / 3, 2, ',', ' ') }}
                                                        </span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </ats-text>
                                </td>
                            </tr>
                        @endforeach
                        @for ($i = count($salaries); $i < 12; $i++)
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>

                <!-- الحجم الساعي -->
                <div class="forprint"
                    style="margin-top:5px;display:flex;margin-bottom:10px; justify-content:space-between;font-size: 14pt;">
                    <span>الحجم الساعي اليومي :</span>
                    <ats-text class="d-inline-block ms-3" style="float: center; margin-center: 10px; text-align: center;">
                        <div class="position-relative text-end">
                            <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                onclick="makeEditable(this)"></i>
                            <span class="ats-text-span d-inline-block fw-bold">
                            </span>
                        </div>
                    </ats-text>
                    <span style="flex:1;"></span>
                    <ats-text class="d-inline-block ms-3" style="float: center; margin-center: 10px; text-align: center;">
                        <div class="position-relative text-end">
                            <span class="ats-text-span d-inline-block fw-bold">
                            </span>
                            <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                onclick="makeEditable(this)"></i>
                        </div>
                    </ats-text>
                    <span>:Volume horaire journalier</span>
                </div>

                <!-- التوقيع والختم -->
                <div class="forprint" style="display:flex;margin-bottom:40px;font-size: 14pt;">
                    <div style="width:40%;display:flex;flex-direction:column;justify-content:space-between;">
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span>في</span>
                            <ats-text class="d-inline-block ms-3"
                                style="float: center; margin-center: 10px; text-align: center;">
                                <div class="position-relative text-end">
                                    <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                        onclick="makeEditable(this)"></i>
                                    <span class="ats-text-span d-inline-block fw-bold">
                                        المغير
                                    </span>
                                </div>
                            </ats-text>
                            <span>le</span>
                            <span>حرر ب</span>
                            <ats-text class="d-inline-block ms-3"
                                style="float: center; margin-center: 10px; text-align: center;">
                                <div class="position-relative text-end">
                                    <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                        onclick="makeEditable(this)"></i>
                                    <span class="ats-text-span d-inline-block fw-bold">
                                        {{ \Carbon\Carbon::today()->format('Y/m/d') }} </span>
                                </div>
                            </ats-text>
                            <span>Fait à</span>
                        </div>
                        <div style="display:flex;justify-content:space-between; font-size: 14pt;">
                            <span>إسم ولقب وصفة الموقع</span><span>:</span>
                            <span>Nom, prénom et qualité du signataire</span>
                        </div>
                        <div style="text-align:center;font-weight:bold;">
                            <ats-text class="d-inline-block ms-3"
                                style="float: center; margin-center: 10px; text-align: center;">
                                <div class="position-relative text-end">
                                    <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                        onclick="makeEditable(this)"></i>
                                    <span class="ats-text-span d-inline-block fw-bold">
                                        المدير </span>
                                </div>
                            </ats-text>
                        </div>
                        <div style="display:flex;justify-content:space-around;margin-top:5px; font-size: 14pt;">
                            <span>الإمضاء</span><span>Signature</span>
                        </div>
                    </div>
                    <div style="width:35%;"></div>
                    <div style="width:25%;display:flex;justify-content:center;align-items:center;padding:5px; font-size: 14pt;">
                        <span>ختم صاحب العمل</span><span style="margin:0 5px;">,</span><span>Cachet de l'employeur</span>
                    </div>
                </div>
                <!-- الملاحظات -->
                <div id="ats2-footer3"
                    style="display:flex;justify-content:space-between;margin-top:10px; font-size:11px;">
                    <div style="direction:rtl;">
                        <span>(1) أذكر الأجور كما هي مبينة في بطاقة الأجر الموافقة لـ:</span>
                        <ul style="margin-right:20px;direction:rtl;">
                            <li>الشهر الذي يسبق التوقف عن العمل في حالة مرض.</li>
                            <li>التسعة (09) أشهر التي تسبق تاريخ الولادة في حالة أمومة.</li>
                            <li>الإثني عشرة (12) شهرا التي تسبق التوقف عن العمل في حالة عجز.</li>
                            <li>الإثني عشرة (12) شهرا التي تسبق حادث عمل أو وفاة.</li>
                        </ul>
                    </div>
                    <div style="direction:ltr;margin-left:20px; text-align:left; font-size:11px;">
                        <span style="float:left;">(1) Indique les salaires tels qu'ils figurent sur les fiches de paie
                            correspondantes:</span>
                        <ul style="margin-top:10px; margin-left:20px; direction:ltr; ">
                            <li>au mois précédent l'arrêt de travail, en cas de maladie.</li>
                            <li>aux 09 mois précédant la date d'accouchement en cas de maternité.</li>
                            <li>aux 12 mois précédant l'arrêt de travail, en cas d'invalidité.</li>
                            <li>aux 12 mois précédant l'accident de travail ou le décès.</li>
                        </ul>
                    </div>
                </div>
                <!-- التحذير -->
                <div id="ats2-footer4" style="display:flex;justify-content:space-between;margin-top:5px;">
                    <div style="direction:rtl;">
                        <span style="font-size:large;font-weight:bold;margin-left:5px;">هــام:</span>
                        <span>كل شخص يقوم بتزوير أو يدلي بتصريحات غير صحيحة يعاقب من طرف القانون.</span>
                    </div>
                    <div style="direction:ltr;text-align:left;margin-left:20px;">
                        <span style="font-weight:bold;">IMPORTANT:</span>
                        <span>La loi punit quiconque coupable de fraude ou de fausse déclaration</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function makeEditable(icon) {
            let span = icon.parentElement.querySelector(".ats-text-span");
            const currentText = span.innerText.trim();
            const input = document.createElement("input");
            input.value = currentText;
            input.className = span.className; // للحفاظ على التنسيق

            if (currentText === "") {
                input.style.width = "100px"; // فارغ
            } else {
                input.style.width = (span.offsetWidth + 20) + "px";
            }

            input.addEventListener("blur", function() {
                span.innerText = input.value;
                span.style.display = "inline-block";
                input.replaceWith(span);
            });

            span.replaceWith(input);
            input.focus();
        }
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        $("#printAtsButton").on("click", function() {
            let printContent = document.getElementById("document").innerHTML;
            let newWindow = window.open("", "_blank", "width=1200,height=800");
            newWindow.document.open();
            newWindow.document.write(`
    <html dir="rtl">
    <head>
        <title>شهادة العمل والأجر</title>
        <style>
            @page {
                size: A4 landscape;
                margin: 3.5mm;
            }
                 .no-print {
        display: none !important;
    }
            body {
                font-family: "Times New Roman", serif;
                background-color: #fff;
                padding: 10px;
                line-height: 1.2;
            }
            .document {
                width: 100%;
                margin: 0 auto;
                padding: 10px;
                background: white;
                position: relative;
            }
            #salaires {
                border-collapse: collapse;
                direction: ltr;
                width: 100%;
                border: 1px solid black;
                min-height:360px;
            }
            #salaires th {
                border: 1px solid black;
                text-align: center;
                height: 0.25cm;
                font-size: 8pt;
                vertical-align: middle;
            }
            #salaires td {
                border-left: 1px solid black;
                border-right: 1px solid black;
                text-align: center;
                height: 0.25cm;
                font-size: 8pt;
                vertical-align: middle;
            }
            #salaires th { height: 1cm; }
            #salaires td { height: 0.25cm; }
            #salaires .dinars {
                text-align: right;
                padding-right: 1cm;
            }
        </style>
    </head>
    <body onload="window.focus(); window.print();">
        ${printContent}
    </body>
    </html>
    `);
            newWindow.document.close();
        });
    </script>
@endsection
