@extends('layouts.master')

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الأجور</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ الغيابات الشهرية</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-calendar-alt ml-2"></i> الغيابات الشهرية
                    </h3>
                    <!-- اختيار السنة -->
                    <form method="GET" action="{{ route('monthly_absences.settings') }}" class="mb-0">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <select name="year" class="form-control col-8" onchange="this.form.submit()">
                                @for ($i = date('Y') - 2; $i <= date('Y') + 2; $i++)
                                    <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    <div class="table-responsive border rounded mt-4">
                        <table class="table key-buttons text-md-nowrap" id="usersTable">
                            <thead>
                                <tr class="text-center">
                                    <th>السنة</th>
                                    <th>الشهر</th>
                                    <th>الحالة</th>
                                    <th>الإجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($monthSettings as $setting)
                                    <tr class="text-center">
                                        <td class="font-weight-bold">{{ $setting->year }}</td>
                                        <td>{{ $months[$setting->month] ?? $setting->month }}</td>
                                        <td>
                                            @if ($setting->is_open)
                                                <span class="badge badge-success px-3 py-2">
                                                    <i class="fas fa-unlock ml-1"></i> مفتوح
                                                </span>
                                            @else
                                                <span class="badge badge-danger px-3 py-2">
                                                    <i class="fas fa-lock ml-1"></i> مغلق
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($setting->is_open)
                                                <a href="{{ route('monthly_absences.create', ['year' => $setting->year, 'month' => $setting->month]) }}"
                                                    class="btn btn-sm btn-success">
                                                    <i class="fas fa-edit"></i> حجز
                                                </a>
                                            @else
                                                <span class="text-muted">غير متاح</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5">
                                            <i class="far fa-folder-open fa-2x d-block mb-2"></i>
                                            لا توجد بيانات
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            @if (session('success'))
                alertify.success('{{ session('success') }}');
            @endif

            @if (session('error'))
                alertify.error('{{ session('error') }}');
            @endif
        });
    </script>
@endsection
