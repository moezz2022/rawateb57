@extends('layouts.master')
@section('css')
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المستخدمين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تحويل
                    الموظفين</span>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0"><i class="fas fa-exchange-alt ml-2"></i> تحويل الموظفين</h3>
                    <div class="d-flex my-xl-auto">
                        <a href="{{ route('users.indexuser') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right ml-1"></i> العودة للقائمة
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="search-container">
                        <h5 class="search-title"><i class="fas fa-search"></i> البحث عن موظف</h5>
                        <form method="GET" action="{{ route('users.transfer') }}" class="mb-0">
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group mb-md-0">
                                        <label for="search">البحث عن الموظف (الاسم أو اللقب أو رقم الحساب البريدي)</label>
                                        <input type="text" class="form-control" id="search" name="search"
                                            placeholder="أدخل الاسم واللقب أو رقم الحساب البريدي"
                                            value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-purple btn-lg btn-block">
                                        <i class="fas fa-search icon-text-spacing"></i>بحث
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if ($employees->isNotEmpty())
                        <div class="results-container mt-5">
                            <div class="results-header">
                                <h5 class="results-title"><i class="fas fa-users"></i> نتائج البحث
                                    ({{ $employees->count() }} موظف)</h5>
                            </div>
                            <div class="results-body">
                                <div class="table-responsive">
                                    <table class="table text-md-nowrap" id="transferTable">
                                        <thead>
                                            <tr>
                                                <th class="wd-5p">#</th>
                                                <th class="wd-20p">الاسم واللقب</th>
                                                <th class="wd-15p">تاريخ الميلاد</th>
                                                <th class="wd-25p">الرتبة</th>
                                                <th class="wd-25p">المؤسسة الأصلية</th>
                                                <th class="wd-10p">إجراء</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($employees as $index => $employee)
                                                <tr>
                                                    <td data-label="#">{{ $index + 1 }}</td>
                                                    <td data-label="الاسم واللقب">
                                                        {{ $employee['NOMA'] . ' ' . $employee['PRENOMA'] }}</td>
                                                    <td data-label="تاريخ الميلاد">{{ $employee['DATNAIS'] }}</td>
                                                    <td data-label="الرتبة">
                                                        @foreach ($grades as $grade)
                                                            @if ($employee->CODFONC == $grade->codtab)
                                                                {{ $grade->name }}
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td data-label="المؤسسة الأصلية">
                                                        {{ $employee->group->name ?? 'غير معروف' }}</td>
                                                    <td data-label="إجراء">
                                                        <button type="button"
                                                            class="btn btn-sm btn-warning transfer-button"
                                                            data-toggle="modal" data-target="#transferModal"
                                                            data-employee-id="{{ $employee->id }}"
                                                            data-employee-name="{{ $employee->NOMA . ' ' . $employee->PRENOMA }}"
                                                            data-employee-codfonc="{{ $employee->CODFONC }}"
                                                            data-employee-affect="{{ $employee->AFFECT }}">
                                                            <i class="fas fa-exchange-alt icon-text-spacing"></i> تحويل
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @elseif(request('search'))
                        <div class="empty-state mt-4">
                            <i class="fas fa-search"></i>
                            <p>لم يتم العثور على أي موظف بهذه المعلومات</p>
                            <p class="text-muted">حاول البحث بمعلومات أخرى</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @include('users.transferModal', ['groups' => $groups])
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
            alertify.set('notifier', 'position', 'bottom-left');
            alertify.set('notifier', 'delay', 3);
            @if (session('success'))
                alertify.success("{{ session('success') }}");
            @endif
            @if (session('error'))
                alertify.error("{{ session('error') }}");
            @endif
            @if (session('status'))
                alertify.error("{{ session('status') }}");
            @endif
        });
    </script>
@endsection
