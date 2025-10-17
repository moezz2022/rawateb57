@extends('layouts.master3')
@section('css')
    <!--- Internal Select2 css-->
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <style>
        .container-fluid {
            margin: 0 auto;
            min-height: calc(80vh - 90px);
        }

        /* Header Section */
        .header-logo {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 20px;
        }

        .header-text {
            color: gold;
            animation: slideInFromLeft 1s ease-out;
            flex: 1;
            text-align: center;
        }

        .republic-name {
            font-size: 24px;
            font-weight: 700;
            color: gold;
            margin-bottom: 5px;
        }

        .ministry-name {
            font-size: 24px;
            font-weight: 600;
            color: gold;
            margin-bottom: 5px;
        }

        .department-name {
            font-size: 20px;
            font-weight: 600;
            color: gold;
        }

        .important-header {
            background-color: #dc3545;
            color: white;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
            padding: 0.5rem;
            margin-bottom: 1rem;
        }

        /* 🎨 تحسين شكل شريط الخطوات Wizard Steps */
        #wizard2 .wizard>.steps {
            display: flex;
            justify-content: space-between;
            align-items: center;
            direction: rtl;
            margin: 40px 0;
            position: relative;
            counter-reset: step;
        }

        #wizard2 .wizard>.steps ul {
            display: flex;
            justify-content: space-between;
            width: 100%;
            padding: 0;
            list-style: none;
        }

        #wizard2 .wizard>.steps ul li {
            position: relative;
            flex: 1;
            text-align: center;
            font-weight: 600;
            color: #999;
            transition: all 0.3s ease;
        }

        /* رقم الخطوة */
        #wizard2 .wizard>.steps ul li:before {
            content: counter(step);
            counter-increment: step;
            width: 55px;
            height: 55px;
            line-height: 55px;
            border-radius: 50%;
            display: block;
            margin: 0 auto 10px;
            background: linear-gradient(135deg, #d1d1d1, #f2f2f2);
            color: #555;
            font-size: 18px;
            font-weight: bold;
            position: relative;
            z-index: 2;
            transition: all 0.4s ease;
            box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1);
        }

        /* الخط المتصل بين الخطوات */
        #wizard2 .wizard>.steps ul li:after {
            content: "";
            position: absolute;
            top: 27px;
            right: -50%;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, #dcdcdc, #e8e8e8);
            z-index: 1;
            transition: background 0.4s ease;
        }

        #wizard2 .wizard>.steps ul li:last-child:after {
            display: none;
        }

        /* الحالة النشطة */
        #wizard2 .wizard>.steps ul li.current:before {
            background: linear-gradient(135deg, #007bff, #00bfff);
            color: #fff;
            transform: scale(1.1);
        }

        #wizard2 .wizard>.steps ul li.current {
            color: #007bff;
        }

        /* الحالة المكتملة */
        #wizard2 .wizard>.steps ul li.done:before {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: #fff;
        }

        #wizard2 .wizard>.steps ul li.done:after {
            background: linear-gradient(to right, #28a745, #20c997);
        }

        #wizard2 .wizard>.steps ul li.done {
            color: #28a745;
        }

        /* تأثير hover */
        #wizard2 .wizard>.steps ul li:hover {
            transform: translateY(-3px);
            cursor: pointer;
        }

        /* تنسيق أيقونات العناوين */
        #wizard2 .wizard-header i {
            color: #007bff;
            margin-left: 5px;
        }

        /* أقسام الخطوات */
        #wizard2 .wizard-step {
            background: #f9f9f9;
            border-radius: 10px;
            padding: 00px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 0px;
            direction: rtl;
        }

            /* العنوان الهام */
            .important-header {
                background-color: #dc3545;
                color: white;
                text-align: center;
                font-size: 1.5rem;
                font-weight: bold;
                border-radius: 10px 10px 0 0;
                padding: 0.5rem;
                margin-bottom: 1rem;
            }

            @keyframes slideInFromLeft {
                from {
                    transform: translateX(-100%);
                }

                to {
                    transform: translateX(0);
                }
            }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="header-logo mt-5">
                    <a href="{{ route('auth.index') }}"><img src="{{ asset('assets/img/brand/logo57.png') }}" class="logo"
                            alt="logo">
                    </a>
                    <div class="header-text">
                        <div class="republic-name">الجمهورية الجزائرية الديمقراطية الشعبية</div>
                        <div class="ministry-name">وزارة التربية الوطنية</div>
                        <div class="department-name">مديرية التربية لولاية المغير</div>
                    </div>
                    <a href="{{ route('auth.index') }}"><img src="{{ asset('assets/img/brand/logo57.png') }}" class="logo"
                            alt="logo">
                    </a>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center">التسجيل في مسابقة التوظيف على أساس الاختبار المهني بعنوان سنة 2025 (العمال
                            المهنيين)</h2>
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger text-center">{{ $error }}</div>
                        @endforeach

                        <form action="{{ route('concours.store') }}" method="POST" id="registrationForm"
                            enctype="multipart/form-data">
                            @csrf
                            <div id="wizard2" class="wizard">
                                <!-- الخطوة 0: الموافقة على جمع ومعالجة البيانات -->
                                <h3 class="wizard-header">
                                    <i class="fas fa-shield-alt" style="padding: 0.5rem"></i> الموافقة على معالجة البيانات
                                </h3>
                                <section class="wizard-step">
                                    <div class="alert alert-info"
                                        style="line-height: 2; text-align: justify; direction: rtl;">
                                        <div class="important-header">هـــام جــدا</div>

                                        <p>
                                            أوافق على أن المعلومات التي سيتم جمعها في هذا الموقع ستستخدم من طرف
                                            <strong>مديرية التربية</strong> من أجل
                                            إنشاء ملف ترشحي، وسيتم استخدامها لاستخراج الاستدعاء كما يتم الاحتفاظ بها
                                            واستغلالها/معالجتها وفقا
                                            للقانون رقم <strong>18-07 المؤرخ في 10 يونيو 2018</strong> المتعلق بحماية
                                            الأشخاص الطبيعيين في مجال معالجة
                                            المعطيات ذات الطابع الشخصي.
                                        </p>
                                        <p>
                                            كما أتعهد أن كل المعلومات التي أدخلها صحيحة، وأعلم أن أي معلومة خاطئة تقصيني
                                            آليا في حالة القبول.
                                            <br> بطاقة الإقامة يجب أن تكون صالحة إلى غاية يوم التسجيل.
                                            <br> الملف الناقص يقصي صاحبه بملاحظة "ملف ناقص".
                                            <br> التسجيل المكرر يحذف، ويسمح لي بتسجيل واحد فقط.
                                        </p>
                                    </div>

                                    <div class="form-check text-center">
                                        <input class="form-check-input" type="checkbox" id="agreeData" name="agreeData"
                                            required>
                                        <label class="form-check-label" for="agreeData">
                                            <strong>لقد قرأت وأوافق على الشروط المذكورة أعلاه.</strong>
                                        </label>
                                    </div>
                                </section>
                                <!-- الخطوة 2 -->
                                <h3 class="wizard-header"><i class="fas fa-user" style="padding: 0.5rem"></i> الرتبة
                                    الترشح</h3>
                                <section class="wizard-step">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="username" class="form-label">اسم المستخدم<span
                                                    style="color: red;">*</span></label>
                                            <input id="username" type="text" class="form-control" name="username"
                                                value="{{ old('username') }}" required autocomplete="username" autofocus>
                                        </div>
                                        <div class="form-group col-md-6 position-relative">
                                            <label for="password">كلمة المرور <span style="color: red;">*</span></label>
                                            <div class="input-group position-relative">
                                                <input id="password" type="password" class="form-control" name="password"
                                                    required autocomplete="current-password">
                                                <span class="toggle-password"
                                                    onclick="togglePasswordVisibility('password', 'toggleIconCurrent')">
                                                    <i class="fa fa-eye" id="toggleIconCurrent"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="daira">الدائرة: <span style="color: red;">*</span></label>
                                            <select name="daira_id" id="daira_id" class="form-control" required>
                                                <option disabled selected>اختر الدائرة..</option>
                                                @foreach ($dairas as $daira)
                                                    <option value="{{ $daira->id }}">{{ $daira->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="commune">البلدية: <span style="color: red;">*</span></label>
                                            <select name="commune_id" id="commune_id" class="form-control" required>
                                                <option disabled selected>اختر البلدية..</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="con_grade">الرتبة: <span class="text-danger">*</span></label>
                                            <select id="con_grade" name="con_grade" class="form-control" required>
                                                <option value="">--يرجى الاختيار--</option>
                                                <option value="1">عامل مهني من المستوى الأول</option>
                                                <option value="2">عامل مهني من المستوى الثاني</option>
                                                <option value="3">عامل مهني من المستوى الثالث</option>
                                                <option value="4">عون خدمة من المستوى الثالث</option>
                                                <option value="5">سائق سيارة من المستوى الأول</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="diploma"> الشهادة: <span class="text-danger">*</span></label>
                                            <select class="form-control" data-placeholder=" الشهادة" id="diploma"
                                                name="diploma" disabled required>
                                                <option value="">--يرجى الاختيار--</option>
                                                <option value="1"> شهادة الكفاءة المهنية</option>
                                                <option value="2">شهادة التكوين المهني المتخصص</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="specialty">التخصص: <span class="text-danger">*</span></label>
                                            <select id="specialty" name="specialty" class="form-control" disabled
                                                required>
                                                <option value="">--يرجى الاختيار--</option>
                                                <option value="1">طبخ الجماعات</option>
                                                <option value="2">نجارة الألمنيوم</option>
                                                <option value="3">تركيب صحي وغاز</option>
                                                <option value="4">تركيب وصيانة أجهزة التبريد والتكييف</option>
                                                <option value="5">الكهرباء المعمارية</option>
                                                <option value="6">تلحيم</option>
                                                <option value="7">بستنة</option>
                                            </select>
                                        </div>
                                    </div>
                                </section>
                                <!-- الخطوة 3 -->
                                <h3 class="wizard-header"><i class="fas fa-user" style="padding: 0.5rem"></i> الحالة
                                    المدنية</h3>
                                <section class="wizard-step">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>اللقب<span style="color: red;">*</span></label>
                                            <input type="text" name="NomArF" class="form-control" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>الاسم<span style="color: red;">*</span></label>
                                            <input type="text" name="PrenomArF" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="gender"> الجنس: <span class="text-danger">*</span> </label>
                                            <select id="gender" name="gender" class="form-control"
                                                data-placeholder="الجنس" required>
                                                <option value="">--يرجى الاختيار--</option>
                                                <option value="1">ذكر</option>
                                                <option value="0">أنثى</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>تاريخ الازدياد<span style="color: red;">*</span></label>
                                            <input type="date" name="DateNaiF" class="form-control" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>مكان الميلاد<span style="color: red;">*</span></label>
                                            <input type="text" name="LieuNaiArF" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="birthNum"> رقم شهادة الميلاد: <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="birthNum" class="form-control"
                                                placeholder="رقم شهادة الميلاد" maxlength="8" digits="true" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="familyStatus"> الحالة العائلية: <span class="text-danger">*</span>
                                            </label>
                                            <select name="familyStatus" class="form-control"
                                                data-placeholder="الحالة العائلية" required>
                                                <option value="">--يرجى الاختيار--</option>
                                                <option value="1">متزوج (ة)</option>
                                                <option value="2">أعزب (عزباء)</option>
                                                <option value="3">مطلق (ة)</option>
                                                <option value="4">أرمل (ة)</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="childrenNumber"> عدد الأولاد: <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="childrenNumber" class="form-control"
                                                placeholder="عدد الأولاد" maxlength="2" digits="true" disabled
                                                required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="residenceMunicipality"> بلدية الإقامة: <span
                                                    class="text-danger">*</span></label>
                                            <select id="residenceMunicipality" name="residenceMunicipality"
                                                class="form-control" data-placeholder="بلدية الإقامة" required>
                                                <option value="">--يرجى الاختيار--</option>
                                                <option value="57271">المغير</option>
                                                <option value="57272">سيدي خليل</option>
                                                <option value="57283">أم الطيور</option>
                                                <option value="57274">سطيل</option>
                                                <option value="57281">جامعة</option>
                                                <option value="57272">المرارة</option>
                                                <option value="57283">تندلة</option>
                                                <option value="57284">سيدي عمران</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="personalAddress"> العنوان الشخصي: <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="personalAddress" class="form-control"
                                                id="personalAddress" arabicWithNumbers="true"
                                                placeholder="العنوان الشخصي" maxlength="100" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="phoneNumber"> رقم الهاتف: <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="phoneNumber"
                                                class="form-control @error('phoneNumber') is-invalid @enderror"
                                                id="phoneNumber" placeholder="رقم الهاتف" maxlength="10" minlength="9"
                                                digits="true" pattern="^0(.*)" required>
                                            @error('phoneNumber')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                @enderror
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="serviceState"> الوضعية اتجاه الخدمة الوطنية: <span
                                                    class="text-danger">*</span></label>
                                            <select name="serviceState" class="form-control" id="serviceState"
                                                data-placeholder="الوضعية اتجاه الخدمة الوطنية" disabled required>
                                                <option value="">--يرجى الاختيار--</option>
                                                <option value="1">مؤدى</option>
                                                <option value="2">معفى</option>
                                                <option value="3">مؤجل</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="serviceNum"> مرجع الوثيقة:<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="serviceNum" class="form-control" id="serviceNum"
                                                placeholder="رقم الوثيقة" maxlength="15" digits="true" required
                                                disabled>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="servIsDate"> تاريخ الإصدار: <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="servIsDate" class="form-control" id="servIsDate"
                                                placeholder="تاريخ الإصدار" dateIso="true" greaterThan="#birthDate"
                                                notAfterToday="true" age18when="#birthDate" required disabled>
                                        </div>
                                    </div>
                                </section>
                                <!-- الخطوة 4 -->
                                <h3 class="wizard-header">
                                    <i class="fas fa-briefcase" style="padding: 0.5rem"></i> تحميل الوثائق
                                </h3>
                                <section class="wizard-step">
                                    <div class="alert alert-danger">
                                        <ul>
                                            <li>يرجى تحميل الوثائق المطلوبة بصيغة PDF.</li>
                                            <li>تأكد من تحميل كل الوثائق المطلوبة بشكل صحيح.</li>
                                            <li>يمكنك معاينة الوثائق المحملة أو استبدالها إذا لزم الأمر.</li>
                                            <li>اضغط على "معاينة" لرؤية الوثيقة المحملة.</li>
                                        </ul>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <table class="table table-striped text-center">
                                                <thead>
                                                    <tr>
                                                        <th>اسم الوثيقة</th>
                                                        <th>تحميل</th>
                                                        <th>معاينة</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>وثيقة اتجاه الخدمة الوطنية</td>
                                                        <td><input type="file" name="military_service_document"
                                                                class="form-control upload-input" accept=".pdf"></td>
                                                        <td><button type="button" class="btn btn-primary preview-file"
                                                                disabled>معاينة</button></td>
                                                    </tr>
                                                    <tr>
                                                        <td>شهادة الإقامة</td>
                                                        <td><input type="file" name="residence_certificate"
                                                                class="form-control upload-input" accept=".pdf"></td>
                                                        <td><button type="button" class="btn btn-primary preview-file"
                                                                disabled>معاينة</button></td>
                                                    </tr>
                                                    <tr>
                                                        <td>شهادة طبية</td>
                                                        <td><input type="file" name="medical_certificate"
                                                                class="form-control upload-input" accept=".pdf"></td>
                                                        <td><button type="button" class="btn btn-primary preview-file"
                                                                disabled>معاينة</button></td>
                                                    </tr>
                                                    <tr>
                                                        <td>شهادة مدرسية</td>
                                                        <td><input type="file" name="school_certificate"
                                                                class="form-control upload-input"
                                                                accept=".pdf,.jpg,.jpeg,.png"></td>
                                                        <td><button type="button" class="btn btn-primary preview-file"
                                                                disabled>معاينة</button></td>
                                                    </tr>
                                                    <tr>
                                                        <td>شهادة التكوين المتخصص</td>
                                                        <td><input type="file" name="specialized_training_certificate"
                                                                class="form-control upload-input"
                                                                accept=".pdf,.jpg,.jpeg,.png"></td>
                                                        <td><button type="button" class="btn btn-primary preview-file"
                                                                disabled>معاينة</button></td>
                                                    </tr>
                                                    <tr>
                                                        <td>رخصة السياقة</td>
                                                        <td><input type="file" name="driving_license"
                                                                class="form-control upload-input"
                                                                accept=".pdf,.jpg,.jpeg,.png"></td>
                                                        <td><button type="button" class="btn btn-primary preview-file"
                                                                disabled>معاينة</button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-lg-4">
                                            <h5 class="text-center">معاينة الوثيقة</h5>
                                            <iframe id="previewFrame"
                                                style="border: none; width: 100%; height: 400px;"></iframe>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#wizard2").steps({
                headerTag: "h3",
                bodyTag: "section",
                transitionEffect: "fade",
                autoFocus: true,
                labels: {
                    next: 'التالي',
                    previous: 'السابق',
                    finish: 'تسجيل'
                },
                onStepChanging: function(event, currentIndex, newIndex) {

                    // ✅ تحقق خاص بالمرحلة الأولى (الموافقة على الشروط)
                    if (currentIndex === 0 && newIndex > currentIndex) {
                        const agreeChecked = $("#agreeData").is(":checked");
                        if (!agreeChecked) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'تنبيه',
                                text: 'يجب الموافقة على جمع ومعالجة البيانات الشخصية قبل المتابعة.',
                                confirmButtonText: 'حسنًا'
                            });
                            return false;
                        }
                    }

                    // السماح بالرجوع إلى الوراء
                    if (newIndex < currentIndex) return true;

                    // ✅ التحقق العام من الحقول المطلوبة في كل خطوة
                    const currentStep = $(`#wizard2-p-${currentIndex}`);
                    let valid = true;
                    currentStep.find("input, select, textarea").each(function() {
                        const $input = $(this);
                        if ($input.prop('required') && $input.val().trim() === "") {
                            valid = false;
                            $input.addClass("is-invalid");
                            if (!$input.next('.invalid-feedback').length) {
                                $input.after(
                                    '<div class="invalid-feedback">هذا الحقل إلزامي</div>');
                            }
                        } else {
                            $input.removeClass("is-invalid");
                            $input.next('.invalid-feedback').remove();
                        }
                    });
                    if (!valid) {
                        currentStep.find('.is-invalid').first().focus();
                    }
                    return valid;
                },
                onFinished: function(event, currentIndex) {
                    const form = $("#registrationForm");
                    if (form[0].checkValidity()) {
                        form.submit();
                    } else {
                        form.find(':invalid').first().focus();
                    }
                }
            });
        });

        $(document).ready(function() {
            $('#daira_id').on('change', function() {
                const dairaId = $(this).val();
                if (dairaId) {
                    $.ajax({
                        url: `/get-communes/${dairaId}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#commune_id').empty().append(
                                '<option disabled selected>اختر البلدية..</option>');
                            data.forEach(commune => {
                                $('#commune_id').append(
                                    `<option value="${commune.id}">${commune.name}</option>`
                                );
                            });
                        },
                        error: function() {
                            alert('حدث خطأ أثناء تحميل البلديات.');
                        }
                    });
                } else {
                    $('#commune_id').empty().append('<option disabled selected>اختر البلدية..</option>');
                }
            });
        });
        $(document).ready(function() {
            $('select[name="familyStatus"]').change(function() {
                const familyStatus = $(this).val();
                const isEnabled = familyStatus === '1' || familyStatus === '3' || familyStatus === '4';
                $('input[name="childrenNumber"]')
                    .prop('disabled', !isEnabled)
                    .prop('required', isEnabled);
                if (!isEnabled) {
                    $('input[name="childrenNumber"]').val('');
                }
            });
        });
        $(document).ready(function() {
            $('#con_grade').change(function() {
                const grade = $(this).val();
                if (grade === '2' || grade === '3' || grade === '4') {
                    $('#specialty, #diploma')
                        .prop('disabled', false)
                        .prop('required', true);
                } else {
                    $('#specialty, #diploma')
                        .prop('disabled', true)
                        .val('')
                        .prop('required', false);
                }
            });
        });
        $(document).ready(function() {
            function toggleMilitaryFields(enable) {
                const fields = ["#serviceState", "#serviceNum", "#servIsDate", "#militaryDoc"];
                fields.forEach(field => {
                    $(field).prop("disabled", !enable);
                    $(field).val('');
                    $(field).prop('required', enable);
                    $(field).removeClass('is-invalid is-valid');
                });
                if (enable) {
                    $("#militaryDoc").closest('.form-group').removeClass('form-group-disabled');
                } else {
                    $("#militaryDoc").closest('.form-group').addClass('form-group-disabled');
                }
            }
            $("#gender").change(function() {
                const gender = $(this).val();
                toggleMilitaryFields(gender == 1);
            });
            $("#serviceState").change(function() {
                const serviceState = $(this).val();
                const isExempt = serviceState == 4 || $("#serviceState").prop("disabled");
                $("#serviceNum").prop("disabled", isExempt).val('');
                $("#servIsDate").prop("disabled", isExempt).val('');
                $("#serviceNum, #servIsDate").prop('required', !isExempt);
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".upload-input").on("change", function() {
                const fileInput = $(this);
                const file = fileInput[0].files[0];
                const previewButton = fileInput.closest("tr").find(".preview-file");
                if (file && /\.(pdf)$/i.test(file.name)) {
                    previewButton.prop("disabled", false);
                    previewButton.off("click").on("click", function() {
                        const fileURL = URL.createObjectURL(file);
                        $("#previewFrame").attr("src", fileURL);
                    });
                } else {
                    previewButton.prop("disabled", true);
                    alert("الرجاء اختيار ملف بصيغة مدعومة: PDF");
                }
            });
        });
    </script>
@endsection
