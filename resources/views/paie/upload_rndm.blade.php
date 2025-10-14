<!-- Modal -->
<div class="modal fade" id="bonusModal" tabindex="-1" aria-labelledby="bonusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg custom-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="bonusModalLabel">تحميل المردودية</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- فورم رفع الملف هنا -->
                <form action="{{ route('upload.process_rndm') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="TITLE">عنوان المردودية:</label>
                        <input type="text" class="form-control" name="TITLE" id="TITLE" placeholder=" مثلا: منحة المردودية وتحسين الأداء التربوي والتسييري للثلاثي الأول" required>
                    </div>
                    <div class="form-group">
                        <label for="LOT">اسم الملف:</label>
                        <input type="text" class="form-control" name="LOT" id="LOT"placeholder=" مثلا:rndm012025" required>
                    </div>

                    <div class="form-group">
                        <label for="TRIMESTER">الثلاثي:</label>
                        <select class="form-control" name="TRIMESTER" id="TRIMESTER" required>
                            <option value="1">الثلاثي الأول</option>
                            <option value="2">الثلاثي الثاني</option>
                            <option value="3">الثلاثي الثالث</option>
                            <option value="4">الثلاثي الرابع</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="YEAR">السنة:</label>
                        <input type="number" class="form-control" name="YEAR" id="YEAR" required>
                    </div>

                    <div class="form-group">
                        <label for="file">رفع الملف:</label>
                        <input type="file" class="form-control" name="file" id="file" accept=".zip" required>
                    </div>
                    <button type="submit" class="btn strong btn-purple float-left">رفع الملف</button>
                </form>
            </div>
        </div>
    </div>
</div>






 