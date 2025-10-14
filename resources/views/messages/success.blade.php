@extends('layouts.master')
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <h4 class="content-title mb-0 my-auto">المراسلات/</h4>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-check-circle text-success m-4" style="font-size: 15rem;"></i>
                    <h2 class="mb-3">تم إرسال الرسالة بنجاح</h2>
                    <p class="text-muted">يمكنك العودة إلى الصفحة الرئيسية أو إرسال رسالة جديدة.</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-danger">العودة للصفحة الرئيسية</a>
                    <a href="{{ route('messages.create') }}" class="btn btn-outline-success">رسالة جديدة</a>
                </div>
            </div>
        </div>
    </div>
@endsection
