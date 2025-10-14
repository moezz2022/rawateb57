@extends('layouts.master')
@section('css')
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Times New Roman', serif;
            background-color: #fff;
            padding: 10px;
            line-height: 1.2;
        }

        .document {
            font-family: 'Times New Roman', serif;
            width: 280mm;
            margin: 0 auto;
            padding: 5px;
            background: white;
            position: relative;
        }


        .lb {
            border-left: solid 1px;
            text-align: center;
            font-weight: bold;
            width: 0.8cm;

        }



        .dateur-table {
            border-bottom: solid 1px;
            border-right: solid 1px;
            width: 4cm;

        }

        .dateur-table div,
        .dateur-table span {
            display: inline-block;
            padding: 2px;
        }


        .dateur-table span {
            width: 0em;
            height: 0.6em;
            border-left: 1px solid #000;
            bottom: -4px;
            position: relative;
        }

        .ats-number {
            border-collapse: collapse;
        }

        .ats-number td {
            text-align: center;
            font-weight: bold;
            border-collapse: collapse;
            border-left: 1px solid black;
            border-right: 1px solid black;
            border-bottom: 1px solid black;
        }

        /* أيقونة التحرير */
        .ats-text-span {
            margin: 0 auto;
            color: red;
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

        /* -------- للشاشة -------- */
        @media screen {
            .lb {
                width: 0.8cm;
            }


            .dateur-table {
                width: 4cm;
            }

            .ats-number {
                width: 1.8cm;
            }

            .ats-number td {
                width: 0.5cm;
            }
        }

        /* -------- للطباعة -------- */
        @media print {
            @page {
                size: A4;
                margin: 5.5mm;
                /* يمنع أي فراغ خارجي */
            }

            html,
            body {
                margin-top: 0 !important;
                padding: 0;
                width: 210mm;
                height: 297mm;
                background: white;
            }

            .document {
                width: 200mm;
                min-height: 267mm;
                margin-top: -170px !important;
                padding: 0;
                margin: 10px;
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



            /* تخصيصات الطباعة */
            .lb {
                width: 1cm;
            }

            .dateur-table {
                width: 4cm;
            }

            .ats-number {
                width: 1.8cm;
            }

            .ats-number td {
                width: 0.3cm;
            }

            /* إخفاء عناصر الواجهة الغير لازمة */
            .btn-group,
            .card-header,
            .editable-icon,
            .breadcrumb-header,
            .card-title {
                display: none !important;
            }
        }

        @media print {
            .ats-text-span {
                color: black !important;
            }
        }

        @media screen {
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
                    تصريح بالإستئناف:
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
            <div class="document">
                <!-- Header with logo and ministry info -->
                <div class="logo">
                    <img src="{{ URL::asset('assets/img/brand/CNAS2.png') }}" alt="CNAS Logo"
                        style="width: 100%; height: auto; margin-bottom: 10px;">
                </div>
                <div
                    style="display: flex; justify-content: space-between; align-items: center; font-family: 'Times New Roman'; position: relative;">
                    <!-- ختم الهيئة -->
                    <div
                        style="width: 300px; height: 130px; border: 2px solid #000; border-radius: 12px; text-align: center; display: flex; flex-direction: column; justify-content: center; font-size: 12pt;">
                        <div style="position: relative; top:60px;">
                            <div style="text-align: center; font-size: 8pt;">تأشيرة الهيئة</div>
                        </div>
                    </div>
                    <!-- العنوان -->
                    <div style="text-align: center; flex: 1; padding-top: 30px;">
                        <div style="font-size: 24pt; font-weight: bold;">تصريح بإستئناف</div>
                        <div style="font-size: 24pt; font-weight: bold;"> أو عدم إستئناف العمل</div>
                    </div>
                </div>

                <div style="margin:  10px 0 10px 0; font-size: 14pt;">
                    <span>أنا المستخدم الممضي أسفله أشهد أن المؤمن له :</span>
                </div>

                <!-- الاسم واللقب + رقم التسجيل -->
                <div style="display: flex; justify-content: space-around; margin-bottom: 10px;">
                    <div style="width: 60%; display: flex; flex-direction: column;">

                        <!-- الاسم -->
                        <div style="display: flex; margin-bottom: 5px; font-size: 14pt;">
                            <span>الإسم : </span>
                            <ats-text class="d-inline-block ms-3" style="float: left; margin-left: 10px; text-align: left;">
                                <div class="position-relative text-start">
                                    <span class="ats-text-span d-inline-block fw-bold mr-2">
                                        {{ $employee->NOMA ?? '' }}
                                    </span>
                                    <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                        onclick="makeEditable(this)"></i>
                                </div>
                            </ats-text>
                        </div>

                        <!-- اللقب -->
                        <div style="display: flex; font-size: 14pt;">
                            <span>اللقب : </span>
                            <ats-text class="d-inline-block ms-3" style="float: left; margin-left: 10px; text-align: left;">
                                <div class="position-relative text-start">
                                    <span class="ats-text-span d-inline-block fw-bold mr-2">
                                        {{ $employee->PRENOMA ?? '' }}
                                    </span>
                                    <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                        onclick="makeEditable(this)"></i>
                                </div>
                            </ats-text>
                        </div>
                    </div>

                    <!-- رقم التسجيل -->
                    <div style="width: 40%; text-align: center; font-size: 14pt;">
                        <div
                            style="border: 2px solid black; height: 9mm; margin-left: 15px; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                            <ats-text class="d-block float-center ms-5">
                                <div class="text-center position-relative">
                                    <span class="ats-text-span d-inline-block fw-bold">
                                        {{ $employee->NUMSS ?? '' }}
                                    </span>
                                    <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                        onclick="makeEditable(this)"></i>
                                </div>
                            </ats-text>
                        </div>
                        <div
                            style="position: relative; top: -5px; background: white; display: inline-block; padding: 0 5px;">
                            رقم التسجيل
                        </div>
                    </div>
                </div>

                <!-- تاريخ ومكان الازدياد -->
                <div style="display: flex;justify-content: start;flex-direction: row;">

                    <!-- تاريخ الإزدياد -->
                    <div style="display: flex; flex-direction: row;justify-content:right; font-size: 14pt;">
                        <span>تاريخ الإزدياد:</span>
                        <div style="margin-right: 5px;">

                            <table border="0" cellspacing="0" class="dateur-table" style="direction: ltr; width: 3cm">
                                <tbody>
                                    <tr>
                                        <td id="DATNAIS_AR" style="text-align:center; position:relative;">
                                            <span class="ats-text-span d-inline-block fw-bold"></span>
                                            <i class="fa fa-edit" onclick="makeEditableDate(this, 'DATNAIS_AR')"></i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>

                    <!-- مكان الإزدياد -->
                    <div style="display: flex; flex-direction: row;justify-content: start; margin-right: 20px; font-size: 14pt;">
                        <span>بـ:</span>
                        <ats-text style="margin-right: 5px;" _nghost-c8="">
                            <div style="width: 100%; text-align: center">
                                <ats-text class="d-inline-block ms-3"
                                    style="float: right; margin-right: 10px; text-align: right;">
                                    <div class="position-relative text-end">
                                        <span class="ats-text-span d-inline-block fw-bold">
                                            {{ $employee->LIEUNAIS ?? '' }}
                                        </span>
                                        <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                            onclick="makeEditable(this)"></i>
                                    </div>
                                </ats-text>
                            </div>
                        </ats-text>
                    </div>

                </div>
                <!-- التوقف عن العمل -->
                <div style="display: flex;justify-content: right; flex-direction: row;justify-content: start; font-size: 14pt;">
                    <span style="width: 50px;"></span>
                    <span style="margin: 10px;">المتوقف عن العمل في:</span>

                    <div style="margin: 10px;">
                        <table border="0" cellspacing="0" class="dateur-table" style="direction: ltr; width: 3cm">
                            <tbody>
                                <tr>
                                    <td id="DATSTOP" style="text-align:center; position:relative;">
                                        <span class="ats-text-span d-inline-block fw-bold"></span>
                                        <i class="fa fa-edit" onclick="makeEditableDate(this, 'DATSTOP')"></i>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


                <!-- الاستئناف -->
                <div style="display: flex;justify-content: center; flex-direction: column; font-size: 14pt;">

                    <!-- خيار 1: قد استأنف عمله -->
                    <div style="display: flex; flex-direction: row;justify-content:center;padding-top: 5px;">
                        <label>
                            <input class="check ng-untouched ng-pristine ng-valid" type="checkbox">
                        </label>
                        <div id="c1"></div>
                        <span class="mr-1">قد إستأنــف عملـه في:</span>
                        <span id="sp1" style="width: 50px;"></span>
                        <div style="margin-right: 5px;">
                            <table border="0" cellspacing="0" class="dateur-table"
                                style="direction: ltr; width: 3cm">
                                <tbody>
                                    <tr>
                                        <td id="DATREPRISE" style="text-align:center; position:relative;">
                                            <span class="ats-text-span d-inline-block fw-bold"></span>
                                            <i class="fa fa-edit" onclick="makeEditableDate(this, 'DATREPRISE')"></i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- خيار 2: لم يستأنف عمله -->
                    <div style="display: flex; flex-direction: row;justify-content: center; font-size: 14pt;">
                        <label>
                            <input class="check ng-untouched ng-pristine ng-valid" type="checkbox">
                        </label>
                        <div id="c1"></div>
                        <span class="mr-1">لم يستأنف عمله إلى يومنا هذا</span>
                        <span id="sp2" style="width: 15px;"></span>
                        <div style="margin-right: 5px;">
                            <table border="0" cellspacing="0" class="dateur-table"
                                style="direction: ltr; width: 3cm">
                                <tbody>
                                    <tr>
                                        <td id="DATNOREPRISE" style="text-align:center; position:relative;">
                                            <span class="ats-text-span d-inline-block fw-bold"></span>
                                            <i class="fa fa-edit" onclick="makeEditableDate(this, 'DATNOREPRISE')"></i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>


                <!-- مكان وتاريخ التحرير -->
                <div style="display: flex; justify-content: left; margin:30px 0 0 50px; font-size: 14pt;">
                    <span>حرر ب </span>
                    <span style="margin: 0 10px; font-weight: bold;"> <ats-text class="d-inline-block ms-3"
                            style="float: center; margin-center: 10px; text-align: center;">
                            <div class="position-relative text-end">
                                <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                    onclick="makeEditable(this)"></i>
                                <span class="ats-text-span d-inline-block fw-bold">
                                    المغيــــــــر </span>
                            </div>
                        </ats-text></span>
                    <span>في </span>
                    <span style="margin-left: 10px; font-weight: bold;"> <ats-text class="d-inline-block ms-3"
                            style="float: center; margin-center: 10px; text-align: center;">
                            <div class="position-relative text-end">
                                <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                    onclick="makeEditable(this)"></i>
                                <span class="ats-text-span d-inline-block fw-bold">
                                    {{ \Carbon\Carbon::today()->format('Y/m/d') }} </span>
                            </div>
                        </ats-text></span>
                </div>

                <!-- التوقيع والختم -->
                <div style="display: flex; justify-content: space-between; margin-top:0px; font-size: 14pt;">
                    <div style="width: 45%;">
                        <span>الطبيعة الإجتماعية وختم المستخدم</span>
                    </div>
                    <div style="width: 55%; text-align: center;">
                        <div>إسم وصفة الموقع:</div>
                        <div>..................................</div>
                        <div>الإمضاء</div>
                    </div>
                </div>
                <div id="div4"
                    style="display: flex; flex-direction: column; justify-content: center; margin-top: 0px;">
                    <!-- العنوان -->
                    <div style="display: flex; flex-direction: column; justify-content: flex-start; align-items: center;">
                        <span style="font-weight: bold; font-size: larger;">تصريح شرفي</span>
                        <span>( يملأ من طرف المؤمن له في حالة عدم إستئناف العمل )</span>
                    </div>

                    <!-- الاسم واللقب + رقم التسجيل -->
                    <div style="display: flex; justify-content: space-around; margin-bottom: 10px; font-size: 14pt;">
                        <div style="width: 60%; display: flex; flex-direction: column;">

                            <!-- الاسم -->
                            <div style="display: flex; margin-bottom: 5px;">
                                <span>الإسم : </span>
                                <ats-text class="d-inline-block ms-3"
                                    style="float: left; margin-left: 10px; text-align: left;">
                                    <div class="position-relative text-start">
                                        <span class="ats-text-span d-inline-block fw-bold mr-2">
                                            {{ $employee->NOMA ?? '' }}
                                        </span>
                                        <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                            onclick="makeEditable(this)"></i>
                                    </div>
                                </ats-text>
                            </div>

                            <!-- اللقب -->
                            <div style="display: flex;">
                                <span>اللقب : </span>
                                <ats-text class="d-inline-block ms-3"
                                    style="float: left; margin-left: 10px; text-align: left;">
                                    <div class="position-relative text-start">
                                        <span class="ats-text-span d-inline-block fw-bold mr-2">
                                            {{ $employee->PRENOMA ?? '' }}
                                        </span>
                                        <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                            onclick="makeEditable(this)"></i>
                                    </div>
                                </ats-text>
                            </div>
                        </div>

                        <!-- رقم التسجيل -->
                        <div style="width: 40%; text-align: center; font-size: 14pt;">
                            <div
                                style="border: 2px solid black; height: 9mm; margin-left: 15px; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                <ats-text class="d-block float-center ms-5">
                                    <div class="text-center position-relative">
                                        <span class="ats-text-span d-inline-block fw-bold">
                                            {{ $employee->NUMSS ?? '' }}
                                        </span>
                                        <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                            onclick="makeEditable(this)"></i>
                                    </div>
                                </ats-text>
                            </div>
                            <div
                                style="position: relative; top: -5px; background: white; display: inline-block; padding: 0 5px;">
                                رقم التسجيل
                            </div>
                        </div>
                    </div>

                    <!-- تاريخ التوقف -->
                    <div style="display: flex; flex-direction: row; justify-content: right; margin-top: 15px; font-size: 14pt;">
                        <span style="width: 50%;">أصرح بشرفــي أنني في حالــة توقـــف عن العمـــل منذ تاريـخ
                            :</span>
                        <div style="margin-right: 5px;">
                            <table border="0" cellspacing="0" class="dateur-table"
                                style="direction: ltr; width: 3cm">
                                <tr>
                                    <td id="DATASTOP" style="text-align:center; position:relative;">
                                        <span class="ats-text-span d-inline-block fw-bold"></span>
                                        <i class="fa fa-edit" onclick="makeEditableDate(this, 'DATASTOP')"></i>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- النشاط -->
                    <div class="mr-4" style="margin-top: 15px; font-size: 14pt;">
                        <span>أنني لا أمارس أي نشاط مهني.</span>
                    </div>

                    <!-- الامضاء والتاريخ -->
                    <div style="display: flex; justify-content: left; margin:30px 0 0 50px; font-size: 14pt;">
                        <span>حرر ب </span>
                        <span style="margin: 0 10px; font-weight: bold;"> <ats-text class="d-inline-block ms-3"
                                style="float: center; margin-center: 10px; text-align: center;">
                                <div class="position-relative text-end">
                                    <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                        onclick="makeEditable(this)"></i>
                                    <span class="ats-text-span d-inline-block fw-bold">
                                        المغيــــــــر </span>
                                </div>
                            </ats-text></span>
                        <span>في </span>
                        <span style="margin-left: 10px; font-weight: bold;"> <ats-text class="d-inline-block ms-3"
                                style="float: center; margin-center: 10px; text-align: center;">
                                <div class="position-relative text-end">
                                    <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                        onclick="makeEditable(this)"></i>
                                    <span class="ats-text-span d-inline-block fw-bold">
                                        {{ \Carbon\Carbon::today()->format('Y/m/d') }} </span>
                                </div>
                            </ats-text></span>
                    </div>

                    <!-- ملاحظة -->
                    <div style="margin-top: 15px;">
                        <span style="font-size: 12px; height: 20px; font-size: 14pt;">
                            (1) ضع علامة (X) في الخانة المناسبة.
                        </span>
                    </div>

                </div>

                <div id="div5" style="display: flex; justify-content: center; margin-top: 10px;">
                    <span style="font-size: 14px; height: 20px;  font-family: 'Times New Roman', serif;">
                        * القانون يعاقب كل من يقوم بتزوير أو يدلي بتصريحات غير صحيحة.
                    </span>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('js')
    <script>
        function makeEditable(icon) {
            let span = icon.parentElement.querySelector(".ats-text-span");
            const currentText = span.innerText.trim();

            // إنشاء input
            const input = document.createElement("input");
            input.value = currentText;
            input.className = span.className; // للحفاظ على التنسيق

            // تحديد العرض حسب الحالة
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

        function renderDateDouble(dateString, containerId) {
            const container = document.getElementById(containerId);
            if (!container) return;
            container.innerHTML = "";

            if (!dateString) {
                const table = document.createElement("table");
                table.className = "dateur-table";
                table.border = "0";
                table.cellSpacing = "0";
                table.style.direction = "ltr";

                const tbody = document.createElement("tbody");
                const tr = document.createElement("tr");

                let firstCell = true; // نحتاجه عشان نضيف الأيقونة حتى لو التاريخ فاضي

                for (let j = 0; j < 3; j++) {
                    const td = document.createElement("td");
                    td.className = "lb";
                    td.style.position = "relative";
                    td.style.width = "100px";
                    td.style.textAlign = "center";

                    if (firstCell) {
                        const icon = document.createElement("i");
                        icon.className = "edit-btn fa fa-edit text-danger d-print-none";
                        icon.style.position = "absolute";
                        icon.style.top = "0";
                        icon.style.left = "5px";
                        icon.style.cursor = "pointer";
                        icon.setAttribute("onclick", `makeEditableDate(this, '${containerId}')`);
                        td.appendChild(icon);
                        firstCell = false;
                    }

                    const left = document.createElement("div");
                    left.className = "dateur-left";
                    left.textContent = "";

                    const divider = document.createElement("span");
                    divider.className = "divider";

                    const right = document.createElement("div");
                    right.className = "dateur-right";
                    right.textContent = "";

                    td.appendChild(left);
                    td.appendChild(divider);
                    td.appendChild(right);
                    tr.appendChild(td);
                }

                tbody.appendChild(tr);
                table.appendChild(tbody);
                container.appendChild(table);
                return;
            }

            // لو التاريخ موجود
            const rawParts = dateString.split("/"); // متوقع "dd/mm/YYYY"
            if (rawParts.length !== 3) return;

            const day = String(rawParts[0]).padStart(2, "0");
            const month = String(rawParts[1]).padStart(2, "0");
            const year = String(rawParts[2]).slice(-2).padStart(2, "0");

            let components;
            if (containerId === "DATNAIS_AR" || containerId === "DATASTOP" ||
                containerId === "DATSTOP" || containerId === "DATREPRISE" || containerId === "DATNOREPRISE") {
                components = [year, month, day];
            } else {
                components = [day, month, year];
            }

            const table = document.createElement("table");
            table.className = "dateur-table";
            table.border = "0";
            table.cellSpacing = "0";
            table.style.direction = "ltr";

            const tbody = document.createElement("tbody");
            const tr = document.createElement("tr");
            let firstCell = true;

            components.forEach(comp => {
                let part = String(comp);
                if (part.length % 2 === 1) part = "0" + part;

                for (let i = 0; i < part.length; i += 2) {
                    const td = document.createElement("td");
                    td.className = "lb";
                    td.style.position = "relative";

                    if (firstCell) {
                        const icon = document.createElement("i");
                        icon.className = "edit-btn fa fa-edit text-danger d-print-none";
                        icon.style.position = "absolute";
                        icon.style.top = "0";
                        icon.style.left = "5px";
                        icon.style.cursor = "pointer";
                        icon.setAttribute("onclick", `makeEditableDate(this, '${containerId}')`);
                        td.appendChild(icon);
                        firstCell = false;
                    }

                    const left = document.createElement("div");
                    left.className = "dateur-left";
                    left.textContent = part[i] || "";

                    const divider = document.createElement("span");
                    divider.className = "divider";

                    const right = document.createElement("div");
                    right.className = "dateur-right";
                    right.textContent = part[i + 1] || "";

                    td.appendChild(left);
                    td.appendChild(divider);
                    td.appendChild(right);
                    tr.appendChild(td);
                }
            });

            tbody.appendChild(tr);
            table.appendChild(tbody);
            container.appendChild(table);
        }


        renderDateDouble(
            "{{ !empty($employee->DATSTOP) ? \Carbon\Carbon::parse($employee->DATSTOP)->format('d/m/Y') : '' }}",
            "DATSTOP");
        renderDateDouble(
            "{{ !empty($employee->DATNAIS) ? \Carbon\Carbon::parse($employee->DATNAIS)->format('d/m/Y') : '' }}",
            "DATNAIS_AR");
        renderDateDouble(
            "{{ !empty($employee->DATREPRISE) ? \Carbon\Carbon::parse($employee->DATREPRISE)->format('d/m/Y') : '' }}",
            "DATREPRISE");
        renderDateDouble(
            "{{ !empty($employee->DATNOREPRISE) ? \Carbon\Carbon::parse($employee->DATNOREPRISE)->format('d/m/Y') : '' }}",
            "DATNOREPRISE");
        renderDateDouble(
            "{{ !empty($employee->DATASTOP) ? \Carbon\Carbon::parse($employee->DATASTOP)->format('d/m/Y') : '' }}",
            "DATASTOP");


        function normalizeDate(inputVal) {
            if (!inputVal) return "";

            // نحول كل الفواصل إلى شرطات
            let val = inputVal.replace(/\//g, "-");

            // نقسم التاريخ
            let parts = val.split("-");
            if (parts.length !== 3) return "";

            let [day, month, year] = parts;

            // إذا السنة كانت برقمين فقط → نخليها 19xx أو 20xx
            if (year.length === 2) {
                let yy = parseInt(year, 10);
                year = yy < 50 ? "20" + yy.toString().padStart(2, "0") : "19" + yy.toString().padStart(2, "0");
            }

            // نرجع التاريخ بالشكل المطلوب dd-mm-yyyy
            return `${day.padStart(2, "0")}-${month.padStart(2, "0")}-${year}`;
        }

        function isValidDate(val) {
            const regex = /^(\d{2})-(\d{2})-(\d{4})$/; // dd-mm-yyyy
            if (!regex.test(val)) return false;

            let [_, d, m, y] = val.match(regex);
            d = parseInt(d, 10);
            m = parseInt(m, 10);
            y = parseInt(y, 10);

            const date = new Date(y, m - 1, d);
            return date.getFullYear() === y &&
                date.getMonth() === m - 1 &&
                date.getDate() === d;
        }

        function makeEditableDate(icon, containerId) {
            let container = document.getElementById(containerId);
            if (!container) return;

            const Toast = Swal.mixin({
                toast: true,
                position: 'bottom-start',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });

            // استخراج التاريخ الحالي
            let currentText = Array.from(container.querySelectorAll(".dateur-left, .dateur-right"))
                .map(el => el.innerText.trim())
                .join("");

            // تحويل النص الخام إلى تاريخ كامل (فقط إذا كان 6 أرقام)
            if (currentText.length === 6) {
                if (containerId === "DATNAIS_AR" || containerId === "DATASTOP" ||
                    containerId === "DATSTOP" || containerId === "DATREPRISE" || containerId === "DATNOREPRISE") {
                    // صيغة عربي: yymmdd
                    const yy = currentText.slice(0, 2);
                    const mm = currentText.slice(2, 4);
                    const dd = currentText.slice(4, 6);

                    // هنا نحدد هل السنة 19xx أو 20xx
                    const fullYear = parseInt(yy, 10) < 50 ? "20" + yy : "19" + yy;

                    currentText = dd + "-" + mm + "-" + fullYear;
                } else {
                    // صيغة فرنسي: ddmmyy
                    const dd = currentText.slice(0, 2);
                    const mm = currentText.slice(2, 4);
                    const yy = currentText.slice(4, 6);

                    const fullYear = parseInt(yy, 10) < 50 ? "20" + yy : "19" + yy;

                    currentText = dd + "-" + mm + "-" + fullYear;
                }
            }

            // نفرغ الكونتينر
            container.innerHTML = "";

            // إنشاء input
            const input = document.createElement("input");
            input.type = "text";
            input.placeholder = "jj-mm-aaaa"; // مثال: 15-12-1987
            input.value = currentText;
            input.style.width = "130px";
            input.style.textAlign = "right"; // محاذاة لليمين
            input.style.direction = "ltr"; // اتجاه كتابة إنجليزي (حتى ما يتعكس)
            input.pattern = "\\d{2}-\\d{2}-\\d{4}";

            // عند الحفظ
            function save() {
                let normalized = normalizeDate(input.value);

                if (!isValidDate(normalized)) {
                    Toast.fire({
                        icon: "error",
                        title: "⚠️ الرجاء إدخال تاريخ صحيح (مثال: 15-12-1987)"
                    });
                    input.focus();
                    return;
                }

                // إعادة الرسم (مع /)
                if (containerId === "DATNAIS_AR" || containerId === "DATASTOP" ||
                    containerId === "DATSTOP" || containerId === "DATREPRISE" || containerId === "DATNOREPRISE") {
                    // لو الجدول عربي → نعيد بالرسم الخاص بالعربية
                    renderDateDouble(normalized.replace(/-/g, "/"), containerId);
                } else {
                    // لو الجدول فرنسي → بنفس العادي
                    renderDateDouble(normalized.replace(/-/g, "/"), containerId);
                }
            }

            input.addEventListener("blur", save);
            input.addEventListener("keydown", function(e) {
                if (e.key === "Enter") {
                    save();
                }
            });

            container.appendChild(input);
            input.focus();
        }

        // زر الطباعة
        document.getElementById("printAtsButton").addEventListener("click", () => {
            window.print();
        });
    </script>
@endsection
