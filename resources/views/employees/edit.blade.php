@extends('layouts.master')
@section('css')
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المستخدمين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تحديث
                    معلومات موظف </span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="main-content-label mg-b-5">
                        تحديث معلومات موظف
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('employees.update', $employee->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div id="wizard1" class="wizard" data-mode="edit">
                            <h3 class="wizard-header"><i class="fas fa-user" style="padding: 0.5rem"></i> الحالة المدنية
                            </h3>
                            <section class="wizard-step active">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>اللقب<span style="color: red;">*</span></label>
                                        <input type="text" name="NOMA" class="form-control"
                                            value="{{ $employee->NOMA }}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>الاسم<span style="color: red;">*</span></label>
                                        <input type="text" name="PRENOMA" class="form-control"
                                            value="{{ $employee->PRENOMA }}" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label">اللقب باللاتينية<span style="color: red;">*</label>
                                        <input type="text" name="NOM" class="form-control"
                                            placeholder="اللقب باللاتينية" value="{{ $employee->NOM }}" required readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">الاسم باللاتينية<span style="color: red;">*</label>
                                        <input type="text" name="PRENOM" class="form-control"
                                            placeholder="الاسم باللاتينية" value="{{ $employee->PRENOM }}" required
                                            readonly>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="DATNAIS">تاريخ الازدياد <span style="color: red;">*</span></label>
                                        <input type="date" class="form-control" id="DATNAIS" name="DATNAIS"
                                            value="{{ $employee->DATNAIS ?? '' }}" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>الحالة العائلية (ادخل مثلا M01 أو C00)<span
                                                style="color: red;">*</span></label>
                                        <input type="text" name="SITFAM" class="form-control"
                                            placeholder="ادخل مثلا M01 أو C00" value="{{ $employee->SITFAM ?? '' }}"
                                            required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>عدد الأولاد أكبر من 10 سنوات <span style="color: red;">*</span></label>
                                        <input class="form-control" name="ENF10" type="number"
                                            value="{{ $employee->ENF10 ?? 0 }}">
                                    </div>
                                </div>
                            </section>
                            <!-- المعلومات الشخصية -->
                            <h3 class="wizard-header"><i class="fas fa-id-card" style="padding: 0.5rem"></i> المعلومات
                                الشخصية</h3>
                            <section class="wizard-step">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>رقم الحساب البريدي<span style="color: red;">*</span></label>
                                        <input class="form-control" name="MATRI" type="text"
                                            value="{{ $employee->MATRI }}" required readonly>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>المفتاح<span style="color: red;">*</label>
                                        <input class="form-control" name="CLECPT" type="text"
                                            value="{{ $employee->CLECPT }}" required readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>رقم الضمان الاجتماعي<span style="color: red;">*</label>
                                    <input class="form-control" name="NUMSS" type="text"
                                        value="{{ $employee->NUMSS }}" required>
                                </div>
                            </section>
                            <!-- المعلومات المهنية -->
                            <h3 class="wizard-header"><i class="fas fa-briefcase" style="padding: 0.5rem"></i> المعلومات
                                المهنية</h3>
                            <section class="wizard-step">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>تاريخ التوظيف<span style="color: red;">*</span></label>
                                        <input type="date" class="form-control" id="DATENT" name="DATENT"
                                            value="{{ $employee->DATENT }}" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>الدرجة الحالية<span style="color: red;">*</span></label>
                                        <select name="ECH" class="form-control" required>
                                            <option value="0" {{ $employee->ECH == '0' ? 'selected' : '' }}>0
                                            </option>
                                            <option value="1" {{ $employee->ECH == '1' ? 'selected' : '' }}>1
                                            </option>
                                            <option value="2" {{ $employee->ECH == '2' ? 'selected' : '' }}>2
                                            </option>
                                            <option value="3" {{ $employee->ECH == '3' ? 'selected' : '' }}>3
                                            </option>
                                            <option value="4" {{ $employee->ECH == '4' ? 'selected' : '' }}>4
                                            </option>
                                            <option value="5" {{ $employee->ECH == '5' ? 'selected' : '' }}>5
                                            </option>
                                            <option value="6" {{ $employee->ECH == '6' ? 'selected' : '' }}>6
                                            </option>
                                            <option value="7" {{ $employee->ECH == '7' ? 'selected' : '' }}>7
                                            </option>
                                            <option value="8" {{ $employee->ECH == '8' ? 'selected' : '' }}>8
                                            </option>
                                            <option value="9" {{ $employee->ECH == '9' ? 'selected' : '' }}>9
                                            </option>
                                            <option value="10" {{ $employee->ECH == '10' ? 'selected' : '' }}>10
                                            </option>
                                            <option value="11" {{ $employee->ECH == '11' ? 'selected' : '' }}>11
                                            </option>
                                            <option value="12" {{ $employee->ECH == '12' ? 'selected' : '' }}>12
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>الرتبة<span style="color: red;">*</span></label>
                                        <select class="form-control" disabled>
                                            <option value="">--الرجاء الاختيار--</option>
                                            @foreach ($grades as $grade)
                                                <option value="{{ $grade->codtab }}"
                                                    {{ $employee->CODFONC == $grade->codtab ? 'selected' : '' }}>
                                                    {{ $grade->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <!-- نخلي القيمة تتبعت مخفية -->
                                        <input type="hidden" name="CODFONC" value="{{ $employee->CODFONC }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>اسم المؤسسة/الهيئة <span style="color: red;">*</span></label>
                                        <select id="AFFECT" class="form-control @error('AFFECT') is-invalid @enderror"
                                            disabled>
                                            <option value="" selected="selected">-- الرجاء الاختيار --</option>
                                            @foreach ($groups as $group)
                                                <option value="{{ $group->AFFECT }}"
                                                    {{ old('AFFECT', isset($employee) && $employee->AFFECT == $group->AFFECT ? 'selected' : '') }}>
                                                    {{ $group->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <!-- هذا اللي يضمن القيمة تتبعت -->
                                        <input type="hidden" name="AFFECT"
                                            value="{{ old('AFFECT', isset($employee) ? $employee->AFFECT : '') }}">

                                    </div>
                                </div>
                            </section>
                        </div>
                    </form>
                </div>
            </div>
            <div id="alert-container" class="alertify-notifier ajs-bottom ajs-right">
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
@endsection
