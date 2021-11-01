<div id="saleQuotationModal" data-target="" class="modal fade modal-scroll bs-modal-lg" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal submit_form_response" role="form" method="post" action="{{ route('quotation-sell') }}">
                @csrf
                <input type="hidden" name="invoice_id" id="sell_quotation_invoice_id">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Sell Quotation Items</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sale_quotation_customer_id" class="col-md-4 control-label">Customer</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="sale_quotation_customer" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sale_quotation_date" class="col-md-4 control-label">Date</label>
                                <div class="col-md-8">
                                    <input type="text" id="sale_quotation_date" name="date" class="form-control date-picker" autocomplete="off" placeholder="Invoice Date">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sale_quotation_order_number" class="col-md-4 control-label">Order Number</label>
                                <div class="col-md-8">
                                    <input type="text" id="sale_quotation_order_number" name="order_number" class="form-control" placeholder="Order Number">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sale_quotation_order_date" class="col-md-4 control-label">Order Date</label>
                                <div class="col-md-8">
                                    <input type="text" id="sale_quotation_order_date" name="order_date" class="form-control date-picker" autocomplete="off" placeholder="Order Date">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                                </thead>
                                <tbody id="sell_quotation_table_body"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sale_quotation_payment_type_id" class="col-md-4 control-label">Payment Type</label>
                                <div class="col-md-8">
                                    <select id="sale_quotation_payment_type_id" name="payment_type_id" class="form-control">
                                        <option disabled selected>Please select the payment type</option>
                                        @foreach(\App\PaymentType::all() as $paymentType)
                                            <option value="{{ $paymentType->id }}">{{ $paymentType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 hidden" id="div-amount_paid">
                            <div class="form-group">
                                <label for="amount_paid" class="col-md-4 control-label">Amount Paid</label>
                                <div class="col-md-8">
                                    <input type="number" min="0" id="amount_paid" name="amount_paid" class="form-control" placeholder="Amount Paid">
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
        function showSaleQuotationModal(quotationId) {
            $.ajax({
                type: 'POST',
                url: '{{ route('quotation-details-for-sale') }}',
                data: { quotation_id: quotationId },
                success: function (response, status, xhr, $form) {
                    let items = '', total = 0;
                    $("#sell_quotation_invoice_id").val(response['invoice_id']);
                    $("#sale_quotation_customer").val(response['customer_name'] + ' (' + response['customer_phone'] + ')');
                    $.each(response['items'], function (index, value) {
                        total += value['quantity'] * value['price'];
                        items += '<tr>' +
                            '<td>' + value['product_name'] +'</td>' +
                            '<td>' + value['quantity'] + '</td>' +
                            '<td>' + value['price'] + '</td>' +
                            '<td>' + (value['quantity'] * value['price']) + '</td>' +
                            '</tr>';
                    });
                    $("#sell_quotation_table_body").html(items);
                    $("#amount_paid").attr('max', total)
                },
                error: function (xhr, status, error) {
                    $('[type="submit"]').removeAttr('disabled');
                    ajaxErrorHandler(JSON.parse(xhr.responseText));
                }
            });
            $("#saleQuotationModal").modal('show');
        }

        $("#sale_quotation_payment_type_id").change(function () {
            if (this.value == 3) {
                $("#div-amount_paid").removeClass('hidden');
            }
            else {
                $("#div-amount_paid").addClass('hidden');
            }
        });
    </script>
@endsection
