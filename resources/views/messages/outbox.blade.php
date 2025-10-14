@extends('layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المراسلات</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ البريد الصادر</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row row-sm main-content-mail">
        <div class="col-lg-12 col-xl-12 col-md-12">
            <div class="card">
                    <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mg-b-0"><i class="fa-solid fas fa-paper-plane side-menu__icon"></i>البريد
                            الصادر
                        </h4>
                    </div>
                </div>
                <div class="main-content-body main-content-body-mail card-body">
                    <div class="main-mail-header">
                        <div class="col-8">
                            <form action="{{ route('messages.search') }}" method="GET">
                                <div class="input-group mb-1 float-left">
                                    <input type="text" name="search" class="form-search" placeholder="ابحث عن الرسالة">
                                    <button class="btn btn-outline position-search " type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div>
                            <span>
                                {{ ($messages->currentPage() - 1) * $messages->perPage() + 1 }}
                                -
                                {{ min($messages->currentPage() * $messages->perPage(), $messages->total()) }}
                                من
                                {{ $messages->total() }}
                            </span>
                            <div class="btn-group" role="group">
                                <button class="btn btn-outline-secondary {{ $messages->onFirstPage() ? 'disabled' : '' }}"
                                    type="button" onclick="window.location='{{ $messages->previousPageUrl() }}'">
                                    <i class="icon ion-ios-arrow-forward"></i>
                                </button>
                                <button class="btn btn-outline-secondary {{ $messages->hasMorePages() ? '' : 'disabled' }}"
                                    type="button" onclick="window.location='{{ $messages->nextPageUrl() }}'">
                                    <i class="icon ion-ios-arrow-back"></i>
                                </button>
                            </div>
                        </div>
                    </div><!-- main-mail-list-header -->
                    <form id="deleteMessagesForm" action="{{ route('messages.delete') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="main-mail-options">
                            <label class="ckbox">
                                <input id="checkAll" type="checkbox"> <span>تحديد الكل</span>
                            </label>
                            <div class="btn-group">
                                <button class="btn btn-light" type="button" disabled><i class="bx bx-undo"></i></button>
                                <button type="button" class="btn btn-light" id="deleteButton" disabled>
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div><!-- btn-group -->
                        </div><!-- main-mail-options -->
                        <div class="message-item">
                            @forelse ($messages as $message)
                                <a href="{{ route('messages.show', ['slug' => $message->slug]) }}"
                                    class="main-mail-item-link">
                                    <div class="main-mail-item border-bottom-0">
                                        <div class="main-mail-checkbox">
                                            <label class="ckbox">
                                                <input type="checkbox" name="message_ids[]" value="{{ $message->id }}">
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="main-mail-star">
                                            <i class="typcn typcn-star {{ $message->isSaved() ? 'starred' : '' }}  save-message"
                                                data-message-id="{{ $message->id }}"></i>
                                        </div>
                                        <div class="main-img-user">
                                            @php
                                            $sender = $message->sender; @endphp
                                            <img alt="user-img" class="avatar avatar-xl brround"
                                                src="{{ asset('storage/' . ($sender->avatar ?? 'default-avatar.png')) }}">
                                        </div>
                                        <div class="main-mail-body">
                                            <strong class="main-mail-from">
                                                @if ($message->is_multiple)
                                                    وجهات متعددة
                                                @else
                                                    @if ($message->groups->isNotEmpty())
                                                        {{ $message->groups->pluck('name')->join(', ') }}
                                                    @else
                                                        لم يتم تحديد مجموعة
                                                    @endif
                                                @endif
                                            </strong>
                                            <div class="main-mail-subject">
                                                <div>{{ $message->subject }}</div>
                                            </div>
                                        </div>
                                        <div class="main-mail-date">
                                            {{ $message->created_at->format('H:i:s Y-m-d') }}
                                        </div>
                                        @if ($message->attachments->isNotEmpty())
                                            <div class="main-mail-attachment mt-4">
                                                <i class="typcn typcn-attachment"></i>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            @empty
                                <div class="alert alert-warning text-center">لا توجد رسائل في البريد الصادر</div>
                            @endforelse
                        </div>
                    </form>
                    <div class="main-mail-header mt-2">
                        <span>
                            {{ ($messages->currentPage() - 1) * $messages->perPage() + 1 }}
                            -
                            {{ min($messages->currentPage() * $messages->perPage(), $messages->total()) }}
                            من
                            {{ $messages->total() }}
                        </span>
                        <div class="btn-group" role="group">
                            <button class="btn btn-outline-secondary {{ $messages->onFirstPage() ? 'disabled' : '' }}"
                                type="button" onclick="window.location='{{ $messages->previousPageUrl() }}'">
                                <i class="icon ion-ios-arrow-forward"></i>
                            </button>
                            <button class="btn btn-outline-secondary {{ $messages->hasMorePages() ? '' : 'disabled' }}"
                                type="button" onclick="window.location='{{ $messages->nextPageUrl() }}'">
                                <i class="icon ion-ios-arrow-back"></i>
                            </button>
                        </div>
                    </div>
                </div><!-- main-mail-list-header -->
            </div>
        </div>
    </div>
@endsection
