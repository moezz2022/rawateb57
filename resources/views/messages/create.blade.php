@extends('layouts.master')
@section('css')
    <link href="{{ asset('assets/plugins/summernote/summernote-bs4.css') }}" rel="stylesheet">
    <!-- Internal Data table css -->
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المراسلات</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ رسالة جديدة</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mg-b-0"><i class="fa-solid fa-envelope-open-text side-menu__icon"></i>رسالة
                            جديدة
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                    <form id="messageForm" action="{{ route('messages.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group position-relative">
                            <label for="receiver_group_id">المستلم:</label>
                            <div class="selected-receivers" name="receiver_group_id" id="receiver_group_name"
                                style="padding: 5px; border: 1px solid #ccc; border-radius: 4px; height: 38px; overflow: auto;"
                                required>
                            </div>
                            <button type="button" class="btn strong btn-danger btn-sm btn-fill position-absolute"
                                data-toggle="modal" data-target="#receiverModal" style="font-size: 1rem" ;> <i
                                    class="fas fa-user-check"></i>
                                اختر المستلم
                            </button>
                            <input type="hidden" class="form-control @error('receiver_group_id') is-invalid @enderror"
                                name="receiver_group_id" id="receiver_group_id" value="" required>
                            @error('receiver_group_id')
                                <span class="invalid-feedback" role="alert">
                                    <h5>{{ $message }}</h5>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="subject">الموضوع:</label>
                            <input type="text" name="subject" id="subject" class="form-control" required>
                        </div>
                        <div class="form-group mb-0">
                            <label for="body">نص الموضوع:</label>
                            <textarea name="body" id="summernote" style="display: none;"></textarea>
                        </div>
                        <div class="form-group mb-0">
                            <div class="row align-items-center">
                                <div class="col-md-3 text-right">
                                    <label class="custom-file-upload" for="file-upload1">
                                        <i class="fa fa-cloud-upload"></i> تحميل المرفقات
                                    </label>
                                    <input type="file" name="attachments[]" id="file-upload1" multiple
                                        style="display:none;">
                                </div>
                                <div class="col-md-4 d-flex justify-content-center">
                                    <div id="dropArea"
                                        style="padding:30px; border:2px dashed red; border-radius:10px; 
                                        text-align:center; display:flex; align-items:center; justify-content:center; font-size:18px; width:100%;">
                                        اسحب الملفات هنا أو اضغط لاختيار الملفات
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <div class="col-md-3">
                                    <div class="files2send">عدد الملفات: <span id="fileCount">0</span></div>
                                    <div class="info4attach mt-3">
                                        <span class="ititle">الحجم الأقصى لمجموع الملفات :</span> 30 م.ب
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-md-nowrap" id="example2">
                                    <thead>
                                        <tr>
                                            <th class="text-center wd-25p border-bottom-0">إسم الملف</th>
                                            <th class="text-center wd-15p border-bottom-0">الحجم</th>
                                            <th class="text-center wd-30p border-bottom-0">تقدم</th>
                                            <th class="text-center wd-10p border-bottom-0">الحالة</th>
                                            <th class="text-center wd-20p border-bottom-0">العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody id="attachmentList">
                                    </tbody>
                                </table>
                                <button type="button" id="uploadAllButton" class="btn btn-outline-warning btn-sm"
                                    style="float: left; margin-top: 10px; margin-left: 25px;">
                                    <i class="fas fa-upload"></i> تحميل الكل
                                </button>
                            </div>
                        </div>
                        <div class="card-footer text-left">
                            <button type="submit" class="btn strong btn-purple"
                                style="padding-y: .25rem; --cui-btn-padding-x: .5rem; font-size: 1.15rem;"> <i
                                    class="fas fa-check" style="padding-left: 0.5rem"></i>إرســال</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    @include('messages.receiverModal')
@endsection
@section('js')
    <script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
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
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    title: 'تم إرسال الرسالة بنجاح',
                    text: '',
                    icon: 'success',
                    confirmButtonText: 'موافق'
                }).then(() => {
                    window.location.href =
                        '{{ route('dashboard') }}';
                });
            @endif
        });
    </script>
@endsection
