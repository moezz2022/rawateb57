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
                <h4 class="content-title mb-0 my-auto">المستخدمين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تفعيل
                    حسابات المستخدمين</span>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-users"></i> إدارة حسابات المستخدمين</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-md-nowrap" id="activeuser">
                            <thead>
                                <tr>
                                    <th class="wd-5p border-bottom-0">#</th>
                                    <th class="wd-15p border-bottom-0">الاسم واللقب</th>
                                    <th class="wd-10p border-bottom-0">رقم الهاتف</th>
                                    <th class="wd-15p border-bottom-0">البريد الإلكتروني</th>
                                    <th class="wd-15p border-bottom-0">اسم المستخدم</th>
                                    <th class="wd-15p border-bottom-0">نوع المستخدم</th>
                                    <th class="wd-30p border-bottom-0">اسم المؤسسة /الهيئة</th>
                                    <th class="wd-10p border-bottom-0">الحالة</th>
                                    <th class="wd-25p border-bottom-0">العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $index => $user)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>
                                            @switch($user->role)
                                                @case('admin')
                                                    مشرف عام
                                                @break

                                                @case('office_head')
                                                    رئيس مصلحة / مكتب
                                                @break

                                                @case('director')
                                                    مدير مؤسسة
                                                @break

                                                @case('manager')
                                                    المسير المالي
                                                @break

                                                @case('inspector')
                                                    مفتش
                                                @break

                                                @default
                                                    غير محدد
                                            @endswitch
                                        </td>
                                        <td>{{ $user->subGroup ? $user->subGroup->name : '' }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span
                                                    class="status-indicator {{ $user->is_active ? 'status-active' : 'status-inactive' }}"></span>
                                                <form
                                                    action="{{ $user->is_active ? route('users.deactivate', $user->id) : route('users.activateuser', $user->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    <label class="switch">
                                                        <input type="checkbox" class="toggle-active"
                                                            data-id="{{ $user->id }}"
                                                            {{ $user->is_active ? 'checked' : '' }}>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </form>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a class="btn btn-sm btn-warning"
                                                    href="{{ route('users.edit', $user->id) }}">
                                                    <i class="las la-edit"></i> تعديل
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">لا يوجد مستخدمين</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="alert-container" class="alertify-notifier ajs-bottom ajs-right"></div>
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
            window.appConfig = {
                csrf: "{{ csrf_token() }}",
                activateUrl: "{{ url('users/activate') }}",
                deactivateUrl: "{{ url('users/deactivate') }}"
            };
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
