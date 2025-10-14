<!-- Modal -->
@foreach ($employees as $employee)
<div class="modal fade" id="delete{{ $employee->id }}" tabindex="-1"  role="dialog" aria-hidden="true  aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-lg custom-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="exampleModalLabel">حذف موظف</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('employees.destroy', $employee->id) }}" method="post">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <h5>هل أنت متأكد من حذف الموظف: {{ $employee->NOMA }} {{ $employee->PRENOMA }}.</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <button type="submit" class="btn btn-danger">تأكيد الحذف</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
