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
            padding: 5px;
            line-height: 1.2;
        }

        .document {
            font-family: 'Times New Roman', serif;
            font-size: 14px;
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
            color: rgb(243, 5, 5);
        }

        .edit-btn {
            color: red;
            animation: blinker 2s linear infinite;
        }

        .print-side-note-right {
            display: none;
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
                width: 287mm;
                height: 297mm;
                background: white;
            }

            .document {
                font-size: 14px;
                width: 277mm;
                min-height: 297mm;
                margin-top: -170px !important;
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

            .print-side-note-right {
                display: block;
                position: absolute;
                top: 50%;
                right: -65px;
                /* المسافة عن الهامش الأيمن */
                transform: rotate(90deg) translateY(-50%);
                transform-origin: right top;
                font-size: 8px;
                color: #000;
                white-space: nowrap;
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
                font-size: 14px;

                border: 1px solid red;
                text-align: center;
                color: red;
            }

            .ats-text-span {
                color: rgb(236, 8, 8);
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
                        style="width: 300px; height: 150px; border: 2px solid #000; border-radius: 12px; text-align: center; display: flex; flex-direction: column; justify-content: center; font-size: 12pt;">
                        <div style="position: relative; top:60px;">
                            <div style="text-align: center; font-size: 8pt;">ختم الهيئة</div>
                            <div style="text-align: center; font-size: 8pt;">Cachet de la Structure</div>
                        </div>
                    </div>
                    <!-- العنوان -->
                    <div style="text-align: center; flex: 1; padding-top: 30px; font-size: 20pt;">
                        <div style=" font-weight: bold;">شهـــادة العمـــل والأجــر</div>
                        <div style="font-weight: bold;">
                            ATTESTATION <span style="border-bottom: 2px solid #000000; font-weight: bold;">DE TRAVAIL
                                ET</span> DE SALAIRE
                        </div>
                    </div>
                </div>
                <!-- Employer identification section -->
                <div
                    style="border: 2px solid #000; margin-top: 5px; font-family: 'Times New Roman'; font-size: 14pt; width: 100%; padding: 5px; box-sizing: border-box;">

                    <!-- العنوان -->
                    <div style="text-align: center; margin-bottom: 5px; position: relative; border-bottom: 2px solid #000;">
                        <span
                            style="background: #fff; padding: 0 10px; position: relative; top: 10px; font-size: 16pt; font-weight: bold; display: inline-block;">
                            هوية رب العمل
                        </span>
                        <br>
                        <span
                            style="background: #fff; padding: 0 10px; position: relative; top: 15px; font-size: 16pt; font-weight: bold; display: inline-block;">
                            IDENTIFICATION DE L'EMPLOYEUR
                        </span>
                    </div>
                    <!-- الإسم واللقب -->
                    <table
                        style="direction: rtl; width: 100%; border-collapse: collapse; font-size: 14pt; margin-bottom: 8px;">
                        <tr>
                            <td style="text-align: right; width: 8%; font-size: 14pt;" dir="rtl">الإسم واللقب:</td>
                            <td style="border-bottom: 1px dotted #000; width: 30%; text-align: right; color: #ff00ff;"
                                dir="rtl">
                                <ats-text class="d-block float-start ms-5">
                                    <div class="text-right position-relative">
                                        <span class="ats-text-span d-inline-block fw-bold">
                                            مديرية التربية لولاية المغير
                                        </span>
                                        <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                            onclick="makeEditable(this)"></i>
                                    </div>
                                </ats-text>
                            </td>
                            <td style="border-bottom: 1px dotted #000; width: 50%; text-align: left; color: red;"
                                dir="ltr">
                                <ats-text class="d-block float-start ms-5">
                                    <div class="text-left position-relative">
                                        <span class="ats-text-span d-inline-block fw-bold">
                                            Direction de l'education El-Meghaier
                                        </span>
                                        <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                            onclick="makeEditable(this)"></i>
                                    </div>
                                </ats-text>
                            </td>
                            <td style="text-align: left; width: 35%; font-size: 14pt;" dir="ltr">Nom et Prénom:</td>
                        </tr>
                    </table>
                    <!-- رقم المنخرط -->
                    <table
                        style="direction: ltr; width: 100%; border-collapse: collapse; font-size: 14pt; margin-bottom: 8px;">
                        <tr>
                            <td style="width: 20%; text-align: left; font-size: 14pt;">ou</td>
                            <td style="width: 10%; text-align: left; font-size: 14pt;">N° Adhérent:</td>
                            <td style="width: 30%; border: 1px solid #000; text-align: center; font-weight: bold; ">
                                <ats-text class="d-block float-start ms-5">
                                    <div class="text-center position-relative">
                                        <span class="ats-text-span d-inline-block fw-bold">
                                            5757503940
                                        </span>
                                        <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                            onclick="makeEditable(this)"></i>
                                    </div>
                                </ats-text>
                            </td>
                            <td style="width: 8%; text-align: right; font-size: 14pt;" dir="rtl">رقم المنخرط:</td>
                            <td style="width: 23%; text-align: right; font-size: 14pt;" dir="rtl">أو</td>
                        </tr>
                    </table>
                    <!-- الطبيعة الاجتماعية -->
                    <table
                        style="direction: rtl; width: 100%; border-collapse: collapse; font-size: 14pt; margin-bottom: 8px;">
                        <tr>
                            <td dir="rtl" style="width: 12%; font-size: 14pt; text-align: right;">الطبيعة الإجتماعية:
                            </td>
                            <td style="border-bottom: 1px dotted #000; width: 76%; color: #ff00ff;" dir="rtl">
                                <ats-text class="d-block float-start ms-5">
                                    <div class="text-right position-relative">
                                        <span class="ats-text-span d-inline-block fw-bold">
                                            مؤسسة عمومية ذات طابع إداري
                                        </span>
                                        <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                            onclick="makeEditable(this)"></i>
                                    </div>
                                </ats-text>
                            </td>
                            <td dir="ltr" style="width: 15%; font-size: 14pt; text-align: left;">Raison sociale:</td>
                        </tr>
                    </table>
                    <!-- العنوان -->
                    <table style="direction: rtl; width: 100%; border-collapse: collapse; font-size: 14pt;">
                        <tr>
                            <td dir="rtl" style="width: 0%; font-size: 14pt; text-align: right;">العنوان:</td>
                            <td colspan="2" style="border-bottom: 1px dotted #000; width: 60%; color: #ff00ff;"
                                dir="rtl">
                                <ats-text class="d-block float-start ms-5">
                                    <div class="text-right position-relative">
                                        <span class="ats-text-span d-inline-block fw-bold">
                                            المغير 57000
                                        </span>
                                        <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                            onclick="makeEditable(this)"></i>
                                    </div>
                                </ats-text>
                            </td>
                            <td dir="ltr" style="width: 0%; text-align: left;">Adresse:</td>
                        </tr>
                    </table>
                </div>
                <!-- Employee identification section -->
                <div
                    style="direction: ltr; border: 2px solid #000; margin-top: 5px; font-size: 12pt;  padding: 5px; box-sizing: border-box; width: 100%; font-family: 'Times New Roman', serif;">
                    <!-- العنوان -->
                    <div
                        style="text-align: center; margin-bottom: 5px; position: relative; border-bottom: 2px solid #000;">
                        <span
                            style="background: #fff; padding: 0 10px; position: relative; top: 10px; font-size: 16pt; font-weight: bold; display: inline-block;">
                            هوية الأجير
                        </span>
                        <br>
                        <span
                            style="background: #fff; padding: 0 10px; position: relative; top: 15px; font-size: 16pt; font-weight: bold; display: inline-block;">
                            IDENTIFICATION DU SALARIE </span>
                    </div>
                    <!-- الاسم واللقب -->
                    <table
                        style="direction: ltr; width: 100%; border-collapse: collapse; font-size: 14pt; margin-bottom: 8px;">
                        <tr>
                            <td style="text-align: left; font-size: 14pt; width: 13%;">Nom et Prénom:</td>
                            <td style="border-bottom: 1px dotted #000; width: 78%;">
                                <!-- الاسم الأول -->
                                <ats-text class="d-inline-block ms-3"
                                    style="float: left; margin-left: 10px; text-align: left;">
                                    <div class="position-relative text-start">
                                        <span class="ats-text-span d-inline-block fw-bold">
                                            {{ $employee->NOM ?? '' }} {{ $employee->PRENOM ?? '' }}
                                        </span>
                                        <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                            onclick="makeEditable(this)"></i>
                                    </div>
                                </ats-text>
                                <!-- الاسم الثاني -->
                                <ats-text class="d-inline-block ms-3"
                                    style="float: right; margin-right: 10px; text-align: right;">
                                    <div class="position-relative text-end">
                                        <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                            onclick="makeEditable(this)"></i>
                                        <span class="ats-text-span d-inline-block fw-bold">
                                            {{ $employee->NOMA ?? '' }} {{ $employee->PRENOMA ?? '' }}
                                        </span>
                                    </div>
                                </ats-text>
                            </td>
                            <td style="text-align: right;font-size: 14pt; width: 14%;" dir="rtl">اللقب والإسم :</td>
                        </tr>
                    </table>
                    <!-- رقم التسجيل -->
                    <table
                        style="direction: ltr; width: 100%; border-collapse: collapse; font-size: 14pt; margin-bottom: 8px;">
                        <tr>
                            <td style="width: 15%; text-align: center; font-size: 14pt;"></td>
                            <td style="width: 15%; text-align: center; font-size: 14pt;">N° Immatriculation:</td>
                            <td style="width: 30%; border: 1px solid #000; text-align: center; font-weight: bold;">
                                <ats-text class="d-block
                                float-center ms-5">
                                <div class="text-center position-relative">
                                    <span class="ats-text-span d-inline-block fw-bold">
                                        {{ $employee->NUMSS ?? '' }}
                                    </span>
                                    <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                        onclick="makeEditable(this)"></i>
                                </div>
                                </ats-text>
                            </td>
                            <td style="width: 16%; text-align: center; font-size: 14pt;" dir="rtl">رقم التسجيل:</td>
                            <td style="width: 15%; text-align: center; font-size: 14pt;"></td>
                        </tr>
                    </table>
                    <!-- تاريخ الازدياد -->
                    <table border="0" cellspacing="0" cellpadding="4"
                        style="width:100%; font-family:'Times New Roman', serif; font-size:11pt; border-collapse:collapse;">
                        <tr>
                            <!-- النص الفرنسي يسار -->
                            <td style="width:15%; text-align:left; font-size: 14pt;">Né(e) le:</td>
                            <!-- مربعات التاريخ الأولى -->
                            <td style="text-align:center; position:relative;">
                                <div id="DATNAIS_FR"></div>
                            </td>
                            <!-- à -->
                            <td style="width:1%; text-align:center; font-size: 14pt;">à</td>
                            <!-- مكان الميلاد -->
                            <td style="width:60%; border-bottom:1px dotted #000;">&nbsp;
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
                            </td>

                            <!-- بـ -->
                            <td style="width:1%; text-align:center; font-size: 14pt;">بـ</td>
                            <!-- مربعات التاريخ الثانية -->
                            <td style="text-align:center; position:relative;">
                                <div id="DATNAIS_AR"></div>
                            </td>
                            <!-- النص العربي يمين -->
                            <td style="width:15%; text-align:right; direction:rtl; font-size: 14pt;">تاريخ الإزدياد:</td>
                        </tr>
                    </table>
                    <!-- العنوان -->
                    <table
                        style="direction: ltr; width: 100%; border-collapse: collapse; font-size: 14pt; margin-bottom: 8px;">
                        <tr>
                            <td style="width: 0%; font-size: 14pt;">Adresse:</td>
                            <td style="border-bottom: 1px dotted #000; width: 70%; text-align: left;">
                                <ats-text class="d-inline-block ms-3"
                                    style="float: left; margin-left: 10px; text-align: left;">
                                    <div class="position-relative text-end">
                                        <span class="ats-text-span d-inline-block fw-bold">
                                            {{ $employee->adresse ?? '' }}
                                        </span>
                                        <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                            onclick="makeEditable(this)"></i>
                                    </div>
                                </ats-text>
                            </td>
                            <td style="border-bottom: 1px dotted #000; width: 70%; text-align: right;">
                                <ats-text class="d-inline-block ms-3"
                                    style="float: right; margin-right: 10px; text-align: right;">
                                    <div class="position-relative text-end">
                                        <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                            onclick="makeEditable(this)"></i>
                                        <span class="ats-text-span d-inline-block fw-bold">
                                            {{ $employee->adresse ?? '' }}
                                        </span>
                                    </div>
                                </ats-text>
                            </td>
                            <td style="text-align: right; font-size: 14pt; width: 0%;" dir="rtl">العنوان:</td>
                        </tr>
                    </table>
                    <!-- المهنة -->
                    <table style="direction: ltr; width: 100%; border-collapse: collapse; font-size: 14pt;">
                        <tr>
                            <td style="width: 0%;">Profession:</td>
                            <td style="border-bottom: 1px dotted #000; width: 70%; text-align: left;">
                                <ats-text class="d-inline-block ms-3"
                                    style="float: left; margin-left: 10px; text-align: left;">
                                    <div class="position-relative text-end">
                                        <span class="ats-text-span d-inline-block fw-bold">
                                            {{ $employee->grade->namefr ?? '' }}
                                        </span>
                                        <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                            onclick="makeEditable(this)"></i>
                                    </div>
                                </ats-text>
                            </td>
                            <td style="border-bottom: 1px dotted #000; width: 70%; text-align: center;">
                                <ats-text class="d-inline-block ms-3"
                                    style="float: right; margin-right: 10px; text-align: right;">
                                    <div class="position-relative text-end">
                                        <i class="edit-btn fa fa-edit d-inline-block ms-2 text-danger d-print-none"
                                            onclick="makeEditable(this)"></i>
                                        <span class="ats-text-span d-inline-block fw-bold">
                                            {{ $employee->grade->name ?? '' }}
                                        </span>
                                    </div>
                                </ats-text>
                            </td>
                            <td style="text-align: right; font-size: 14pt; width: 0%;" dir="rtl">المهنة:</td>
                        </tr>
                    </table>
                </div>
                <!-- Rights study information -->
                <div
                    style="direction: ltr; border: 2px solid #000; margin-top: 5px; font-size: 12pt;  padding: 5px; box-sizing: border-box; width: 100%; font-family: 'Times New Roman', serif;">
                    <!-- العنوان -->
                    <div
                        style="text-align: center; margin-bottom: 5px; position: relative; border-bottom: 2px solid #000;">
                        <span
                            style="background: #fff; padding: 0 10px; position: relative; top: 10px; font-size: 16pt; font-weight: bold; display: inline-block;">
                            المعلومات الضرورية لدراسة تخويل الحقوق </span>
                        <br>
                        <span
                            style="background: #fff; padding: 0 10px; position: relative; top: 15px; font-size: 16pt; font-weight: bold; display: inline-block;">
                            RENSEIGNEMENTS NÉCESSAIRES POUR L'ETUDE DES DROITS </span>
                    </div>
                    <table
                        style="width:100%; border-collapse:collapse; font-size:11pt; font-family:'Times New Roman', serif;">
                        <tbody>
                            <!-- تاريخ التوظيف -->
                            <tr>
                                <td style="width:45%; border-bottom:1px dotted #bcbcbc; text-align:left; font-size: 14pt;">
                                    Date de recrutement
                                </td>
                                <td style="text-align:center; position:relative;">
                                    <div id="DATENT"></div>
                                </td>
                                <td
                                    style="width:45%; border-bottom:1px dotted #bcbcbc; text-align:right; font-size: 14pt;">
                                    تاريخ التوظيف
                                </td>
                            </tr>

                            <!-- آخر يوم عمل -->
                            <tr>
                                <td style="width:45%; border-bottom:1px dotted #bcbcbc; text-align:left; font-size: 14pt;">
                                    Date du dernier jour de travail
                                </td>
                                <td style="text-align:center; position:relative;">
                                    <div id="date-lastday"> <span class="ats-text-span d-inline-block fw-bold">
                                            
                                        </span></div>
                                </td>
                                <td
                                    style="width:45%; border-bottom:1px dotted #bcbcbc; text-align:right; font-size: 14pt;">
                                    تاريخ آخر يوم عمل
                                </td>
                            </tr>

                            <!-- تاريخ استئناف العمل -->
                            <tr>
                                <td style="width:45%; border-bottom:1px dotted #bcbcbc; text-align:left; font-size: 14pt;">
                                    Date de reprise de travail
                                </td>
                                <td style="text-align:center; position:relative;">
                                    <div id="date-reprise"></div>
                                </td>
                                <td
                                    style="width:45%; border-bottom:1px dotted #bcbcbc; text-align:right; font-size: 14pt;">
                                    تاريخ إستئناف العمل
                                </td>
                            </tr>
                            <!-- لم يستأنف العمل -->
                            <tr>
                                <td style="width:45%; border-bottom:1px dotted #bcbcbc; text-align:left; font-size: 14pt;">
                                    L'intéressé(e) n'a pas repris son travail à ce jour
                                </td>
                                <td style="text-align:center; position:relative;">
                                    <div id="date-noreprise"></div>
                                </td>
                                <td
                                    style="width:45%; border-bottom:1px dotted #bcbcbc; text-align:right; font-size: 14pt;">
                                    المعني(ة) بالأمر لم يستأنف العمل إلى يومنا هذا
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <!-- Work stoppage under 6 months or maternity -->
                <div
                    style="direction: ltr; border: 2px solid #000; margin-top: 5px; font-size: 12pt;  padding: 5px; box-sizing: border-box; width: 100%; font-family: 'Times New Roman', serif;">
                    <!-- العنوان -->
                    <div
                        style="text-align: center; margin-bottom: 5px; position: relative; border-bottom: 2px solid #000;">
                        <span
                            style="background: #fff; padding: 0 10px; position: relative; top: 10px; font-size: 14pt; font-weight: bold; display: inline-block;">
                            في حالة التوقف عن العمل لمدة تقل عن 06 أشهر أو في حالة الأمومة
                        </span>
                        <br>
                        <span
                            style="background: #fff; padding: 0 10px; position: relative; top: 15px; font-size: 14pt; font-weight: bold; display: inline-block;">
                            EN CAS D'ARETE DE TRAVAIL D'UNE DURÉE INFÉRIEURE A 06 MOIS ET EN CAS DE MATERNITÉ</span>
                    </div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 14pt; margin-bottom: 10px;">
                        <tr>
                            <!-- فرنسي -->
                            <td style="width:22%;  text-align: left; font-size: 14pt;">
                                l'assuré(e) a travaillé pendant
                            </td>
                            <td style="width:10%;  text-align: left;">
                                <table class="ats-number" style="direction: ltr; margin: auto;">
                                    <tbody>
                                        <tr>
                                            <td><span>&nbsp;</span></td>
                                            <td><span>&nbsp;</span></td>

                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style=" padding: 5px; font-size: 14pt;">jours</td>
                            <td style="width:10%;  text-align: left;">
                                <table class="ats-number" style="direction: ltr; margin: auto;">
                                    <tbody>
                                        <tr>
                                            <td><span>&nbsp;</span></td>
                                            <td><span>&nbsp;</span></td>
                                            <td><span>&nbsp;</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style=" padding: 5px; font-size: 14pt;">heures</td>
                            <td style="width:  5%;"></td>
                            <!-- عربي -->
                            <td style=" padding: 5px; font-size: 14pt;">ساعة</td>
                            <td style="width: 10%; padding: 5px;">
                                <table class="ats-number" style="direction: ltr; margin: auto;">
                                    <tbody>
                                        <tr>
                                            <td><span>&nbsp;</span></td>
                                            <td><span>&nbsp;</span></td>
                                            <td><span>&nbsp;</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style=" padding: 5px; font-size: 14pt;">يوم</td>
                            <td style="width: 10%; padding: 5px;">
                                <table class="ats-number" style="direction: ltr; margin: auto;">
                                    <tbody>
                                        <tr>
                                            <td><span>&nbsp;</span></td>
                                            <td><span>&nbsp;</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td style="width: 15%; padding: 5px; text-align: right; font-size: 14pt;">
                                المؤمن اشتغل لمدة
                            </td>
                        </tr>
                    </table>
                    <!-- جدول الفترات -->
                    <table style="width: 100%; margin-bottom: 12px; border-collapse: collapse; font-size: 14pt;">
                        <tr>
                            <td style="width: 15%; font-size: 14pt;">du:</td>
                            <td style="width:20%; text-align:center;">
                            <td style="text-align:center; position:relative;">
                                <div id="Materniter-resume-dateFR"></div>
                            </td>
                            </td>
                            <td style="width: 15%;  padding-right: 15px; font-size: 14pt;">au:</td>
                            <td style="width:20%; text-align:center;">
                            <td style="text-align:center; position:relative;">
                                <div id="Materniter-stop-dateFR"></div>
                            </td>
                            </td>
                            <td style="width: 35%;"></td>
                            <td style="width:20%; text-align:center;">
                            <td style="text-align:center; position:relative;">
                                <div id="Materniter-stop-date"></div>
                            </td>
                            </td>
                            <td style="width: 15%; direction: rtl; padding-right: 50px; font-size: 14pt;">إلى:</td>
                            <td style="width:20%; text-align:center;">
                            <td style="text-align:center; position:relative;">
                                <div id="Materniter-resume-date"></div>
                            </td>
                            </td>
                            <td
                                style="width: 15%; direction: rtl; padding-left: 15px; padding-right: 50px; font-size: 14pt;">
                                من:</td>
                        </tr>
                    </table>
                    <!-- النصوص التوضيحية -->
                    <table style="width: 100%; font-size: 14pt;">
                        <tr>
                            <td style="text-align: left;">Selon le cas</td>
                            <td></td>
                            <td style="text-align: right;">حسب الحالة</td>
                        </tr>
                        <tr>
                            <td style="font-size: 11px; vertical-align: top; line-height: 1.4;">
                                <div style="text-align: left;">Au cours du trimestre civil qui précède la date de la
                                    première</div>
                                <div style="text-align: left;"> constatation de la
                                    maladie ou les trois (03) mois qui précèdent</div>
                                <div style="text-align: left;"> la date de la grossesse</div>
                                <div style="text-align: left;">Au cours des douze (12) mois (de date à date) précédant la
                                    date </div>
                                <div style="text-align: left;">de la première
                                    constatation de la maladie ou de la date de constatation</div>
                                <div style="text-align: left;"> de la grossesse</div>
                            </td>
                            <td></td>
                            <td style="direction: rtl; font-size: 12px; vertical-align: top; line-height: 1.8;">
                                <div>خلال الثلاثي المدني الأخير الذي يسبق تاريخ أول معاينة للمرض أو خلال</div>
                                <div> الثلاثة (03) أشهر التي تسبق تاريخ معاينة الحمل</div>
                                <div>خلال الإثني عشرة (12) شهرا (من تاريخ إلى تاريخ) التي تسبق تاريخ </div>
                                <div>المعاينة للمرض أو الحمل</div>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- Work stoppage over 6 months or disability -->
                <div style="width:100%; margin:0 auto; position: relative;">
                    <div class="print-side-note-right">
                        IMP.CNAS 03-2021 - AS.08
                    </div>
                    <div
                        style="direction: ltr; border: 2px solid #000; margin-top: 5px; font-size: 12pt;  padding: 5px; box-sizing: border-box; width: 100%; font-family: 'Times New Roman', serif;">

                        <!-- العنوان -->
                        <div
                            style="text-align: center; margin-bottom: 5px; position: relative; border-bottom: 2px solid #000;">
                            <span
                                style="background: #fff; padding: 0 10px; position: relative; top: 10px; font-size: 16pt; font-weight: bold; display: inline-block;">
                                في حالة التوقف عن العمل لأكثر من 06 أشهر أو في حالة العجز
                            </span>
                            <br>
                            <span
                                style="background: #fff; padding: 0 10px; position: relative; top: 15px; font-size: 16pt; font-weight: bold; display: inline-block;">
                                EN CAS D'ARETE DE TRAVAIL DÉPASSANT 06 MOIS OU EN CAS D'INVALIDITE</span>
                        </div>
                        <table style="width: 100%; border-collapse: collapse; font-size: 14pt; margin-bottom: 10px;">
                            <tr>
                                <!-- فرنسي -->
                                <td style="width:22%;  text-align: left; font-size: 14pt;">
                                    l'assuré(e) a travaillé pendant
                                </td>
                                <td style="width:10%;  text-align: left;">
                                    <table class="ats-number" style="direction: ltr; margin: auto;">
                                        <tbody>
                                            <tr>
                                                <td><span>&nbsp;</span></td>
                                                <td><span>&nbsp;</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td style=" padding: 5px; font-size: 14pt;">jours</td>
                                <td style="width:10%;  text-align: left;">
                                    <table class="ats-number" style="direction: ltr; margin: auto;">
                                        <tbody>
                                            <tr>
                                                <td><span>&nbsp;</span></td>
                                                <td><span>&nbsp;</span></td>
                                                <td><span>&nbsp;</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td style=" padding: 5px; font-size: 14pt;">heures</td>
                                <td style="width:  5%;"></td>
                                <!-- عربي -->
                                <td style=" padding: 5px; font-size: 14pt;">ساعة</td>
                                <td style="width: 10%; padding: 5px;">
                                    <table class="ats-number" style="direction: ltr; margin: auto;">
                                        <tbody>
                                            <tr>
                                                <td><span>&nbsp;</span></td>
                                                <td><span>&nbsp;</span></td>
                                                <td><span>&nbsp;</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td style=" padding: 5px; font-size: 14pt;">يوم</td>
                                <td style="width: 10%; padding: 5px;">
                                    <table class="ats-number" style="direction: ltr; margin: auto;">
                                        <tbody>
                                            <tr>
                                                <td><span>&nbsp;</span></td>
                                                <td><span>&nbsp;</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                <td style="width: 15%; padding: 5px; text-align: right; font-size: 14pt;">
                                    المؤمن اشتغل لمدة
                                </td>
                            </tr>
                        </table>
                        <!-- جدول الفترات -->
                        <table style="width: 100%; margin-bottom: 12px; border-collapse: collapse; font-size: 14pt;">
                            <tr>
                                <td style="width: 15%;font-size: 14pt; ">du:</td>
                                <td style="width:20%; text-align:center;">
                                <td style="text-align:center; position:relative;">
                                    <div id="Invalidite-resume-dateFR"></div>
                                </td>
                                </td>
                                <td style="width: 15%;  padding-right: 15px; font-size: 14pt;">au:</td>
                                <td style="width:20%; text-align:center;">
                                <td style="text-align:center; position:relative;">
                                    <div id="Invalidite-stop-dateFR"></div>
                                </td>
                                </td>
                                <td style="width: 35%;"></td>
                                <td style="width:20%; text-align:center;">
                                <td style="text-align:center; position:relative;">
                                    <div id="Invalidite-stop-date"></div>
                                </td>
                                </td>
                                <td style="width: 15%; direction: rtl; padding-right: 50px; font-size: 14pt;">إلى:</td>
                                <td style="width:20%; text-align:center;">
                                <td style="text-align:center; position:relative;">
                                    <div id="Invalidite-resume-date"></div>
                                </td>
                                </td>
                                <td
                                    style="width: 15%; direction: rtl; padding-left: 15px; padding-right: 50px; font-size: 14pt;">
                                    من:</td>
                            </tr>
                        </table>
                        <!-- النصوص التوضيحية -->
                        <table style="width: 100%; font-size: 14pt;">
                            <tr>
                                <td style="font-size: 11px; vertical-align: top; line-height: 1.4;">
                                    <div style="text-align: left;">au cours des (12) mois ou des (03) années (de date à
                                        date)
                                    </div>
                                    <div style="text-align: left;"> précédant la date de la premiére constatation de la
                                        maladie
                                    </div>
                                </td>
                                <td></td>
                                <td style="direction: rtl; font-size: 12px; vertical-align: top; line-height: 1.8;">
                                    <div>خلال الإثني عشرة (12) شهرا (من تاريخ إلى تاريخ) أو الثلاث</div>
                                    <div> (03) سنوات التي تسبق تاريخ أول معاينة للمرض</div>
                                </td>
                            </tr>
                        </table>
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
            if (containerId === "DATNAIS_AR" || containerId === "DATENT" ||
                containerId === "date-lastday" || containerId === "date-reprise" || containerId === "date-noreprise") {
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
            "{{ !empty($employee->DATNAIS) ? \Carbon\Carbon::parse($employee->DATNAIS)->format('d/m/Y') : '' }}",
            "DATNAIS_FR");
        renderDateDouble(
            "{{ !empty($employee->DATNAIS) ? \Carbon\Carbon::parse($employee->DATNAIS)->format('d/m/Y') : '' }}",
            "DATNAIS_AR");
        renderDateDouble(
            "{{ !empty($employee->DATENT) ? \Carbon\Carbon::parse($employee->DATENT)->format('d/m/Y') : '' }}",
            "DATENT");
        renderDateDouble(
            "{{ !empty($employee->datelastday) ? \Carbon\Carbon::parse($employee->datelastday)->format('d/m/Y') : '' }}",
            "date-lastday");
        renderDateDouble(
            "{{ !empty($employee->datereprise) ? \Carbon\Carbon::parse($employee->datereprise)->format('d/m/Y') : '' }}",
            "date-reprise");
        renderDateDouble(
            "{{ !empty($employee->datenoreprise) ? \Carbon\Carbon::parse($employee->datenoreprise)->format('d/m/Y') : '' }}",
            "date-noreprise");
        renderDateDouble(
            "{{ !empty($employee->Mresumedate) ? \Carbon\Carbon::parse($employee->Mresumedate)->format('d/m/Y') : '' }}",
            "Materniter-resume-date");
        renderDateDouble(
            "{{ !empty($employee->MresumedateFR) ? \Carbon\Carbon::parse($employee->MresumedateFR)->format('d/m/Y') : '' }}",
            "Materniter-resume-dateFR");
        renderDateDouble(
            "{{ !empty($employee->Mstopdate) ? \Carbon\Carbon::parse($employee->Mstopdate)->format('d/m/Y') : '' }}",
            "Materniter-stop-date");
        renderDateDouble(
            "{{ !empty($employee->MstopdateFR) ? \Carbon\Carbon::parse($employee->MstopdateFR)->format('d/m/Y') : '' }}",
            "Materniter-stop-dateFR");
        renderDateDouble(
            "{{ !empty($employee->Iresumedate) ? \Carbon\Carbon::parse($employee->Iresumedate)->format('d/m/Y') : '' }}",
            "Invalidite-resume-date");
        renderDateDouble(
            "{{ !empty($employee->IresumedateFR) ? \Carbon\Carbon::parse($employee->IresumedateFR)->format('d/m/Y') : '' }}",
            "Invalidite-resume-dateFR");
        renderDateDouble(
            "{{ !empty($employee->Istopdate) ? \Carbon\Carbon::parse($employee->Istopdate)->format('d/m/Y') : '' }}",
            "Invalidite-stop-date");
        renderDateDouble(
            "{{ !empty($employee->IstopdateFR) ? \Carbon\Carbon::parse($employee->IstopdateFR)->format('d/m/Y') : '' }}",
            "Invalidite-stop-dateFR");

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
            // نسمح بالحقل الفارغ مباشرة
            if (!val || val.trim() === "") {
                return true;
            }
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
                if (containerId === "DATNAIS_AR" || containerId === "DATENT" ||
                    containerId === "date-lastday" || containerId === "date-reprise" || containerId === "date-noreprise") {
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
                if (containerId === "DATNAIS_AR" || containerId === "DATENT" ||
                    containerId === "date-lastday" || containerId === "date-reprise" || containerId === "date-noreprise") {
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
