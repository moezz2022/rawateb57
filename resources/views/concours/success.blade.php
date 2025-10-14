@extends('layouts.master2')
@section('css')
@endsection
@section('content')
<div class="container mt-5">
    <div class="alert alert-success text-center">
        <h1>🎉 تم التسجيل بنجاح!</h1>
        <p>شكرًا لتسجيلك معنا. يمكنك الآن طباعة استمارة التسجيل الخاصة بك.</p>
    </div>

    <div class="card">

        <div class="card-body">

            <div class="text-center m-4">
                <!-- رابط لتحميل PDF -->
                @if ($concours)
                    <a href="{{ route('concours.Docprint', ['id' => $concours->id]) }}"  type="button"
                        class="btn btn-primary btn-lg" target="_blank">🖨️ طباعة الاستمارة </a>
                @else
                    <p>لا توجد بيانات للتحميل.</p>
                @endif

                <!-- رابط العودة إلى صفحة التسجيل -->
                <a href="{{ route('concours.register') }}" type="button" class="btn btn-primary btn-lg">
                    <i class="fas fa-arrow-left"></i> العودة إلى صفحة التسجيل
                </a>
            </div>
        </div>

    </div>
    @endsection
    @section('js')  
    <script>
        document.getElementById('printButton').addEventListener('click', function () {
            window.print();
        });
    </script>
    @endsection