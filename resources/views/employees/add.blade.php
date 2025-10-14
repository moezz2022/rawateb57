@extends('layouts.master')
@section('css')
    <!--- Internal Select2 css-->
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المستخدمين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ إضافة
                    موظف جديد </span>
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
                        إضافة موظف جديد
                    </div>
                    <form action="{{ route('employees.store') }}" method="POST">
                        @csrf
                        <div id="wizard1" class="wizard" data-mode="create">
                            <h3 class="wizard-header"><i class="fas fa-user" style="padding: 0.5rem"></i> الحالة المدنية
                            </h3>
                            <section class="wizard-step active">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label">اللقب<span style="color: red;">*</span></label>
                                        <input type="text" name="NOMA"
                                            class="form-control @error('NOMA') is-invalid @enderror" placeholder="اللقب">
                                        @error('NOMA')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">الاسم<span style="color: red;">*</span></label>
                                        <input type="text" name="PRENOMA"
                                            class="form-control @error('PRENOMA') is-invalid @enderror" placeholder="الاسم">
                                        @error('PRENOMA')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label">اللقب باللاتينية<span style="color: red;">*</span></label>
                                        <input type="text" name="NOM"
                                            class="form-control @error('NOM') is-invalid @enderror"
                                            placeholder="اللقب باللاتينية">
                                        @error('NOM')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">الاسم باللاتينية<span style="color: red;">*</span></label>
                                        <input type="text" name="PRENOM"
                                            class="form-control @error('PRENOM') is-invalid @enderror"
                                            placeholder="الاسم باللاتينية">
                                        @error('PRENOM')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="DATNAIS">تاريخ الازدياد<span style="color: red;">*</span></label>
                                        <input type="date" name="DATNAIS"
                                            class="form-control @error('DATNAIS') is-invalid @enderror" id="DATNAIS">
                                        @error('DATNAIS')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>الحالة العائلية<span style="color: red;">*</span></label>
                                        <input type="text" name="SITFAM"
                                            class="form-control @error('SITFAM') is-invalid @enderror"
                                            placeholder="ادخل مثلا M01 أو C00">
                                        @error('SITFAM')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>عدد الأولاد أكبر من 10 سنوات<span style="color: red;">*</span></label>
                                        <input class="form-control" name="ENF10" type="number" value="0">
                                    </div>
                                </div>
                            </section>
                            <h3 class="wizard-header"><i class="fas fa-id-card" style="padding: 0.5rem"></i> المعلومات
                                الشخصية</h3>
                            <section class="wizard-step">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>رقم الحساب البريدي<span style="color: red;">*</span></label>
                                        <input class="form-control @error('MATRI') is-invalid @enderror" name="MATRI"
                                            type="text">
                                        @error('MATRI')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>المفتاح<span style="color: red;">*</span></label>
                                        <input class="form-control @error('CLECPT') is-invalid @enderror" name="CLECPT"
                                            type="text">
                                        @error('CLECPT')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>رقم الضمان الاجتماعي<span style="color: red;">*</span></label>
                                    <input class="form-control @error('NUMSS') is-invalid @enderror" name="NUMSS"
                                        type="text">
                                    @error('NUMSS')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </section>
                            <h3 class="wizard-header"><i class="fas fa-briefcase" style="padding: 0.5rem"></i> المعلومات
                                المهنية</h3>
                            <section class="wizard-step">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label>الرتبة<span style="color: red;">*</span></label>
                                        <select name="CODFONC"
                                            class="form-control @error('CODFONC') is-invalid @enderror">
                                            <option value="" selected="selected">--الرجاء الاختيار--</option>
                                            @foreach ($grades as $grade)
                                                <option value="{{ $grade->id }}">{{ $grade->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('CODFONC')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 ">
                                        <label>تاريخ التوظيف<span style="color: red;">*</span></label>
                                        <input type="date" class="form-control @error('DATENT') is-invalid @enderror"
                                            id="employmentDate" name="DATENT">
                                        @error('DATENT')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>الدرجة الحالية<span style="color: red;">*</span></label>
                                        <select name="ECH" class="form-control @error('ECH') is-invalid @enderror">
                                            <option value="0" selected="selected">0</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                        </select>
                                        @error('ECH')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>اسم المؤسسة/الهيئة <span style="color: red;">*</span></label>
                                        <select id="AFFECT" name="AFFECT"
                                            class="form-control @error('AFFECT') is-invalid @enderror">
                                            <option value="" selected>-- الرجاء الاختيار --</option>

                                            @foreach ($groups as $mainGroup)
                                                <optgroup label="{{ $mainGroup->name }}">
                                                    @foreach ($mainGroup->children as $subGroup)
                                                        <option value="{{ $subGroup->id }}"
                                                            {{ old('AFFECT', $employee->AFFECT ?? '') == $subGroup->id ? 'selected' : '' }}>
                                                            {{ $subGroup->name }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        @error('AFFECT')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </section>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-steps/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweet-alert/sweetalert2.min.js') }}"></script>
    <script>
        window.appRoutes = {
            getSubGroups: "{{ route('subgroups.get') }}"
        };
    </script>
@endsection
