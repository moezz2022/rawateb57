<div class="card-body">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center text-white">
            <div class="card-title">
                <form action="" method="GET" id="yearFormrndm" class="d-flex align-items-center gap-2">
                    <i class="fas fa-calendar ml-2"></i>
                    <select name="yearrndm" class="form-control"
                        onchange="document.getElementById('yearFormrndm').submit()">
                        @foreach ($yearsrndm as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            @forelse ($rndm_migrations as $rndm_migration)
                <div class="d-flex justify-content-between align-items-center border-bottom p-2">
                    <h5>
                        {{ $rndm_migration->TITLE }}
                    </h5>
                    <div class="btn-group">
                        <a href="{{ url('/paie/rndm_details') }}?trimestre={{ $rndm_migration->TRIMESTER }}&year={{ $rndm_migration->YEAR }}&adm={{ $firstAdm->ADM ?? '' }}"
                            class="btn btn-outline-danger btn-salary-details">
                            <i class="fa-solid fa-check"></i> الثلاثي {{ $rndm_migration->TRIMESTER }}
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-3 text-center text-muted">
                    لا توجد مردودية متاحة حاليا
                </div>
            @endforelse
        </div>
    </div>
</div>
