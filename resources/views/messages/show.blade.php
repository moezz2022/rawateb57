@php
    use Carbon\Carbon;
    use App\Models\Group;
    $formattedDate = Carbon::parse($message->created_at)->locale('ar')->translatedFormat('l d-m-Y h:i a');
@endphp
@extends('layouts.master')
@section('css')
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المراسلات</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تفصيل الرسالة</span>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row row-sm">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="email-media">
                        <div class="mt-0 d-sm-flex">
                            @php $sender = $message->sender; @endphp
                            <img class="ml-2 rounded-circle avatar-xl"
                                src="{{ asset('storage/' . ($sender->avatar ?? 'default-avatar.png')) }}">
                            <div class="media-body">
                                <div class="btn-group float-left d-none d-md-flex fs-15 mr-1">
                                    <div class="btn-group float-left d-none d-md-flex fs-15 mr-1">
                                        <a href="{{ route('messages.forward', $message->slug) }}" class="btn btn-light">
                                            <i class="fas fa-share" title="إعادة توجيه"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="btn-group float-left d-none d-md-flex fs-15 mr-1">
                                    <button id="printDetailsButton" data-sender-group="{{ $senderGroup }}"
                                        data-receiver-groups='@json($receiverGroups)' data-date="{{ $formattedDate }}"
                                        data-subject="{{ $message->subject }}" data-body='@json($message->body)'
                                        data-attachments='@json($attachments)' class="btn btn-light"
                                        type="button">
                                        <i class="fas fa-print" title="طباعة"></i>
                                    </button>

                                </div>
                                <div class="btn-group float-left d-none d-md-flex fs-15">
                                    <button class="btn btn-light" type="button">
                                        <i class="bx bx-star {{ $message->isSaved() ? 'active' : '' }} save-message"
                                            data-message-id="{{ $message->id }}" title="حفظ"></i>
                                    </button>
                                </div>
                                <div class="media-title font-weight-bold mt-3">
                                    من: {{ $senderGroup }} <br>
                                    <p class="mt-3">إلى: {{ implode(' - ', $receiverGroups) ?: 'غير محدد' }}</p>
                                    <span class="mr-1 d-md">تم الإرسال يوم: {{ $formattedDate }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="email-body mt-2">
                            <strong class="email-label">الموضوع:</strong>
                            <span class="email-subject">{{ $message->subject }}</span>
                        </div>
                        <div class="email-body mt-1">
                            <strong>نص الرسالة:</strong>
                            <p class="email-body-content"> {!! $message->body !!}</p>
                        </div>
                        <div class="email-body mt-1">
                            <strong>المرفقات:</strong>
                            <div class="emai-img">
                                <div class="d-sm-flex">
                                    @if ($message->attachments->isNotEmpty())
                                        <div class="m-1">
                                            @foreach ($message->attachments as $index => $attachment)
                                                <a href="{{ route('attachments.download', ['filename' => $attachment->filename]) }}"
                                                    download>تحميل المرفق {{ $index + 1 }}</a>
                                                <br>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted"><i class="fas fa-paperclip"></i> لا توجد مرفقات</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <table id="readTable" class="table table-striped table-hover text-center">
                            <thead>
                                <tr>
                                    <th>المؤسسة/الهيئة</th>
                                    <th>حالة الاطلاع</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($message->groups as $group)
                                    <tr>
                                        <td>{{ $group->name }}</td>
                                        <td>
                                            @if ($group->pivot->is_read)
                                                <span>
                                                    <i class="fas fa-check-circle text-success"></i>
                                                    تم الإطلاع </span>
                                            @else
                                                <span>
                                                    <i class="fas fa-times-circle text-danger"></i>
                                                    لم يتم الإطلاع
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
