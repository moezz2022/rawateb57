@extends('layouts.master')
@section('css')
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الأجور</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ شهادة العمل والأجر</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fa-solid fa-file-contract ml-2"></i>
                    شهادة العمل والأجر:
                    <span id="employeeName" class="badge bg-danger ms-2">لم يتم اختيار موظف</span>
                </h3>
            </div>
            <div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
                <div class="card shadow-sm border-0 rounded-3 w-75">
                    <div class="card-body">
                        <form id="settingsForm" class="row g-4">

                            <!-- العام -->
                            <div class="form-group col-md-6">
                                <label for="year" class="form-label">العام:</label>
                                <select id="year" class="form-control form-control-lg">
                                    <option value="0">حدد العام</option>
                                    @foreach ($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- الشهر -->
                            <div class="form-group col-md-6">
                                <label for="month" class="form-label">الشهر:</label>
                                <select id="month" class="form-control form-control-lg" disabled>
                                    <option value="0">حدد الشهر</option>
                                </select>
                            </div>

                            <!-- زر قائمة الموظفين -->
                            <div class="form-group col-md-6">
                                <label class="form-label">الموظف:</label><br>
                                <button type="button" class="btn btn-info btn-lg btn-fill w-100" data-toggle="modal"
                                    data-target="#employeeListModal" id="openEmployeeModal" disabled>
                                    <i class="fas fa-users ml-2"></i> قائمة الموظفين
                                </button>
                                <input type="hidden" id="employeeId" value="0">
                            </div>

                            <!-- المدة -->
                            <div class="form-group col-md-6">
                                <label for="duration" class="form-label">المدة:</label>
                                <select id="duration" class="form-control form-control-lg" disabled>
                                    <option value="0">عدد الأشهر</option>
                                    <option value="1">شهر</option>
                                    <option value="2">شهرين</option>
                                    <option value="3">3 أشهر</option>
                                    <option value="4">4 أشهر</option>
                                    <option value="5">5 أشهر</option>
                                    <option value="6">6 أشهر</option>
                                    <option value="7">7 أشهر</option>
                                    <option value="8">8 أشهر</option>
                                    <option value="9">9 أشهر</option>
                                    <option value="10">10 أشهر</option>
                                    <option value="11">11 أشهر</option>
                                    <option value="12">12 شهر</option>
                                </select>
                            </div>

                            <!-- الأزرار -->
                            <div class="col-12 d-flex justify-content-center mt-3 gap-2">
                                <button id="submit" type="button" class="btn btn-primary btn-lg px-4"
                                    disabled>موافق</button>
                                <button id="cancel" type="reset" class="btn btn-outline-secondary btn-lg px-4">إلغاء
                                    الأمر</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- مودال قائمة الموظفين -->
        <div class="modal fade" id="employeeListModal" tabindex="-1" role="dialog"
            aria-labelledby="employeeListModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content shadow-lg rounded-3 border-0">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="employeeListModalLabel">
                            <i class="fas fa-users mr-2"></i> قائمة الموظفين
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- التبويبات -->
                        <ul class="nav nav-tabs" id="employeeTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all-employees"
                                    role="tab">جميع الموظفين</a>
                            </li>
                        </ul>
                        <!-- محتوى التبويبات -->
                        <div class="tab-content mt-3"
                            style="background-color: #fff; border-radius: 10px; padding: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);"
                            id="employeeTabsContent">
                            <!-- جميع الموظفين -->
                            <div class="tab-pane fade show active" id="all-employees" role="tabpanel">
                                <div class="row">
                                    <!-- البحث -->
                                    <div class="col-md-4 border-right">
                                        <div class="form-group">
                                            <label for="employeeSearchAts" class="font-weight-bold">🔍 البحث عن
                                                موظف</label>
                                            <input type="text" id="employeeSearchAts" class="form-control"
                                                placeholder="اكتب الاسم أو رقم الحساب...">
                                            <small class="form-text text-muted">
                                                ابحث باستخدام الاسم، اللقب أو رقم الحساب الجاري.
                                            </small>
                                        </div>
                                    </div>
                                    <!-- الجدول -->
                                    <div class="col-md-8">
                                        <!-- حاوية قابلة للتمرير -->
                                        <div class="tab-content scroll-area"
                                            style="max-height: 300px; overflow-y: auto; direction: rtl;">
                                            <table class="table table-striped table-hover table-sticky"
                                                id="allEmployeesTable">
                                                <thead>
                                                    <tr>
                                                        <th>الرقم</th>
                                                        <th>الاسم واللقب</th>
                                                        <th>إجراء</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- نتائج البحث عبر AJAX -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div> <!-- row -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> إغلاق
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const months = {
                1: "جانفي",
                2: "فيفري",
                3: "مارس",
                4: "أفريل",
                5: "ماي",
                6: "جوان",
                7: "جويلية",
                8: "أوت",
                9: "سبتمبر",
                10: "أكتوبر",
                11: "نوفمبر",
                12: "ديسمبر"
            };

            const yearSelect = document.getElementById("year");
            const monthSelect = document.getElementById("month");
            const employeeId = document.getElementById("employeeId");
            const employeeName = document.getElementById("employeeName");
            const durationSelect = document.getElementById("duration");
            const submitBtn = document.getElementById("submit");
            const cancelBtn = document.getElementById("cancel");
            const tbody = document.querySelector("#allEmployeesTable tbody");

            const resetMonth = () => {
                monthSelect.innerHTML = '<option value="0">اختر الشهر</option>';
                monthSelect.disabled = true;
            };
            const resetEmployee = () => {
                employeeId.value = 0;
                employeeName.innerText = "لم يتم اختيار موظف";
                document.getElementById("openEmployeeModal").disabled = true;
            };
            const resetDuration = () => {
                durationSelect.value = 0;
                durationSelect.disabled = true;
            };
            const resetSubmit = () => {
                submitBtn.disabled = true;
            };

            // عند تغيير السنة
            yearSelect.addEventListener("change", async () => {
                const year = yearSelect.value;
                resetMonth();
                resetEmployee();
                resetDuration();
                resetSubmit();

                if (year != 0) {
                    try {
                        let url = "{{ route('ats.months', ':year') }}".replace(':year', year);
                        const res = await axios.get(url);
                        res.data.forEach(m => {
                            monthSelect.insertAdjacentHTML("beforeend",
                                `<option value="${m}">${months[m]}</option>`);
                        });
                        monthSelect.disabled = false;
                    } catch (err) {
                        console.error("خطأ في جلب الأشهر:", err);
                    }
                }
            });

            // عند تغيير الشهر
            monthSelect.addEventListener("change", async () => {
                const year = yearSelect.value;
                const month = monthSelect.value;
                resetEmployee();
                resetDuration();
                resetSubmit();
                tbody.innerHTML = "";

                if (month != 0) {
                    try {
                        let url = "{{ route('ats.employees', [':year', ':month']) }}"
                            .replace(':year', year)
                            .replace(':month', month);

                        const res = await axios.get(url);
                        res.data.forEach(e => {
                            tbody.insertAdjacentHTML("beforeend", `
                                <tr>
                                    <td>${e.MATRI}</td>
                                    <td>${e.NOMA} ${e.PRENOMA}</td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-sm btn-success select-employee"
                                            data-id="${e.MATRI}"
                                            data-name="${e.NOMA} ${e.PRENOMA}">
                                            اختيار
                                        </button>
                                    </td>
                                </tr>
                            `);
                        });

                        document.getElementById("openEmployeeModal").disabled = false;
                    } catch (err) {
                        console.error("خطأ في جلب الموظفين:", err);
                    }
                }
            });

            // عند الضغط على زر اختيار موظف
            tbody.addEventListener("click", (ev) => {
                if (ev.target.classList.contains("select-employee")) {
                    const id = ev.target.dataset.id;
                    const name = ev.target.dataset.name;

                    employeeId.value = id;
                    employeeName.innerText = name;

                    durationSelect.disabled = false;
                    submitBtn.disabled = (durationSelect.value == 0);

                    $("#employeeListModal").modal("hide");
                }
            });
            document.getElementById("employeeSearchAts").addEventListener("input", function() {
                const search = this.value.toLowerCase().trim();
                const terms = search.split(" ").filter(Boolean); // تقسيم الكلمات (مثلاً "ahmed ali")

                tbody.querySelectorAll("tr").forEach(tr => {
                    const fullName = tr.children[1].textContent
                        .toLowerCase(); // الاسم واللقب مع بعض
                    const id = tr.children[0].textContent.toLowerCase();

                    // نتحقق أن كل كلمة مكتوبة موجودة إما في الاسم أو في الرقم
                    const matches = terms.every(term =>
                        fullName.includes(term) || id.includes(term)
                    );

                    tr.style.display = matches ? "" : "none";
                });
            });

            // عند تغيير المدة
            durationSelect.addEventListener("change", () => {
                submitBtn.disabled = (durationSelect.value == 0);
            });

            // زر الطباعة
            submitBtn.addEventListener("click", () => {
                const emp = employeeId.value;
                const year = yearSelect.value;
                const month = monthSelect.value;
                const duration = durationSelect.value;

                if (emp != 0 && year != 0 && month != 0 && duration != 0) {
                    let url = "{{ route('ats.generate1', ':matricule') }}"
                        .replace(':matricule', emp);

                    url += `?year=${year}&month=${month}&duration=${duration}`;
                    window.location.href = url; // فتح في نفس الصفحة

                }

            });


            // زر إلغاء الأمر
            cancelBtn.addEventListener("click", () => {
                yearSelect.value = 0;
                resetMonth();
                resetEmployee();
                resetDuration();
                resetSubmit();
                tbody.innerHTML = "";
            });
        });
    </script>
@endsection
