@extends('layouts.master')

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الأجور</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ إعدادات المردودية </span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                    <h3 class="card-title mb-2 mb-sm-0">
                        <i class="fas fa-cog ml-2"></i>إعدادات المردودية
                    </h3>
                </div>
                <div class="card-body">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <!-- اختيار السنة (يمين) -->
                        <form method="GET" action="{{ route('prime_rendements.settings.months') }}" class="mb-0">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <select name="year" class="form-control" onchange="this.form.submit()">
                                    @for ($i = date('Y') - 2; $i <= date('Y') + 2; $i++)
                                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </form>

                        <!-- زر إضافة (يسار) -->
                        <button class="btn btn-success" data-toggle="modal" data-target="#addQuarterModal">
                            <i class="fas fa-plus ml-2"></i> إضافة
                        </button>
                    </div>

                    <div class="table-responsive border rounded mt-4">
                        <table class="table key-buttons text-md-nowrap" id="usersTable">
                            <thead>
                                <tr class="text-center">
                                    <th style="width: 120px;">السنة</th>
                                    <th style="width: 220px;">الثلاثي</th>
                                    <th style="width: 140px;">الحالة</th>
                                    <th style="width: 260px;">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($monthSettings as $setting)
                                    <tr class="text-center">
                                        <td class="font-weight-bold">{{ $setting->year }}</td>
                                        <td class="font-weight-bold">{{ $quarters[$setting->quarter] ?? $setting->quarter }}
                                        </td>
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
                                            <div class="btn-group" role="group" aria-label="actions">
                                                <form action="{{ route('prime_rendements.settings.toggle', $setting->id) }}"
                                                    method="POST" class="toggle-form" style="display:inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="btn btn-sm {{ $setting->is_open ? 'btn-warning' : 'btn-success' }}"
                                                        data-toggle="tooltip"
                                                        title="{{ $setting->is_open ? 'إغلاق الثلاثي' : 'فتح الثلاثي' }}">
                                                        <i
                                                            class="fas {{ $setting->is_open ? 'fa-lock' : 'fa-unlock' }}"></i>
                                                        {{ $setting->is_open ? 'إغلاق' : 'فتح' }}
                                                    </button>
                                                </form>
                                               @if ($setting->is_open)
                                                    <!-- زر الحجز إذا الفترة مفتوحة -->
                                                    <a href="{{ route('prime_rendements.create', ['year' => $setting->year, 'quarter' => $setting->quarter]) }}"
                                                        class="btn btn-sm btn-primary" data-toggle="tooltip"
                                                        title="حجز المردودية">
                                                        <i class="fas fa-edit"></i> حجز
                                                    </a>
                                                @else
                                                    <!-- زر المعاينة إذا الفترة مغلقة -->
                                                    <a href="{{ route('prime_rendements.show', [
                                                        'year' => $setting->year,
                                                        'quarter' => $setting->quarter,
                                                        'adm' => $currentAdm ?? null,
                                                    ]) }}"
                                                        class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i>
                                                        معاينة</a>
                                                @endif

                                                <a href="{{ route('prime_rendements.details', ['year' => $setting->year, 'quarter' => $setting->quarter]) }}"
                                                    class="btn btn-sm btn-info" data-toggle="tooltip" title="تفاصيل">
                                                    <i class="fas fa-list"></i> تفاصيل
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                                @if ($monthSettings->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5">
                                            <i class="far fa-folder-open fa-2x d-block mb-2"></i>
                                            لا توجد بيانات
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Modal إضافة ثلاثي -->
    <div class="modal fade" id="addQuarterModal" tabindex="-1" role="dialog" aria-labelledby="addQuarterModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('prime_rendements.settings.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addQuarterModalLabel">إضافة مردودية جديدة</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>السنة</label>
                            <select name="year" class="form-control" required>
                                @for ($i = date('Y') - 2; $i <= date('Y') + 2; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <label>الثلاثي</label>
                            <select name="quarter" class="form-control" required>
                                @foreach ($quarters as $num => $name)
                                    <option value="{{ $num }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-success">حفظ</button>
                    </div>
                </div>
            </form>
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
