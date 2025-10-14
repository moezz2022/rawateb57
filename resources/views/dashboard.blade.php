@extends('layouts.master')
@section('content')
    <div class="row row-sm">
        <div class="col-lg-12 col-xl-12 col-md-12 mt-5">
            <div class="card mt-4">
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
                        <div class="main-mail-list" id="messagesList">
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
                                            <i class="typcn typcn-star {{ $message->isSaved() ? 'starred' : '' }} save-message"
                                                data-message-id="{{ $message->id }}"></i>
                                        </div>
                                        <div class="main-img-user">
                                            @php $sender = $message->sender; @endphp
                                            <img alt="user-img" class="avatar brround"
                                                src="{{ asset('storage/' . ($sender->avatar ?? 'default-avatar.png')) }}">
                                        </div>
                                        <div class="main-mail-body">
                                            <strong
                                                class="main-mail-from {{ $message->is_read_status ? 'read-group-name' : 'unread-group-name' }}">
                                                {{ $message->sender->subGroup->name ?? ($message->sender->mainGroup->name ?? 'المجموعة غير محددة') }}
                                            </strong>

                                            <div class="main-mail-subject">
                                                <div
                                                    class="{{ $message->is_read_status ? 'read-group-name' : 'unread-group-name' }}">
                                                    {{ $message->subject }}
                                                </div>
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
                                <div class="alert alert-warning text-center">لا يوجد رسائل</div>
                            @endforelse
                        </div>
                    </form>
                </div>
                @if (!in_array(auth()->user()->role, ['office_head', 'inspector']))
                    <div class="main-content-body main-content-body-mail card-body mt-1">
                        <nav class="navbar navbar-expand-lg navbar-purple bg-purple mb-2">
                            <div class="container d-flex justify-content-between gap-2">
                                <a class="navbar-brand text-white p-0 me-2" href="{{ route('users.indexuser') }}">
                                    <i class="fa fa-users side-menu__icon"></i>قائمة الموظفين
                                </a>
                                <a class="navbar-brand text-white p-0 me-2" href="{{ route('cards.select.grade') }}">
                                    <i class="fa-solid fa-id-card icon-text-spacing"></i> البطاقة المهنية
                                </a>
                                <a class="navbar-brand text-white p-0 me-2" href="{{ route('paie.show') }}">
                                    <i class="fa-solid fa-money-bill icon-text-spacing"></i> الكشوف
                                </a>
                                <a class="navbar-brand text-white p-0 me-2" href="{{ route('paie.salaryreport') }}">
                                    <i class="fa-solid fa fa-line-chart icon-text-spacing"></i> التقرير المفصل
                                </a>
                                <a class="navbar-brand text-white p-0 me-2"
                                    href="{{ auth()->user()->role === 'admin' ? route('prime_rendements.rndmsettings') : route('prime_rendements.create') }}">
                                    <i class="fa-solid fa-pen-to-square icon-text-spacing"></i> حجز المردودية
                                </a>
                                <a class="navbar-brand text-white p-0 me-2"
                                    href="{{ auth()->user()->role === 'admin' ? route('monthly_absences.index') : route('monthly_absences.create') }}">
                                    <i class="fa-solid fa-pen-nib icon-text-spacing"></i> حجز الغيابات
                                </a>
                            </div>
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'bottom-start',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });

            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif

            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: "{{ session('error') }}"
                });
            @endif
        });
    </script>
@endsection
