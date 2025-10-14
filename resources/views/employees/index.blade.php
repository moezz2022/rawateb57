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
                <h4 class="content-title mb-0 my-auto">المستخدمين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ قائمة
                    الموظفين</span>
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
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-users ml-2"></i>قائمة الموظفين</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 d-flex justify-content-start">
                            <a href="{{ route('employees.add') }}" class="btn btn-purple">
                                <i class="fas fa-user-plus ml-1"></i> إضافة موظف
                            </a>
                            <a href="{{ route('employees.statistics') }}" class="btn btn-info mr-2">
                                <i class="fas fa-chart-bar ml-1"></i> إحصائيات
                            </a>
                        </div>
                    </div>
                    
                    <div class="file-upload-container mb-4">
                        <form action="{{ route('employees.import') }}" method="POST"  id="employee-form" enctype="multipart/form-data" class="mb-0">
                            @csrf
                            <div class="row align-items-end">
                                <div class="col-md-9">
                                    <div class="form-group mb-md-0">
                                        <label for="file"><i class="fas fa-file-excel ml-1"></i>استيراد بيانات الموظفين من ملف Excel</label>
                                        <input type="file" name="file" id="file" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-purple btn-block">
                                        <i class="fas fa-upload ml-1"></i> استيراد
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table text-md-nowrap" id="emp">
                            <thead>
                                <tr>
                                    <th class="wd-3p">#</th>
                                    <th class="wd-15p">اللقب والاسم</th>
                                    <th class="wd-12p">تاريخ الميلاد</th>
                                    <th class="wd-27p">الرتبة</th>
                                    <th class="wd-27p">المؤسسة/الهيئة</th>
                                    <th class="wd-15p">العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($employees as $employee)
                                    <tr>
                                        <td data-label="#">{{ $employee['id'] }}</td>
                                        <td data-label="اللقب والاسم">{{ $employee['NOMA'] . ' ' . $employee['PRENOMA'] }}</td>
                                        <td data-label="تاريخ الميلاد">{{ $employee['DATNAIS'] }}</td>
                                        <td data-label="الرتبة">
                                            @foreach ($grades as $grade)
                                                @if ($employee->CODFONC == $grade->codtab)
                                                    {{ $grade->name }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td data-label="المؤسسة/الهيئة">{{ $employee->group->name ?? 'غير معروف' }}</td>
                                        <td data-label="العمليات">
                                            <div class="action-buttons">
                                                <a class="btn btn-sm btn-warning" href="{{ route('employees.edit', $employee->id) }}">
                                                    <i class="fas fa-edit ml-1"></i>تعديل
                                                </a>
                                                <a class="btn btn-sm btn-danger" data-effect="effect-scale" data-toggle="modal" href="#delete{{ $employee->id }}">
                                                    <i class="fas fa-trash-alt ml-1"></i>حـذف
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="empty-state">
                                            <i class="fas fa-user-slash"></i>
                                            لا توجد بيانات موظفين لعرضها
                                            <p class="text-muted mt-2">يمكنك إضافة موظفين جدد أو استيراد بياناتهم من ملف Excel</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div><!-- bd -->
            </div><!-- bd -->
        </div>
        @include('employees.delete')
    </div>
    <!-- row closed -->
@endsection
@section('js')
    <!-- Internal Data tables -->
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
            alertify.set('notifier', 'position', 'bottom-left');
            alertify.set('notifier', 'delay', 6);
            @if (session('success'))
                alertify.success("{{ session('success') }}");
            @endif
        });
    </script>
@endsection
