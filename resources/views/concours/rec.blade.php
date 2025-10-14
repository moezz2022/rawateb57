
 <div class="modal fade" id="data-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-coustom modal-xxl" role="document">        
        <div class="modal-content">
            <div class="text-center bg-info p-3" id="model-header">
                <h4 class="modal-title text-white" id="info-header-modalLabel">دراسة ملف المترشح</h4>
            </div>
            <form id="traitDiplomes">
                <input type="hidden" name="matricule" id="matricule">
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-8">
                                <table class="table table-striped table-bordered text-center" dir="rtl">
                                    <thead></thead>
                                    <tbody>
                                        <tr class="text-center align-middle">
                                            <td style="padding:0.25px">اللقب</td>
                                            <td id="nom" class="fw-bold" style="padding:0.25px"></td>
                                            <td style="padding:0.25px">الاسم</td>
                                            <td id="prenom" class="fw-bold" style="padding:0.25px"></td>
                                        </tr>
                                        <tr class="text-center align-middle">
                                            <td style="padding:0.25px">تاريخ ومكان الميلاد</td>
                                            <td id="date_wilnais" class="fw-bold" style="padding:0.25px"></td>
                                            <td style="padding:0.25px">الجنس</td>
                                            <td id="sexe" class="fw-bold" style="padding:0.25px"></td>
                                        </tr>
                                        <tr class="text-center align-middle">
                                            <td style="padding:0.25px">الوضعية العائلية</td>
                                            <td id="sfamail" class="fw-bold" style="padding:0.25px"></td>
                                            <td style="padding:0.25px">عدد الأولاد</td>
                                            <td id="nbenfant" class="fw-bold" style="padding:0.25px"></td>
                                        </tr>
                                        <tr class="text-center align-middle">
                                            <td style="padding:0.25px">الوضعية تجاه الخدمة الوطنية</td>
                                            <td id="service_national" class="fw-bold" style="padding:0.25px"></td>
                                            <td style="padding:0.25px">رقم الوثيقة (تاريخ الإصدار)</td>
                                            <td id="ref_srvn" class="fw-bold" style="padding:0.25px"></td>
                                        </tr>
                                        <tr class="text-center align-middle">
                                            <td style="padding:0.25px">العنوان</td>
                                            <td id="adresse" class="fw-bold" style="padding:0.25px"></td>
                                            <td style="padding:0.25px">بلدية الإقامة</td>
                                            <td id="cd_adr" class="fw-bold" style="padding:0.25px"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-striped table-bordered text-center" dir="rtl">
                                    <thead>
                                        <tr>
                                            <th>الوثيقة</th>
                                            <th>معاينة</th>
                                            <th>دراسة</th>
                                        </tr>
                                    </thead>
                                    <tbody id="diplomsData">
                                        <tr>
                                            <td colspan="3">جاري التحميل...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-4">
                                <div class="text-center">
                                    <h5>معاينة الملف</h5>
                                </div>
                                <div class="card-body">
                                    <iframe id="ph1" style="border:none;min-height: 320px;width:100%;"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row w-100">
                        <div class="col-md-6">
                        <button type="button" class="btn btn-danger w-100" data-dismiss="modal">رجوع</button>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-success w-100">تأكيد</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
