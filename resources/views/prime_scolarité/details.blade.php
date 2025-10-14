@if ($absences->isEmpty())
    <p class="text-muted">لا توجد بيانات غيابات لهذا الشهر.</p>
@else
    <div dir="rtl" class="details-wrapper">
        <style>
            .details-wrapper .summary {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: .75rem;
                margin-bottom: .75rem;
            }
            .details-wrapper .summary-title {
                margin: 0;
                font-weight: 700;
                font-size: 1.05rem;
            }
            .details-wrapper .summary-sub {
                color: #6c757d;
                font-size: .9rem;
            }
            .details-wrapper .toolbar {
                display: flex;
                align-items: center;
                gap: .5rem;
            }
            .details-wrapper .table thead th {
                position: sticky;
                top: 0;
                z-index: 2;
                background: #f8f9fa;
            }
            .badge-soft {
                display: inline-block;
                padding: .35rem .6rem;
                border-radius: 999px;
                background: #eef2f7;
                color: #344767;
                border: 1px solid #dee2e6;
                font-weight: 500;
                white-space: nowrap;
            }
            .text-truncate-1 {
                max-width: 260px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                display: inline-block;
                vertical-align: middle;
            }
            .details-wrapper .small-hint {
                font-size: .8rem;
                color: #6c757d;
            }
        </style>

        <div class="summary">
            <div>
                <p class="summary-title mb-1">تفاصيل الغيابات</p>
                <div class="summary-sub">
                    عدد السجلات: {{ $absences->count() }}
                    | مجموع أيام الغياب: {{ $absences->sum('absence_days') }}
                </div>
            </div>

            <div class="toolbar">
                <input id="detailsSearch" type="text" class="form-control form-control-sm" placeholder="بحث سريع (الموظف/المؤسسة/السبب)">
                <select id="daysFilter" class="form-select form-select-sm" title="تصفية حسب الحد الأدنى لأيام الغياب">
                    <option value="">كل السجلات</option>
                    <option value="1">+ يوم</option>
                    <option value="3">+ 3 أيام</option>
                    <option value="5">+ 5 أيام</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table id="absencesTable" class="table table-hover table-striped table-bordered align-middle table-sm mb-1">
                <thead class="table-light">
                    <tr>
                        <th style="width: 48px;">#</th>
                        <th>الموظف</th>
                        <th>المؤسسة</th>
                        <th style="width: 120px;">أيام الغياب</th>
                        <th>السبب</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($absences as $absence)
                        @php
                            $name = trim(($absence->employee->NOMA ?? '') . ' ' . ($absence->employee->PRENOMA ?? ''));
                            $group = $absence->employee->group->name ?? 'غير معروف';
                            $days = (int) ($absence->absence_days ?? 0);
                            $reason = $absence->absence_reason ?? '';
                            $rowClass = $days >= 5 ? 'table-danger' : ($days >= 3 ? 'table-warning' : '');
                        @endphp
                        <tr
                            class="{{ $rowClass }}"
                            data-name="{{ Str::lower($name) }}"
                            data-group="{{ Str::lower($group) }}"
                            data-reason="{{ Str::lower($reason) }}"
                            data-days="{{ $days }}"
                        >
                            <td class="text-muted small">{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-semibold">{{ $name ?: '—' }}</div>
                                @if (!empty($absence->employee->MATRICULE ?? null))
                                    <div class="small-hint">رقم: {{ $absence->employee->MATRICULE }}</div>
                                @endif
                            </td>
                            <td>{{ $group }}</td>
                            <td class="fw-bold text-center">{{ $days }}</td>
                            <td>
                                @if ($reason)
                                    <span class="badge-soft text-truncate-1" title="{{ $reason }}">{{ $reason }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="small-hint">نصيحة: استخدم البحث أعلاه لتصفية النتائج بسرعة.</div>

        <script>
            (function () {
                const table = document.getElementById('absencesTable');
                const q = document.getElementById('detailsSearch');
                const daysFilter = document.getElementById('daysFilter');

                if (!table) return;
                const rows = Array.from(table.querySelectorAll('tbody tr'));

                function applyFilters() {
                    const term = (q?.value || '').trim().toLowerCase();
                    const minDays = parseInt(daysFilter?.value || '0', 10) || 0;

                    rows.forEach(tr => {
                        const hay = ((tr.dataset.name || '') + ' ' + (tr.dataset.group || '') + ' ' + (tr.dataset.reason || ''));
                        const days = parseInt(tr.dataset.days || '0', 10);
                        const matchesText = term === '' || hay.indexOf(term) !== -1;
                        const matchesDays = days >= minDays;
                        tr.style.display = (matchesText && matchesDays) ? '' : 'none';
                    });
                }

                q && q.addEventListener('input', applyFilters);
                daysFilter && daysFilter.addEventListener('change', applyFilters);
            })();
        </script>
    </div>
@endif
