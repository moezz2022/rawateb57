@php
    use Illuminate\Support\Facades\Storage;
    $attachmentPath = 'public/attachments/' . $messages->attachment;
    $fileExists = Storage::exists($attachmentPath);
@endphp
@extends('layouts.master')
@section('css')
    <link href="{{ asset('assets/plugins/summernote/summernote-bs4.css') }}" rel="stylesheet">
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المراسلات</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ إعادة توجيه</span>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                     <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mg-b-0"><i class="fa-solid fa-envelope-open-text side-menu__icon"></i>إعادة توجيه رسالة
                        </h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('messages.forwardStore', ['id' => $messages->id]) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                            <input type="hidden" name="original_message_id" value="{{ $messages->id }}">
                            <input type="hidden" name="attachment" value="{{ json_encode($messages->attachment) }}">

                        <div class="form-group position-relative">
                            <label for="receiver_group_id">المستلم:</label>
                            <div class="selected-receivers" id="receiver_group_name" style="height: 38px;">
                                <!--  إضافة أسماء المستلمين هنا -->
                            </div>
                            <button type="button" class="btn strong btn-danger btn-sm btn-fill position-absolute"
                                data-toggle="modal" data-target="#receiverModal" style="font-size: 1rem";> <i
                                    class="fas fa-user-check"></i>
                                اختر المستلم
                            </button>
                            <input type="hidden" class="form-control @error('receiver_group_id') is-invalid @enderror"
                                name="receiver_group_id" id="receiver_group_id" value="" required>
                            @error('receiver_group_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="subject">الموضوع:</label>
                            <input type="text" name="subject" id="subject" class="form-control"
                                value="{{ old('subject', $messages->subject) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="body">نص الموضوع:</label>
                            <textarea name="body" id="summernote" style="display: none;">{{ old('body', $messages->body) }}</textarea>
                        </div>
                        <div class="email-body mt-1">
                            <strong>المرفقات:</strong>
                            <div class="emai-img">
                                <div class="d-sm-flex">
                                    @if ($messages->attachments->isNotEmpty())
                                        <div class="m-1">
                                            @foreach ($messages->attachments as $index => $attachment)
                                                <a href="{{ route('attachments.download', ['filename' => $attachment->filename]) }}"
                                                    download>تحميل المرفق {{ $index + 1 }}</a>
                                                <br>
                                            @endforeach
                                        </div>
                                    @else
                                        <p>لا يوجد مرفقات</p>
                                    @endif
                                </div>
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
    @include('messages.receiverModal')
@endsection
@section('js')
    <script src="{{ asset('assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
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
