<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title>شهادة العمل والأجر</title>
    <style>
        body {
            margin: 0;
            font-family: DejaVu Sans, sans-serif;
            direction: rtl;
            font-size: 12pt;
        }

        .certificate {
            position: relative;
            width: 210mm;
            height: 297mm;
            background: url("{{ public_path('assets/img/brand/CANS01.jpg') }}") no-repeat center center;
            background-size: cover;
        }

        .field {
            position: absolute;
            font-weight: bold;
            color: #000;
            white-space: nowrap;
        }

        /* ----------- هوية رب العمل ----------- */
        .employer-name {
            top: 101mm;
            right: 38mm;
        }

        /* اسم المؤسسة بالعربية */
        .employer-nameFR {
            top: 101mm;
            left: 41mm;
        }

        /* اسم المؤسسة بالفرنسية */
        .employer-num {
            top: 114mm;
            left: 125mm;
        }

        /* رقم الانخراط */
        .employer-rs {
            top: 123mm;
            right: 40mm;
        }

        /* الطبيعة القانونية بالعربية */
        .employer-rsFR {
            top: 123mm;
            left: 40mm;
        }

        /* الطبيعة القانونية بالفرنسية */
        .employer-adresse {
            top: 132mm;
            right: 28mm;
        }

        /* العنوان بالعربية */
        .employer-adresseFR {
            top: 132mm;
            left: 28mm;
        }

        /* العنوان بالفرنسية */

        /* ----------- هوية الأجير ----------- */
        .employee-name {
            top: 166mm;
            right: 35mm;
        }

        /* الاسم بالعربية */
        .employee-nameFR {
            top: 166mm;
            left: 50mm;
        }

        /* الاسم بالفرنسية */
        .employee-num {
            top: 181mm;
            left: 120mm;
        }

        /* رقم الضمان الاجتماعي */
        .birth-date {
            top: 191mm;
            right: 36mm;
            letter-spacing: 2mm;
        }

        /* تاريخ الميلاد عربي */
        .birth-dateFR {
            top: 191mm;
            left: 18mm;
            letter-spacing: 2mm;
        }

        /* تاريخ الميلاد فرنسي */
        .employee-NE {
            top: 191mm;
            right: 105mm;
        }

        /* مكان الميلاد */
        .address {
            top: 200mm;
            right: 25mm;
            max-width: 90mm;
        }

        /* العنوان عربي */
        .addressFR {
            top: 200mm;
            left: 25mm;
            max-width: 90mm;
        }

        /* العنوان فرنسي */
        .profession {
            top: 209mm;
            right: 25mm;
        }

        /* المهنة بالعربية */
        .professionFR {
            top: 209mm;
            left: 32mm;
        }

        /* المهنة بالفرنسية */

        /* ----------- معلومات العمل ----------- */
        .recruitment-date {
            top: 242mm;
            left: 102mm;
            letter-spacing: 2mm;
        }

        /* تاريخ التوظيف */
        .last-work-date {
            top: 251mm;
            left: 102mm;
            letter-spacing: 2mm;
        }

        /* آخر يوم عمل */
        .resume-date {
            top: 260mm;
            left: 102mm;
            letter-spacing: 2mm;
        }

        /* تاريخ الاستئناف */
        .stop-date {
            top: 269mm;
            left: 102mm;
            letter-spacing: 2mm;
        }

        /* ----------- الأمومة ----------- */
        .Materniter-resume-date {
            top: 310mm;
            right: 60mm;
            letter-spacing: 2mm;
        }

        .Materniter-stop-date {
            top: 310mm;
            right: 124mm;
            letter-spacing: 2mm;
        }

        .Materniter-resume-dateFR {
            top: 310mm;
            left: 84mm;
            letter-spacing: 2mm;
        }

        .Materniter-stop-dateFR {
            top: 310mm;
            left: 16mm;
            letter-spacing: 2mm;
        }

        /* ----------- العجز ----------- */
        .Invalidite-resume-date {
            top: 385mm;
            right: 60mm;
            letter-spacing: 2mm;
        }

        .Invalidite-stop-date {
            top: 385mm;
            right: 124mm;
            letter-spacing: 2mm;
        }

        .Invalidite-resume-dateFR {
            top: 385mm;
            left: 84mm;
            letter-spacing: 2mm;
        }

        .Invalidite-stop-dateFR {
            top: 385mm;
            left: 16mm;
            letter-spacing: 2mm;
        }
    </style>
