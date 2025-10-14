@extends('layouts.master')
@section('content')
    <div class="row row-sm">
        <div class="col-lg-12 col-xl-12 col-md-12 mt-5">
            <div class="card">
                <div class="main-content-body main-content-body-mail card-body mt-1">
                <nav class="navbar navbar-expand-lg navbar-purple bg-purple mb-2">
                    <div class="container d-flex justify-content-between gap-2">
                        <a class="navbar-brand text-white p-0 me-2" href="{{ route('messages.create') }}">
                            <i class="fa-solid fa-envelope-open-text side-menu__icon"></i> رسالة جديدة
                        </a>
                        <a class="navbar-brand text-white p-0 me-2" href="{{ route('messages.inbox') }}">
                            <i class="fas fa-inbox icon-text-spacing"></i> البريد الوارد
                        </a>
                        <a class="navbar-brand text-white p-0 me-2" href="{{ route('messages.outbox') }}">
                            <i class="fas fa-paper-plane icon-text-spacing"></i> البريد الصادر
                        </a>
                        <a class="navbar-brand text-white p-0 me-2" href="{{ route('messages.saved') }}">
                            <i class="fas fa-save icon-text-spacing"></i> الرسائل المحفوظة
                        </a>
                        <a class="navbar-brand text-white p-0 me-2" href="{{ route('messages.trash') }}">
                            <i class="fas fa-trash icon-text-spacing"></i> الرسائل المحذوفة
                        </a>
                        @if (auth()->user()->role === 'admin')
                            <a class="navbar-brand text-white p-0 me-2" href="{{ route('groups.index') }}">
                                <i class="fa fa-building icon-text-spacing"></i> قائمة المؤسسات
                            </a>
                            <a class="navbar-brand text-white p-0 me-2" href="{{ route('employees.index') }}">
                                <i class="fa fa-users icon-text-spacing"></i> قائمة الموظفين
                            </a>
                            <a class="navbar-brand text-white p-0 me-2" href="{{ route('users.activeuser.index') }}">
                                <i class="fa-solid fa-key icon-text-spacing"></i> تفعيل الحسابات
                            </a>
                        @endif
                    </div>
                </nav>
                    <form action="{{ route('messages.search') }}" method="GET">
                        <div class="input-group mb-1 float-left">
                            <input type="text" name="search" class="form-search" placeholder="ابحث عن الرسالة">
                            <button class="btn btn-outline position-search " type="submit">
                                <i class="fas fa-search"></i> 
                            </button>
                        </div>
                    </form>
                    <form id="deleteMessagesForm" action="{{ route('messages.delete') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="main-mail-options">
                            <label class="ckbox">
                                <input id="checkAll" type="checkbox"> <span>تحديد الكل</span>
                            </label>
                            <div class="btn-group">
                                <button class="btn btn-light" type="button" disabled><i class="bx bx-undo"></i></button>
                                <button type="button" class="btn btn-light btn-delete" id="deleteButton" disabled>
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="main-mail-list">
                            <div class="main-mail-list">
                                @forelse ($messages as $message)  
                                    @php
                                        $isRead =
                                            $message->groups
                                                ->where('id', auth()->user()->groups->pluck('id')->first())
                                                ->first()->pivot->is_read ?? 0;
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
                                            <div class="main-mail-star">
                                                <i class="typcn typcn-star {{ $message->isSaved() ? 'starred' : '' }} save-message"
                                                    data-message-id="{{ $message->id }}"></i>
                                            </div>
                                            <div class="main-img-user">
                                                @php
                                                    $sender = $message->sender;
                                                @endphp
                                                <img alt="user-img" class="avatar brround"
                                                    src="{{ asset('storage/' . ($sender->avatar ?? 'default-avatar.png')) }}">
                                            </div>
                                            <div class="main-mail-body">
                                                <div class="main-mail-from">
                                                    @if ($message->sender)
                                                        @if ($message->sender->subGroup)
                                                            {{ $message->sender->subGroup->name }}<br>
                                                        @elseif ($message->sender->mainGroup)
                                                            {{ $message->sender->mainGroup->name }}<br>
                                                        @else
                                                            المجموعة غير محددة
                                                        @endif
                                                    @else
                                                        <p>المرسل غير موجود</p>
                                                    @endif
                                                </div>
                                                <div class="main-mail-subject">
                                                <strong>{{ $message->subject }}</strong><br>
                                                </div>
                                            </div>
                                            <div class="main-mail-date">
                                                {{ $message->created_at->format('H:i Y-m-d ') }}
                                            </div>
                                            @if ($message->attachments->isNotEmpty())
                                            <div class="main-mail-attachment mt-4">
                                                <i class="typcn typcn-attachment"></i>
                                            </div>
                                            @endif
                                        </div>
                                    </a>
                                @empty
                                    <div class="alert alert-warning text-center">لا توجد رسائل بهذا الموضوع</div>
                                @endforelse
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script>
    $(document).ready(function() {
        alertify.set('notifier', 'position', 'top-left');
        alertify.set('notifier', 'delay', 3);
        @if (session('error'))
            alertify.error("{{ session('error') }}");
        @elseif (session('success'))
            alertify.success("{{ session('success') }}");
        @elseif (session('info'))
            alertify.message("{{ session('info') }}");
        @elseif (session('warning'))
            alertify.warning("{{ session('warning') }}");
        @endif
    });
</script>
@endsection
