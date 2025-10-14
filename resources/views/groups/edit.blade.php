@extends('layouts.master')
@section('css')
<!-- Internal Select2 css -->
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المستخدمين</h4>
                <h4 class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل مؤسسة</h4>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    <div class="col-md-12 col-xl-12 col-xs-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="main-content-label mb-4">
                    <h2>تعديل بيانات المؤسسة</h2>
                </div>
                <form action="{{ route('groups.update', $group->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="form-section">
                        <div class="form-group">
                            <label for="AFFECT">
                                <span class="required-star">*</span>رمز المؤسسة/الهيئة:
                            </label>
                            <input name="AFFECT" id="AFFECT" class="form-control @error('AFFECT') is-invalid @enderror"
                                placeholder="أدخل رمز المؤسسة" type="text" value="{{ old('AFFECT', $group->AFFECT) }}" required>
                            @error('AFFECT')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="name">
                                <span class="required-star">*</span>اسم المؤسسة/الهيئة:
                            </label>
                            <input name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                placeholder="أدخل اسم المؤسسة" type="text" value="{{ old('name', $group->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="type">
                                <span class="required-star">*</span>طبيعة المؤسسة/الإدارة:
                            </label>
                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type"
                                required>
                                <option disabled>يرجى اختيار طبيعة المؤسسة..</option>
                                <option value="admin" {{ old('type', $group->type) == 'admin' ? 'selected' : '' }}>مديرية
                                </option>
                                <option value="education" {{ old('type', $group->type) == 'education' ? 'selected' : '' }}>المؤسسات
                                    التربوية</option>
                                <option value="inspection" {{ old('type', $group->type) == 'inspection' ? 'selected' : '' }}>الهيئة
                                    التفتيشية</option>
                            </select>
                            @error('type')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="parent_id">اسم المؤسسة/الإدارة التابعة لها:</label>
                            <select name="parent_id" id="parent_id"
                                class="form-control select2 @error('parent_id') is-invalid @enderror">
                                <option value="">-- لا توجد --</option>
                                @foreach ($groups as $item)
                                    @if($item->id != $group->id)
                                        <option value="{{ $item->id }}"
                                            {{ old('parent_id', $group->parent_id) == $item->id ? 'selected' : '' }}>
                                            {{ $item->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <small class="form-text">اختر المؤسسة الأم التي تتبع لها هذه المؤسسة (اختياري)</small>
                            @error('parent_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('groups.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right ml-2"></i>العودة للقائمة
                        </a>
                        <button type="submit" class="btn btn-purple btn-lg">
                            <i class="fas fa-save ml-2"></i>حفظ التعديلات
                        </button>
                    </div>
                </form>
            </div>
            <div id="alert-container" class="alertify-notifier ajs-bottom ajs-right">
            </div>
        </div>
    </div>
@endsection
@section('js')
    <!-- Internal Select2 js-->
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
@endsection