</head>

<body>
    <div class="certificate">
        {{-- --------- هوية رب العمل --------- --}}
        <div class="field employer-name" contenteditable="true">
            {{ $employer->NOM ?? 'مديرية التربية لولاية المغير' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        <div class="field employer-nameFR" contenteditable="true">
            {{ $employer->NOM ?? "Direction de l'education El-Meghaier" }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        <div class="field employer-num" contenteditable="true">
            {{ $employer->NUM ?? '5757503940' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        <div class="field employer-rs" contenteditable="true">
            {{ $employer->RAISONSOC ?? 'مؤسسة عمومية ذات طابع إداري' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        <div class="field employer-rsFR" contenteditable="true">
            {{ $employer->RAISONSOC ?? '' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        <div class="field employer-adresse" contenteditable="true">
            {{ $employer->ADRESSE ?? 'المغير 57000' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        <div class="field employer-adresseFR" contenteditable="true">
            {{ $employer->ADRESSE ?? '' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        {{-- --------- هوية الأجير --------- --}}
        <div class="field employee-name" contenteditable="true">
            {{ $employee->NOMA ?? '' }} {{ $employee->PRENOMA ?? '' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        <div class="field employee-nameFR" contenteditable="true">
            {{ $employee->NOM ?? '' }} {{ $employee->PRENOM ?? '' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        <div class="field employee-num" contenteditable="true">
            {{ $employee->NUMSS ?? '' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        <div class="field birth-date" contenteditable="true">
            {{ !empty($employee->DATNAIS) ? \Carbon\Carbon::parse($employee->DATNAIS)->format('d / m / Y') : '' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        <div class="field employee-NE" contenteditable="true">
            {{ $employee->NE ?? '' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        <div class="field birth-dateFR" contenteditable="true">
            {{ !empty($employee->DATNAIS) ? \Carbon\Carbon::parse($employee->DATNAIS)->format('d / m / Y') : '' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        <div class="field address" contenteditable="true">
            {{ $employee->ADRESSE ?? 'المغير' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        <div class="field profession" contenteditable="true">
            {{ $employee->CODFONC ?? 'أستاذ مميّز في تعليم الإبتدائي' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        <div class="field addressFR" contenteditable="true">
            {{ $employee->ADRESSE ?? '' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        <div class="field professionFR" contenteditable="true">
            {{ $employee->PROFESSION ?? '' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>

        {{-- --------- معلومات العمل --------- --}}
        <div class="field recruitment-date" contenteditable="true">
            {{ !empty($employee->DATENT) ? \Carbon\Carbon::parse($employee->DATENT)->format('d / m / Y') : '' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        <div class="field last-work-date" contenteditable="true">
            {{ !empty($employee->DATFIN) ? \Carbon\Carbon::parse($employee->DATFIN)->format('d / m / Y') : '' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        <div class="field resume-date" contenteditable="true">
            {{ !empty($employee->DATRES) ? \Carbon\Carbon::parse($employee->DATRES)->format('d / m / Y') : '' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        <div class="field stop-date" contenteditable="true">
            {{ !empty($employee->DATARRET) ? \Carbon\Carbon::parse($employee->DATARRET)->format('d / m / Y') : '' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>

        {{-- --------- الأمومة --------- --}}
        <div class="field Materniter-resume-date" contenteditable="true">
            {{ $employee->CAS_DE_MATERNITÉ ?? '' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        <div class="field Materniter-stop-date" contenteditable="true">
            {{ $employee->DATFIN ?? '' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        <div class="field Materniter-resume-dateFR" contenteditable="true">
            {{ $employee->DATRES ?? '' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        <div class="field Materniter-stop-dateFR" contenteditable="true">
            {{ $employee->DATARRET ?? '' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>

        {{-- --------- العجز --------- --}}
        <div class="field Invalidite-resume-date" contenteditable="true">
            {{ $employee->CAS_D_INVALIDITE ?? '' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        <div class="field Invalidite-stop-date" contenteditable="true">
            {{ $employee->DATFIN ?? '' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        <div class="field Invalidite-resume-dateFR" contenteditable="true">
            {{ $employee->DATRES ?? '' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
        <div class="field Invalidite-stop-dateFR" contenteditable="true">
            {{ $employee->DATARRET ?? '' }}
            <i class="fa fa-edit editable-icon"></i>
        </div>
    </div>
</body>

</html>
