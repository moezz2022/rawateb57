@extends('layouts.master')

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الأجور</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">
                    / نقاط المردودية / الثلاثي {{ $quarters[$quarter] ?? $quarter }} {{ $year }}
                </span>
            </div>
        </div>
    </div>
@endsection
@section('content')
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive border rounded mt-4">
                        <table class="table text-md-nowrap text-center">
                            <thead>
                                <tr>
                                    <th>الإدارة</th>
                                    <th>عدد الموظفين الكلي</th>
                                    <th>عدد الموظفين المنقطين</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($departments as $dept)
                                    <tr>
                                        <td>{{ $dept->name }}</td>
                                        <td>{{ $dept->total_employees_count }}</td>
                                        <td>{{ $dept->scored_employees_count }}</td>
                                        <td>
                                            <a href="{{ route('prime_rendements.export.sql', ['year' => $year, 'quarter' => $quarter, 'adm' => $dept->ADM]) }}"
                                               class="btn btn-danger">
                                                <i class="fas fa-database"></i> تنزيل
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($departments->isEmpty())
                                    <tr>
                                        <td colspan="4" class="text-muted py-4">لا توجد إدارات</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

