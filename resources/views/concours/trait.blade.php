@extends('layouts.master')
@section('css')
    <!-- Internal Data table css -->
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المستخدمين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ معالجة
                    الملفات</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="form-group col-md-4 text-white">
                            <label class="text-white" for="residenceMunicipality"> البلدية:</label>
                            <select id="residenceMunicipality" name="residenceMunicipality" class="form-control" required>
                                <option value="">--يرجى الاختيار--</option>
                                <option value="57271">المغير</option>
                                <option value="57272">سيدي خليل</option>
                                <option value="57273">أم الطيور</option>
                                <option value="57274">سطيل</option>
                                <option value="57281">جامعة</option>
                                <option value="57282">المرارة</option>
                                <option value="57283">تندلة</option>
                                <option value="57284">سيدي عمران</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="text-white" for="con_grade">الرتبة:</label>
                            <select id="con_grade" name="con_grade" class="form-control" required>
                                <option value="">--يرجى الاختيار--</option>
                                <option value="1">عامل مهني من المستوى الأول</option>
                                <option value="2">عامل مهني من المستوى الثاني</option>
                                <option value="3">عامل مهني من المستوى الثالث</option>
                                <option value="4">عون خدمة من المستوى الثالث</option>
                                <option value="5">سائق سيارة من المستوى الأول</option>
                            </select>
                        </div>
                        <div class="col-md-3 mt-4">
                            <div class="d-grid gap-2 mt-2">
                                <button id="filter-btn" type="button" class="btn btn-warning mb-2" title="عرض المترشحين"
                                    disabled>
                                    <i class="mdi mdi-filter"></i> عرض المترشحين
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-md-nowrap" id="traitcandidate">
                            <thead>
                                <tr>
                                    <th class="wd-3p border-bottom-0">#</th>
                                    <th class="wd-15p border-bottom-0">اللقب </th>
                                    <th class="wd-15p border-bottom-0">الاسم</th>
                                    <th class="wd-12p border-bottom-0">تاريخ الميلاد</th>
                                    <th class="wd-27p border-bottom-0">رقم الهاتف</th>
                                    <th class="wd-15p border-bottom-0">حالة الملف</th>
                                    <th class="wd-15p border-bottom-0">العمليات</th>
                                </tr>
                            </thead>
                            <tbody id="users-table-body">
                                <tr>
                                    <td colspan="7" class="text-center">يرجى اختيار البلدية والرتبة لعرض البيانات</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="data-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-coustom modal-xxl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="text-center" id="model-header">
                                <h4 class="modal-title text-white" id="info-header-modalLabel">دراسة ملف المترشح</h4>
                            </div>
                        </div>
                        <form id="traitDiplomes">
                            <input type="hidden" name="matricule" id="matricule">
                            <div class="modal-body">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <table class="table table-striped table-bordered text-center" dir="rtl">
                                                <thead></thead>
                                                <tbody>
                                                    <tr class="text-center align-middle">
                                                        <td style="padding:0.25px">اللقب</td>
                                                        <td id="nom" class="fw-bold" style="padding:0.25px"></td>
                                                        <td style="padding:0.25px">الاسم</td>
                                                        <td id="prenom" class="fw-bold" style="padding:0.25px"></td>
                                                    </tr>
                                                    <tr class="text-center align-middle">
                                                        <td style="padding:0.25px">تاريخ ومكان الميلاد</td>
                                                        <td id="date_wilnais" class="fw-bold" style="padding:0.25px">
                                                        </td>
                                                        <td style="padding:0.25px">الجنس</td>
                                                        <td id="sexe" class="fw-bold" style="padding:0.25px"></td>
                                                    </tr>
                                                    <tr class="text-center align-middle">
                                                        <td style="padding:0.25px">الوضعية العائلية</td>
                                                        <td id="sfamail" class="fw-bold" style="padding:0.25px"></td>
                                                        <td style="padding:0.25px">عدد الأولاد</td>
                                                        <td id="nbenfant" class="fw-bold" style="padding:0.25px"></td>
                                                    </tr>
                                                    <tr class="text-center align-middle">
                                                        <td style="padding:0.25px">الوضعية تجاه الخدمة الوطنية</td>
                                                        <td id="service_national" class="fw-bold" style="padding:0.25px">
                                                        </td>
                                                        <td style="padding:0.25px">رقم الوثيقة (تاريخ الإصدار)</td>
                                                        <td id="ref_srvn" class="fw-bold" style="padding:0.25px"></td>
                                                    </tr>
                                                    <tr class="text-center align-middle">
                                                        <td style="padding:0.25px">العنوان</td>
                                                        <td id="adresse" class="fw-bold" style="padding:0.25px"></td>
                                                        <td style="padding:0.25px">بلدية الإقامة</td>
                                                        <td id="cd_adr" class="fw-bold" style="padding:0.25px"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class="table table-striped table-bordered text-center" dir="rtl">
                                                <thead>
                                                    <tr>
                                                        <th>الوثيقة</th>
                                                        <th>معاينة</th>
                                                        <th>دراسة</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="diplomsData">
                                                    <tr>
                                                        <td colspan="3">جاري التحميل...</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="text-center">
                                                <h5>معاينة الملف</h5>
                                            </div>
                                            <div class="card-body">
                                                <iframe id="ph1"
                                                    style="border:none;min-height: 320px;width:100%;"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">رجوع</button>
                                <button type="submit" class="btn strong btn-purple btn-lg btn-fill">تأكيد</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/responsive.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            function getStatusBadge(status) {
                switch (status) {
                    case 1:
                    case '1':
                    case 'مطابق':
                        return '<span class="badge bg-success badge-sm w-100">مُطابق</span>';
                    case 2:
                    case '2':
                    case 'غير مطابق':
                        return '<span class="badge bg-danger badge-sm w-100">غير مطابق</span>';
                    default:
                        return '<span class="badge bg-warning text-dark badge-sm w-100">قيد الدراسة</span>';
                }
            }
            // ✅ تفعيل زر الفلترة عند اختيار البلدية والرتبة
            $('#residenceMunicipality, #con_grade').on('change', function() {
                let municipality = $('#residenceMunicipality').val();
                let grade = $('#con_grade').val();
                $('#filter-btn').prop('disabled', !(municipality && grade));
            });

            // ✅ جلب المترشحين عند الضغط على زر الفلترة
            $('#filter-btn').on('click', function() {
                let municipality = $('#residenceMunicipality').val();
                let grade = $('#con_grade').val();

                $.ajax({
                    url: "{{ route('filter.users') }}",
                    method: 'GET',
                    data: {
                        residenceMunicipality: municipality,
                        con_grade: grade
                    },
                    beforeSend: function() {
                        $('#users-table-body').html(
                            '<tr><td colspan="7" class="text-center text-warning">جاري التحميل...</td></tr>'
                        );
                    },
                    success: function(response) {
                        let tableBody = $('#users-table-body');
                        tableBody.empty();

                        if (response.users.length > 0) {
                            response.users.forEach((user, index) => {
                                let row = `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${user.NomArF || ''}</td>
                                <td>${user.PrenomArF || ''}</td>
                                <td>${user.DateNaiF || ''}</td>
                                <td>${user.phoneNumber || ''}</td>
                                <td>${getStatusBadge(user.status)}</td>
                                <td>
                                    <a class="btn btn-sm btn-danger" data-toggle="modal" data-target="#data-modal" data-id="${user.id}">
                                        <i class="fa fa-eye"></i> دراسة
                                    </a>
                                </td>
                            </tr>`;
                                tableBody.append(row);
                            });
                        } else {
                            tableBody.append(
                                '<tr><td colspan="7" class="text-center text-muted">لا توجد بيانات مطابقة</td></tr>'
                            );
                        }
                        if ($.fn.DataTable.isDataTable('#traitcandidate')) {
                            $('#traitcandidate').DataTable().destroy();
                        }
                        $('#traitcandidate').DataTable({
                            paging: true,
                            pageLength: 10,
                            language: {
                                searchPlaceholder: 'بحث...',
                                sSearch: '',
                                lengthMenu: 'عرض _MENU_ مدخلات',
                                info: 'عرض _START_ إلى _END_ من _TOTAL_',
                                infoEmpty: 'عرض 0 إلى 0 من 0 ',
                                infoFiltered: '(منتقاة من _MAX_ إجمالي المدخلات)',
                                paginate: {
                                    first: 'الأول',
                                    last: 'الأخير',
                                    next: 'التالي',
                                    previous: 'السابق'
                                },
                                zeroRecords: 'لا توجد سجلات مطابقة',
                                emptyTable: 'لا توجد بيانات في الجدول',
                                search: 'بحث:'
                            },
                            responsive: true,
                            autoWidth: false
                        });
                    },
                    error: function() {
                        alertify.error("حدث خطأ أثناء جلب البيانات.");
                    }
                });
            });

            // ✅ عند فتح المودال - تحميل بيانات المترشح ووثائقه
            $('#data-modal').on('show.bs.modal', function(e) {
                const userId = $(e.relatedTarget).data('id');
                if (!userId) return;
                fetchCandidateData(userId);
                fetchCandidateDocuments(userId);
            });
        });

        // -----------------------------
        // 📄 جلب بيانات المترشح
        // -----------------------------
        function fetchCandidateData(userId) {
            $.ajax({
                url: "{{ route('getConcoursData') }}",
                method: 'GET',
                data: {
                    id: userId
                },
                success: function(response) {
                    if (response.success) fillCandidateDetails(response.data);
                    else alertify.error("فشل في جلب بيانات المترشح.");
                },
                error: function() {
                    alertify.error("حدث خطأ أثناء الاتصال بالخادم.");
                }
            });
        }

        // -----------------------------
        // 🧾 جلب وثائق المترشح
        // -----------------------------
        let documentStatuses = {};

        function fetchCandidateDocuments(userId) {
            $.ajax({
                url: "{{ route('getDocuments') }}",
                method: 'GET',
                data: {
                    id: userId
                },
                beforeSend: function() {
                    $('#diplomsData').html(
                        '<tr><td colspan="3" class="text-center text-info">جاري التحميل...</td></tr>');
                },
                success: function(response) {
                    const table = $('#diplomsData');
                    table.empty();
                    documentStatuses = {}; // إعادة التهيئة عند فتح مترشح جديد

                    if (response.success && response.data.length > 0) {
                        response.data.forEach(doc => {
                            const name = documentNames[doc.type] || "وثيقة غير معروفة";
                            table.append(`
                        <tr>
                            <td>${name}</td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm" 
                                    onclick="previewDocument(event, '{{ asset('storage') }}/${doc.path}')">
                                    معاينة
                                </button>
                            </td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="validation_${doc.id}" 
                                        data-doc-id="${doc.id}" value="مطابق"
                                        onclick="updateDocumentStatus(${doc.id}, 'مطابق')">
                                    <label class="form-check-label text-success">مطابق</label>

                                    <input class="form-check-input ml-3" type="radio" name="validation_${doc.id}" 
                                        data-doc-id="${doc.id}" value="غير مطابق"
                                        onclick="updateDocumentStatus(${doc.id}, 'غير مطابق')">
                                    <label class="form-check-label text-danger">غير مطابق</label>
                                </div>
                            </td>
                        </tr>
                    `);
                        });
                    } else {
                        table.append(
                            '<tr><td colspan="3" class="text-center text-muted">لا توجد وثائق مرفوعة.</td></tr>'
                        );
                    }
                },
                error: function() {
                    alertify.error("حدث خطأ أثناء تحميل الوثائق.");
                }
            });
        }

        // -----------------------------
        // ✅ تحديث الحالة عند اختيار المطابقة
        // -----------------------------
        function updateDocumentStatus(documentId, status) {
            documentStatuses[documentId] = status;
            checkAllDocumentsValidated();
        }

        function checkAllDocumentsValidated() {
            const total = $('#diplomsData input[type="radio"][value="مطابق"]').length / 2; // عدد الوثائق
            const validated = Object.keys(documentStatuses).length;
            $('#traitDiplomes button[type="submit"]').prop('disabled', validated < total);
        }

        // -----------------------------
        // 💾 إرسال جميع الحالات دفعة واحدة
        // -----------------------------
        $('#traitDiplomes').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('updateDocumentsBulk') }}",
                method: 'POST',
                data: {
                    documents: documentStatuses,
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    $('#traitDiplomes button[type="submit"]').prop('disabled', true).text(
                        'جاري الحفظ...');
                },
                success: function(response) {
                    if (response.success) {
                        alertify.success('تم تحديث حالة الوثائق بنجاح');
                        $('#data-modal').modal('hide');

                        if (response.updated_statuses) {
                            Object.entries(response.updated_statuses).forEach(([id, status]) => {
                                const badgeHtml = getStatusBadge(status);
                                $(`#users-table-body tr a[data-id="${id}"]`)
                                    .closest('tr')
                                    .find('td:nth-child(6)')
                                    .html(badgeHtml);
                            });
                        }

                    } else {
                        alertify.error(response.message || 'فشل في تحديث الوثائق');
                    }
                },
                error: function() {
                    alertify.error('حدث خطأ أثناء الاتصال بالخادم.');
                },
                complete: function() {
                    $('#traitDiplomes button[type="submit"]').text('تأكيد');
                }
            });
        });

        // -----------------------------
        // 👁️ عرض الوثيقة في iframe
        // -----------------------------
        function previewDocument(event, url) {
            event.preventDefault();
            $('#ph1').attr('src', url);
        }

        // -----------------------------
        // 🧠 تعبئة بيانات المترشح
        // -----------------------------
        function fillCandidateDetails(data) {
            $('#matricule').val(data.matricule || '');
            $('#nom').text(data.NomArF || '');
            $('#prenom').text(data.PrenomArF || '');
            $('#date_wilnais').text(data.DateNaiF && data.LieuNaiArF ? `${data.DateNaiF} ${data.LieuNaiArF}` : '');
            $('#sfamail').text(getFamilyStatusText(data.familyStatus));
            $('#nbenfant').text(data.childrenNumber || '0');
            $('#sexe').text(getGenderText(data.gender));
            $('#service_national').text(getServiceStateText(data.serviceState));
            $('#ref_srvn').text(data.serviceNum && data.servIsDate ? `${data.serviceNum} (${data.servIsDate})` : '');
            $('#adresse').text(data.personalAddress || '');
            $('#cd_adr').text(getResidenceMunicipalityText(data.residenceMunicipality));
        }

        // -----------------------------
        // 🗺️ ترجمات النصوص
        // -----------------------------
        const documentNames = {
            residence_certificate: "شهادة الإقامة",
            military_service_document: "وثيقة إثبات تجاه الخدمة الوطنية",
            medical_certificate: "شهادة طبية",
            school_certificate: "شهادة مدرسية",
            specialized_training_certificate: "شهادة التكوين أو الكفاءة",
            driving_license: "رخصة السياقة"
        };

        function getFamilyStatusText(id) {
            return {
                1: 'متزوج(ة)',
                2: 'أعزب(ة)',
                3: 'مطلق(ة)',
                4: 'أرمل(ة)'
            } [id] || '';
        }

        function getGenderText(g) {
            return g === 1 ? 'ذكر' : g === 0 ? 'أنثى' : '';
        }

        function getServiceStateText(s) {
            return {
                1: 'مؤدى',
                2: 'معفى',
                3: 'مؤجل'
            } [s] || '';
        }

        function getResidenceMunicipalityText(id) {
            return {
                57271: 'المغير',
                57272: 'سيدي خليل',
                57273: 'أم الطيور',
                57274: 'سطيل',
                57281: 'جامعة',
                57282: 'المرارة',
                57283: 'تندلة',
                57284: 'سيدي عمران'
            } [id] || '';
        }
    </script>
@endsection
