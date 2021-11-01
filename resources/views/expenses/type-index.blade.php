<div id="expenseTypeModal" data-target="" class="modal fade bs-modal-sm" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">View Expense Types</h4>
            </div>
            <div class="modal-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(\App\ExpenseType::all() as $expenseType)
                        <tr>
                            <td>{{ $expenseType->id }}</td>
                            <td>{{ $expenseType->name }}</td>
                            <td>
                                @if(Auth::user()->hasRole('Admin'))
                                    <button onclick="deleteExpenseTypeRecord('{{ $expenseType->id }}')" class="btn btn-xs btn-danger">
                                        <i class='fa fa-trash'></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn default">Close</button>
            </div>
        </div>
    </div>
</div>
@include('common.delete-confirm', ['subject' => 'Expense Type', 'route' => route('expense-types-delete')])
@section('extraJS')
    @parent
    <script>
        function showExpenseTypeModal() {
            $("#expenseTypeModal").modal('show');
        }

        function deleteExpenseTypeRecord(expenseTypeId) {
            $("#delete_expenseType_id").val(expenseTypeId);
            $("#expenseTypeDeleteModal").modal('show');
        }
    </script>
@endsection
