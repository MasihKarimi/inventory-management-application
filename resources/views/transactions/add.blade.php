<div id="transactionRegisterModal" data-target="" class="modal fade bs-modal-lg" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal submit_form_response" role="form" method="post" action="{{ route('transactions-new-submit') }}">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add New Transaction</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="transaction_registration_customer_id" class="col-md-4 control-label">Select Customer</label>
                                <div class="col-md-8">
                                    <select id="transaction_registration_customer_id" name="customer_id" class="form-control select2">
                                        <option selected disabled>Please select the customer</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="transaction_registration_balance" class="col-md-4 control-label">Balance</label>
                                <div class="col-md-8">
                                    <input type="text" id="transaction_registration_balance" class="form-control" placeholder="Balance" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="transaction_registration_deal_type_id" class="col-md-4 control-label">Deal Type</label>
                                <div class="col-md-8">
                                    <select name="deal_type_id" id="transaction_registration_deal_type_id" class="form-control">
                                        <option selected disabled>Deal Type</option>
                                        <option value="3">Cash Receive</option>
                                        <option value="4">Cash Payment</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="transaction_registration_date" class="col-md-4 control-label">Date</label>
                                <div class="col-md-8">
                                    <input type="text" id="transaction_registration_date" name="date" class="form-control date-picker" autocomplete="off" placeholder="Date">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="transaction_registration_amount" class="col-md-4 control-label">Amount</label>
                                <div class="col-md-8">
                                    <input type="number" min="0" id="transaction_registration_amount" name="amount" class="form-control" placeholder="Amount of Transaction">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="transaction_registration_description" class="col-md-2 control-label">Description</label>
                                <div class="col-md-10">
                                    <textarea name="description" id="transaction_registration_description" class="form-control" rows="3"></textarea>
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
        function showTransactionRegisterModal() {
            $("#transactionRegisterModal").modal('show');
        }

        $.fn.select2.defaults.set("theme", "bootstrap");
        $("#transaction_registration_customer_id").select2({
            width: null,
            ajax: {
                url: "{{ route('customer-search') }}",
                dataType: 'json',
                delay: 150,
                data: function (params) {
                    return {
                        term: params.term || '',
                        page: params.page || 1
                    };
                }
            }
        });

        $("#transaction_registration_customer_id").change(function () {
            let url = "{{ route('customer-get-balance', 0) }}";
            url = url.replace('0', this.value);
            $.ajax({
                type: 'GET',
                url: url,
                success: function (response, status, xhr, $form) {
                    $("#transaction_registration_balance").val(response['balance']);
                },
                error: function (xhr, status, error) {
                    $('[type="submit"]').removeAttr('disabled');
                    let data = JSON.parse(xhr.responseText);
                    let errors = '';
                    $.each(data.errors, function(index, error) {
                        errors += error + '<br/>';
                    });
                    displayToastr(null, errors, 'error', null);
                }
            });
        });
    </script>
@endsection
