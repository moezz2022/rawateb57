<div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg custom-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="transferModalLabel">تحويل الموظف</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="transferForm" method="POST" action="{{ route('users.updateGroup') }}">
                    @csrf
                    <input type="hidden" name="employee_id" id="employeeId">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>الرتبة</label>
                            <select id="CODFONC" name="CODFONC" class="form-control">
                                <option value="" disabled>-- الرجاء الاختيار --</option>
                                @foreach ($grades as $grade)
                                    <option value="{{ $grade->codtab }}">{{ $grade->name }}</option>
                                @endforeach
                            </select>                                                       
                        </div>
                        <div class="form-group col-md-6">
                            <label for="receiving_institution">المؤسسة المستقبلة</label>
                            <select id="AFFECT" name="AFFECT" class="form-control">
                                <option value="">-- الرجاء الاختيار --</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->AFFECT }}">{{ $group->name }}</option>
                                @endforeach
                            </select>                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">إغلاق</button>
                        <button type="button" class="btn strong btn-purple btn-lg btn-fill" id="confirmTransfer">تأكيد التحويل</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
