<div id="expenseRegisterModal" data-target="" class="modal fade bs-modal-lg" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal submit_form_response" role="form" method="post" action="{{ route('expenses-add-submit') }}">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add New Expense</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="expense_register_expense_type_id" class="col-md-3 control-label">Expense Type</label>
                                <div class="col-md-9">
                                    <select name="expense_type_id" id="expense_register_expense_type_id" class="form-control">
                                        <option selected disabled></option>
                                        @foreach(\App\ExpenseType::all() as $expenseType)
                                            <option value="{{ $expenseType->id }}">{{ $expenseType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="expense_register_expense_by" class="col-md-3 control-label">Expense By</label>
                                <div class="col-md-9">
                                    <input type="text" id="expense_register_expense_by" name="expense_by" class="form-control" placeholder="Expense By">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="expense_register_amount" class="col-md-3 control-label">Amount</label>
                                <div class="col-md-9">
                                    <input type="number" min="0" id="expense_register_amount" name="amount" class="form-control" placeholder="Amount">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="expense_register_date" class="col-md-3 control-label">Date</label>
                                <div class="col-md-9">
                                    <input type="text" id="expense_register_date" name="date" class="form-control date date-picker"
                                           value="{{ date('Y/m/d') }}" data-date-format="yyyy/mm/dd">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="expense_register_remark" class="col-md-2 control-label">Remark</label>
                                <div class="col-md-9">
                                    <textarea name="remark" id="expense_register_remark" cols="30" rows="5" class="form-control"></textarea>
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
        function showExpenseRegisterModal() {
            $("#expenseRegisterModal").modal('show');
        }
    </script>
@endsection
