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
                    المؤسسات</span>
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
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mg-b-0"><i class="fa fa-building icon-text-spacing"></i>قائمة المؤسسات</h4>
                        <a href="{{ route('groups.add') }}" class="btn btn-danger">
                            <i class="fa-solid fa-pen-to-square ml-1"></i>إضافة مؤسسة
                        </a>
                    </div>
                </div>
                <div class="import-form m-2">
                    <h5 class="mb-3"><i class="fas fa-file-import ml-2"></i>استيراد بيانات المؤسسات</h5>
                    <form  id ="formImport" action="{{ route('groups.import') }}" method="POST" enctype="multipart/form-data"
                        class="row align-items-end">
                        @csrf
                        <div class="col-md-8">
                            <div class="form-group mb-0">
                                <label for="file">اختر ملف Excel:</label>
                                <input type="file" name="file" id="file" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-purple w-100">
                                <i class="fas fa-upload ml-2"></i>استيراد البيانات
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-md-nowrap" id="groupsTable">
                            <thead>
                                <tr>
                                    <th class="wd-5p">#</th>
                                    <th class="wd-15p">رمز المؤسسة</th>
                                    <th class="wd-25p">اسم المؤسسة/الإدارة</th>
                                    <th class="wd-20p">طبيعة المؤسسة</th>
                                    <th class="wd-25p">اسم المؤسسة/الإدارة التابعة لها</th>
                                    <th class="wd-10p">العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($groups as $index => $group)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $group['AFFECT'] }}</td>
                                        <td>{{ $group['name'] }}</td>
                                        <td>
                                            <span
                                                class="badge badge-pill 
                                            @if ($group->type == 1) badge-primary 
                                            @elseif($group->type == 2) badge-success 
                                            @elseif($group->type == 3) badge-info 
                                            @else badge-secondary @endif
                                            p-2">
                                                {{ $group->type_label }}
                                            </span>
                                        </td>
                                        <td>{{ $group->parent ? $group->parent->name : '-' }}</td>
                                        <td>
                                            <div class="action-buttons">
                                                <a class="btn btn-sm btn-warning"
                                                    href="{{ route('groups.edit', $group->id) }}">
                                                    <i class="las la-edit"></i>تعديل
                                                </a>
                                                <a class="btn btn-sm btn-danger" data-effect="effect-scale"
                                                    data-toggle="modal" href="#delete{{ $group->id }}">
                                                    <i class="las la-trash"></i>حـذف
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="empty-state">
                                                <i class="fas fa-database fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">لا توجد بيانات لعرضها</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div><!-- bd -->
            </div><!-- bd -->
        </div>
        @include('groups.delete')
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
            alertify.set('notifier', 'delay', 3);
            @if (session('success'))
                alertify.success("{{ session('success') }}");
            @endif
            @if (session('error'))
                alertify.error("{{ session('error') }}");
            @endif
        });
    </script>
@endsection
