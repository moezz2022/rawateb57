<!-- Modal -->
<div class="modal fade" id="salaryModal" tabindex="-1" aria-labelledby="salaryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg custom-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="salaryModalLabel">تحميل الراتب</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- فورم رفع الملف هنا -->
                <form action="{{ route('upload.process') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="LOT">اسم الملف:</label>
                        <input type="text" class="form-control" name="LOT" id="LOT" required>
                    </div>
                    <div class="form-group">
                        <label for="month">الشهر:</label>
                        <select class="form-control" name="month" id="month" required>
                            <option value="">-- اختر الشهر --</option>
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}">
                                    {{ \Carbon\Carbon::create()->month($m)->locale('ar-DZ')->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="year">السنة:</label>
                        <select class="form-control" name="year" id="year" required>
                            <option value="">-- اختر السنة --</option>
                            @foreach (range(now()->year, now()->year - 10) as $y)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="file">رفع الملف:</label>
                        <input type="file" class="form-control" name="file" id="file" accept=".zip"
                            required>
                    </div>
                    <button type="submit" class="btn strong btn-purple float-left">رفع الملف</button>
                </form>
            </div>
        </div>
    </div>
</div>
