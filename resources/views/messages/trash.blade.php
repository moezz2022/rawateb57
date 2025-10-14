@extends('layouts.master')
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المراسلات</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ البريد المحذوف</span>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row row-sm main-content-mail">
        <div class="col-lg-12 col-xl-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mg-b-0"><i class="fa-solid fas fa-trash side-menu__icon"></i>الرسائل
                            المحذوفة
                        </h4>
                    </div>
                </div>
                <div class="main-content-body main-content-body-mail card-body">
                    <div class="main-mail-header">
                        <div class="col-8">
                            <form action="{{ route('messages.search') }}" method="GET">
                                <div class="input-group mb-1 float-left">
                                    <input type="text" name="search" class="form-search" placeholder="ابحث عن الرسالة">
                                    <button class="btn btn-outline-secondary position-search " type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div>
                            <span>
                                {{ ($messages->currentPage() - 1) * $messages->perPage() + 1 }} -
                                {{ min($messages->currentPage() * $messages->perPage(), $messages->total()) }} من
                                {{ $messages->total() }}
                            </span>
                            <div class="btn-group" role="group">
                                <button class="btn btn-outline-secondary {{ $messages->hasMorePages() ? '' : 'disabled' }}"
                                    type="button" onclick="window.location='{{ $messages->nextPageUrl() }}'">
                                    <i class="icon ion-ios-arrow-forward"></i>
                                </button>
                                <button class="btn btn-outline-secondary {{ $messages->onFirstPage() ? 'disabled' : '' }}"
                                    type="button" onclick="window.location='{{ $messages->previousPageUrl() }}'">
                                    <i class="icon ion-ios-arrow-back"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <form id="messagesForm" action="{{ route('messages.manage') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="main-mail-options">
                            <label class="ckbox">
                                <input id="checkAll" type="checkbox"> <span>تحديد الكل</span>
                            </label>
                            <div class="btn-group">
                                <button class="btn btn-light" type="button" id="restoreButton" disabled>
                                    <i class="bx bx-undo"></i>
                                </button>
                                <button type="button" class="btn btn-light" id="permanentlyDeleteButton" disabled>
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="message-item">
                            @forelse ($messages as $deletedMessage)
                                @php
                                    $message = $deletedMessage->message;
                                @endphp
                                <a href="{{ route('messages.show', ['slug' => $message->slug]) }}"
                                    class="main-mail-item-link">
                                    <div class="main-mail-item border-bottom-0">
                                        <div class="main-mail-checkbox">
                                            <label class="ckbox">
                                                <input type="checkbox" name="message_ids[]" value="{{ $message->id }}">
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="main-img-user">
                                            @php
                                                $sender = $message->sender;
                                            @endphp
                                            <img alt="user-img" class="avatar avatar-xl brround"
                                                src="{{ asset('storage/' . ($sender->avatar ?? 'default-avatar.png')) }}">
                                        </div>
                                        <div class="main-mail-body">

                                            <div class="main-mail-from">
                                                @if ($message)
                                                    @if ($message->deletedMessages->where('user_id', auth()->id())->isNotEmpty() && $message->sender_id !== auth()->id())
                                                        @if ($message->sender && $message->sender->groups && $message->sender->groups->isNotEmpty())
                                                            @if ($message->is_multiple)
                                                                وجهات متعددة
                                                            @else
                                                                {{ $message->sender->groups->pluck('name')->join(', ') }}
                                                            @endif
                                                        @endif
                                                    @elseif ($message->sender_id === auth()->id() && $message->deletedMessages->where('user_id', auth()->id())->isNotEmpty())
                                                        @if ($message->groups && $message->groups->isNotEmpty())
                                                            @if ($message->is_multiple)
                                                                وجهات متعددة
                                                            @else
                                                                {{ $message->groups->pluck('name')->join(', ') }}
                                                            @endif
                                                        @endif
                                                    @else
                                                        @if ($message->groups && $message->groups->isNotEmpty())
                                                            @if ($message->is_multiple)
                                                                وجهات متعددة
                                                            @else
                                                                {{ $message->groups->pluck('name')->join(', ') }}
                                                            @endif
                                                        @else
                                                            لم يتم تحديد مجموعة
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="main-mail-subject">
                                                <strong>
                                                    {{ $message ? $message->subject : 'لا يوجد موضوع' }}

                                                </strong>
                                            </div>
                                            <div class="main-mail-date">
                                                {{ $message ? $message->created_at->format('H:i:s Y-m-d') : 'غير متاح' }}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="alert alert-warning text-center">لا توجد رسائل في سلة المحذوفات.</div>
                            @endforelse
                        </div>
                    </form>
                    <div class="main-mail-header mt-2">
                        <span>
                            {{ ($messages->currentPage() - 1) * $messages->perPage() + 1 }} -
                            {{ min($messages->currentPage() * $messages->perPage(), $messages->total()) }} من
                            {{ $messages->total() }}
                        </span>
                        <div class="btn-group" role="group">
                            <button class="btn btn-outline-secondary {{ $messages->hasMorePages() ? '' : 'disabled' }}"
                                type="button" onclick="window.location='{{ $messages->nextPageUrl() }}'">
                                <i class="icon ion-ios-arrow-forward"></i>
                            </button>
                            <button class="btn btn-outline-secondary {{ $messages->onFirstPage() ? 'disabled' : '' }}"
                                type="button" onclick="window.location='{{ $messages->previousPageUrl() }}'">
                                <i class="icon ion-ios-arrow-back"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        const restoreRoute = "{{ route('messages.restore') }}";
        const permanentlyDeleteRoute = "{{ route('messages.permanentlyDelete') }}";
    </script>
@endsection
