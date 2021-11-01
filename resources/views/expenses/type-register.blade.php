<div id="expenseTypeRegisterModal" data-target="" class="modal fade bs-modal-sm" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form class="form-horizontal submit_form_response" role="form" method="post" action="{{ route('expense-types-add-submit') }}">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add New Expense Type</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="expense_register_name" class="col-md-3 control-label">Name</label>
                                <div class="col-md-9">
                                    <input type="text" id="expense_register_name" name="name" class="form-control" placeholder="Name">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn green">Add</button>
                    <button type="reset" data-dismiss="modal" class="btn default">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@section('extraJS')
    @parent
    <script>
        function showExpenseTypeRegisterModal() {
            $("#expenseTypeRegisterModal").modal('show');
        }
    </script>
@endsection
