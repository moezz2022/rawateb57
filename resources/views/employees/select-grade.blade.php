@extends('layouts.master')

@section('css')
    <style>
        .stats-card {
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        .checkbox-cell {
            width: 40px;
        }

        #printBtn {
            min-width: 250px;
            height: 40px;
            position: sticky;
            top: 10px;
        }


        .selection-counter {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
            padding: 15px 25px;
            background: #28a745;
            color: white;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            display: none;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(-100px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .pulse {
            animation: pulse 1s infinite;
        }

        /* تحسين مربع البحث للـ DataTable */
        .dataTables_wrapper .dataTables_filter input {
            margin-right: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            padding: 5px 10px;
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الموظفين</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ البطاقة المهنية</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-success text-white">
                    <h3 class="card-title mb-0 d-flex align-items-center">
                        <i class="fa-solid fa-id-card ml-2"></i> البطاقات المهنية
                    </h3>
                </div>
                <div class="card-body">

                    {{-- نموذج لاختيار الرتبة وعدد البطاقات --}}
                    <form action="{{ route('cards.load.employees') }}" method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-graduation-cap text-primary ml-1"></i> اختر الرتبة:
                                </label>
                                <select name="grade_code" class="form-control form-control-lg" required>
                                    <option value="">-- اختر رتبة --</option>
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade->codtab }}"
                                            {{ isset($gradeCode) && $gradeCode == $grade->codtab ? 'selected' : '' }}>
                                            {{ $grade->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-success btn-lg w-100">
                                    <i class="fa-solid fa-magnifying-glass ml-1"></i> عرض الموظفين
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- عرض الموظفين --}}
                    @isset($employees)
                        <form action="{{ route('cards.print.selected') }}" method="POST" id="printForm">
                            @csrf

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-primary" id="selectAllBtn">
                                        <i class="fas fa-check-double"></i> تحديد الكل
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="deselectAllBtn">
                                        <i class="fas fa-times"></i> إلغاء التحديد
                                    </button>
                                    <button type="button" class="btn btn-outline-info" id="invertSelectionBtn">
                                        <i class="fas fa-exchange-alt"></i> عكس التحديد
                                    </button>
                                </div>
                                <button type="submit" class="btn btn-warning btn-lg" id="printBtn" disabled>
                                    <i class="fas fa-print ml-1"></i> طباعة البطاقات المختارة
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle text-center" id="employeesTable">
                                    <thead class="table-success">
                                        <tr>
                                            <th class="checkbox-cell"><input type="checkbox" id="selectAll"></th>
                                            <th>رقم التعريف</th>
                                            <th>اللقب</th>
                                            <th>الاسم</th>
                                            <th>الرتبة</th>
                                            <th>تاريخ التوظيف</th>
                                            <th>الحالة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employees as $employee)
                                            <tr class="employee-row">
                                                <td>
                                                    <input type="checkbox" name="employee_ids[]" value="{{ $employee->id }}"
                                                        class="form-check-input employee-checkbox">
                                                </td>
                                                <td><strong>{{ $employee->MATRI }}</strong></td>
                                                <td>{{ $employee->NOMA }}</td>
                                                <td>{{ $employee->PRENOMA }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-primary">{{ $employee->grade->name ?? 'غير محدد' }}</span>
                                                </td>
                                                <td>{{ $employee->DATENT }}</td>
                                                <td>
                                                    @if ($employee->photo)
                                                        <span class="badge bg-success"><i class="fas fa-image"></i> بصورة</span>
                                                    @else
                                                        <span class="badge bg-warning"><i
                                                                class="fas fa-exclamation-triangle"></i> بدون صورة</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                            <h5>قم باختيار الرتبة لعرض الموظفين</h5>
                            <p class="mb-0">اختر الرتبة ثم اضغط على "عرض الموظفين"</p>
                        </div>
                    @endisset

                </div>
            </div>
        </div>
    </div>
    {{-- عداد الموظفين المحددين --}}
    <div class="selection-counter" id="selectionCounter">
        <i class="fas fa-check-circle ml-2"></i> تم اختيار <strong id="counterNumber">0</strong> موظف
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            // تهيئة DataTable
            if ($.fn.DataTable.isDataTable('#employeesTable')) {
                $('#employeesTable').DataTable().destroy();
            }

            $('#employeesTable').DataTable({
                paging: false,
                searching: true,
                ordering: true,
                info: false,
                destroy: true,
                language: {
                    searchPlaceholder: 'بحث في الموظفين...',
                    sSearch: '',
                    zeroRecords: 'لا توجد سجلات مطابقة',
                    emptyTable: 'لا توجد بيانات في الجدول'
                },
                responsive: true,
                autoWidth: false
            });

            // تحديد جميع العناصر
            const selectAllCheckbox = document.getElementById('selectAll');
            const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');
            const printBtn = document.getElementById('printBtn');
            const selectionCounter = document.getElementById('selectionCounter');
            const counterNumber = document.getElementById('counterNumber');

            // دالة واحدة لتحديث الحالة
            function updateSelectionState() {
                const selectedCount = document.querySelectorAll('.employee-checkbox:checked').length;

                // تحديث العداد
                if (counterNumber) {
                    counterNumber.textContent = selectedCount;
                }

                // إظهار/إخفاء العداد العائم
                if (selectionCounter) {
                    selectionCounter.style.display = selectedCount > 0 ? 'block' : 'none';
                }

                // تحديث زر الطباعة
                if (printBtn) {
                    printBtn.disabled = selectedCount === 0;
                    if (selectedCount > 0) {
                        printBtn.classList.add('pulse');
                    } else {
                        printBtn.classList.remove('pulse');
                    }
                }

                // تحديث حالة "تحديد الكل"
                if (selectAllCheckbox) {
                    const allChecked = selectedCount === employeeCheckboxes.length;
                    const someChecked = selectedCount > 0 && selectedCount < employeeCheckboxes.length;
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = someChecked;
                }
            }

            // تحديد/إلغاء تحديد الكل من الـ checkbox الرئيسي
            selectAllCheckbox?.addEventListener('change', function() {
                employeeCheckboxes.forEach(ch => ch.checked = this.checked);
                updateSelectionState();
            });

            // تحديث عند تغيير أي checkbox
            employeeCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectionState);
            });

            // النقر على الصف لتحديد الموظف
            document.querySelectorAll('.employee-row').forEach(row => {
                row.addEventListener('click', function(e) {
                    if (e.target.type !== 'checkbox') {
                        const checkbox = this.querySelector('.employee-checkbox');
                        if (checkbox) {
                            checkbox.checked = !checkbox.checked;
                            updateSelectionState();
                        }
                    }
                });
            });

            // أزرار التحكم
            document.getElementById('selectAllBtn')?.addEventListener('click', function() {
                employeeCheckboxes.forEach(ch => ch.checked = true);
                updateSelectionState();
            });

            document.getElementById('deselectAllBtn')?.addEventListener('click', function() {
                employeeCheckboxes.forEach(ch => ch.checked = false);
                updateSelectionState();
            });

            document.getElementById('invertSelectionBtn')?.addEventListener('click', function() {
                employeeCheckboxes.forEach(ch => ch.checked = !ch.checked);
                updateSelectionState();
            });

            // التحقق قبل إرسال النموذج
            document.getElementById('printForm')?.addEventListener('submit', function(e) {
                const selectedCount = document.querySelectorAll('.employee-checkbox:checked').length;
                if (selectedCount === 0) {
                    e.preventDefault();
                    alert('يرجى اختيار موظف واحد على الأقل قبل الطباعة');
                }
            });

            // تهيئة أولية
            updateSelectionState();
        });
    </script>
@endsection
