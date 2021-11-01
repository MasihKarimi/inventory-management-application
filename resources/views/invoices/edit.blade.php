<div id="editInvoiceModal" data-target="" class="modal fade modal-scroll bs-modal-lg" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal submit_form_response" role="form" method="post" action="{{ route('invoices-edit') }}">
                @csrf
                <input type="hidden" name="invoice_id" id="invoice_edit_invoice_id">
                <input type="hidden" name="invoiceData" id="invoice_edit_invoice_data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Edit Invoice #: <span id="invoice_edit_invoice_number"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="invoice_edit_customer_id" class="col-md-4 control-label">Select Customer</label>
                                <div class="col-md-8">
                                    <select id="invoice_edit_customer_id" name="customer_id" class="form-control select2"></select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="invoice_edit_date" class="col-md-4 control-label">Date</label>
                                <div class="col-md-8">
                                    <input type="text" id="invoice_edit_date" name="date" class="form-control date-picker" autocomplete="off" placeholder="Invoice Date">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="invoice_edit_order_number" class="col-md-4 control-label">Order Number</label>
                                <div class="col-md-8">
                                    <input type="text" id="invoice_edit_order_number" name="order_number" class="form-control" placeholder="Order Number">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="invoice_edit_order_date" class="col-md-4 control-label">Order Date</label>
                                <div class="col-md-8">
                                    <input type="text" id="invoice_edit_order_date" name="order_date" class="form-control date-picker" autocomplete="off" placeholder="Order Date">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="invoice_edit_currency" class="col-md-4 control-label">Currency</label>
                                <div class="col-md-8">
                                    <input type="text" id="invoice_edit_currency" name="currency" class="form-control" placeholder="Currency">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="product_id" class="col-md-2 control-label">Product</label>
                                <div class="col-md-10">
                                    <div id="invoice_edit_repeater">
                                        <div data-repeater-list="group-b" id="invoice_edit_product_selection">
                                            <div data-repeater-item class="row margin-bottom-10">
                                                <div class="col-md-11">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <input type="hidden" name="invoice_item_id[]" class="invoice_edit_invoice_item_ids">
                                                            <select name="product_id[]" class="form-control select2 invoice_edit_products"></select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="number" name="quantity[]" placeholder="Quantity" class="form-control invoice_edit_quantities" />
                                                        </div>
                                                        <div class="col-md-3" style="margin-right: -13px;">
                                                            <input type="number" step="0.01" name="price[]" placeholder="Price" class="form-control invoice_edit_prices" />
                                                        </div>
                                                    </div>
                                                    <div class="row margin-top-10">
                                                        <div class="col-md-12">
                                                            <input type="text" name="remark[]" placeholder="Remark" class="form-control invoice_edit_remarks" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-1" style="margin-left: -15px;">
                                                    <a href="javascript:;" data-repeater-delete class="btn btn-danger">
                                                        <i class="fa fa-close"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" data-repeater-create class="btn btn-info mt-repeater-add" id="invoice_edit_repeater_button">
                                            <i class="fa fa-plus"></i> Add Product
                                        </button>
                                        <button type="button" class="btn btn-info hidden" onclick="undoEditPay()" id="invoice_edit_edit_product_button">
                                            <i class="fa fa-edit"></i> Edit Products
                                        </button> &nbsp;&nbsp;
                                        <button type="button" class="btn btn-success" onclick="payEditInvoice()" id="invoice_edit_pay_button">
                                            <i class="fa fa-money"></i> Pay
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row margin-bottom-20" id="invoice_edit_div-log">
                        <div class="col-md-12">
                            <textarea id="invoice_edit_log" disabled rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="invoice_edit_payment_type_id" class="col-md-4 control-label">Payment Type</label>
                                <div class="col-md-8">
                                    <select id="invoice_edit_payment_type_id" name="payment_type_id" class="form-control">
                                        <option disabled selected>Please select the payment type</option>
                                        @foreach(\App\PaymentType::all() as $paymentType)
                                            <option value="{{ $paymentType->id }}">{{ $paymentType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 hidden" id="invoice_edit_div-amount_paid">
                            <div class="form-group">
                                <label for="invoice_edit_amount_paid" class="col-md-4 control-label">Amount Paid</label>
                                <div class="col-md-8">
                                    <input type="number" min="0" step="0.01" id="invoice_edit_amount_paid" name="amount_paid" class="form-control" placeholder="Amount Paid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn green hidden" id="invoice_edit_submit_button">Save</button>
                    <button type="reset" data-dismiss="modal" class="btn default">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@section('extraJS')
    @parent
    <script>
        function showEditInvoiceModal(invoiceId) {
            $.ajax({
                type: 'POST',
                url: '{{ route('invoice-details') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { invoice_id: invoiceId },
                success: function (response, status, xhr, $form) {
                    $("#editInvoiceModal").modal('show');
                    $("[data-repeater-item]").not("[data-repeater-item]:first").remove();
                    $("#invoice_edit_invoice_number").html(response['id']);
                    $("#invoice_edit_invoice_id").val(response['id']);
                    $("#invoice_edit_customer_id").html('<option selected value="' + response['customer_id'] + '">' + response['customer_name'] + '</option>');
                    $("#invoice_edit_date").val(response['date']);
                    $("#invoice_edit_order_date").val(response['order_date']);
                    $("#invoice_edit_order_number").val(response['order_number']);
                    $("#invoice_edit_currency").val(response['currency']);
                    $("#invoice_edit_payment_type_id").val(response['payment_type_id']).trigger('change');
                    if (response['payment_type_id'] == 3)
                        $("#invoice_edit_amount_paid").val(response['amount_paid']);
                    $.each(response['items'], function (index, value) {
                        $("#invoice_edit_repeater_button").click();
                        $(".invoice_edit_invoice_item_ids").eq(index).val(value['id']);
                        $(".invoice_edit_products").eq(index).html("<option selected value='" + value['product_id'] + "'>" + value['product_name'] + "</option>");
                        $(".invoice_edit_quantities").eq(index).val(value['quantity']);
                        $(".invoice_edit_prices").eq(index).val(value['price']);
                        $(".invoice_edit_remarks").eq(index).val(value['remark']);
                    });
                    editInvoiceProductsInit();
                    payEditInvoice();
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
        }

        function payEditInvoice() {
            if (editValidation()) {
                let scope = $("#invoice_edit_product_selection");
                scope.find('input').attr('disabled', 'disabled');
                scope.find('select').attr('disabled', 'disabled');
                $("#invoice_edit_repeater_button").addClass('hidden');
                $("#invoice_edit_edit_product_button").removeClass('hidden');
                $("#invoice_edit_div-log").addClass('hidden');
                $("#invoice_edit_div-pay").removeClass('hidden');
                $("#invoice_edit_pay_button").addClass('hidden');
                $("#invoice_edit_submit_button").removeClass('hidden');
                $("a[data-repeater-delete]").hide();
            } else {
                displayToastr(null, 'Please enter all the product names, quantities and prices to proceed.', 'error', null)
            }
        }

        function undoEditPay() {
            let scope = $("#invoice_edit_product_selection");
            scope.find('input').removeAttr('disabled');
            scope.find('select').removeAttr('disabled');
            $("#invoice_edit_repeater_button").removeClass('hidden');
            $("#invoice_edit_edit_product_button").addClass('hidden');
            $("#invoice_edit_div-log").removeClass('hidden');
            $("#invoice_edit_div-pay").addClass('hidden');
            $("#invoice_edit_pay_button").removeClass('hidden');
            $("#invoice_edit_submit_button").addClass('hidden');
            $("a[data-repeater-delete]").show();
        }

        let editInvoiceData = {};
        function editValidation() {
            let productIds = $(".invoice_edit_products").map(function() {
                if($(this).val() !== "")
                    return $(this).val();
            }).get();
            let quantities = $(".invoice_edit_quantities").map(function() {
                if($(this).val() !== "")
                    return $(this).val();
            }).get();
            let prices = $(".invoice_edit_prices").map(function() {
                if($(this).val() !== "")
                    return $(this).val();
            }).get();
            let remarks = $(".invoice_edit_remarks").map(function() {
                return $(this).val();
            }).get();
            let invoiceItemIds = $(".invoice_edit_invoice_item_ids").map(function () {
                return $(this).val();
            }).get();
            editInvoiceData['productIds'] = productIds;
            editInvoiceData['quantities'] = quantities;
            editInvoiceData['prices'] = prices;
            editInvoiceData['remarks'] = remarks;
            editInvoiceData['invoiceItemIds'] = invoiceItemIds;
            $("#invoice_edit_invoice_data").val(JSON.stringify(editInvoiceData));
            return productIds.length == quantities.length && quantities.length == prices.length && productIds.length != 0;
        }

        function editInvoiceProductsInit() {
            let products = $(".invoice_edit_products");
            products.off('change');
            products.change(function () {
                let productInput = $(this);
                let url = "{{ route('products-get-details', 0) }}";
                url = url.replace('0', this.value);
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function (response, status, xhr, $form) {
                        productInput.parent().parent().parent().find('input[disabled]').removeAttr('disabled');
                        let logText = $('#invoice_edit_log');
                        logText.append(response['name'] + ' '
                            + ' Net Price: '
                            + response['net_price']
                            + ' Sale Price: '
                            + response['sale_price'] + ' Stock: '
                            + response['quantity'] + '\n');
                        logText.scrollTop(logText[0].scrollHeight);
                        editInvoiceProductsInit();
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

            products.select2({
                width: null,
                ajax: {
                    url: "{{ route('product-search') }}",
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
        }

        $("#invoice_edit_payment_type_id").change(function () {
            if (this.value == 3) {
                $("#invoice_edit_div-amount_paid").removeClass('hidden');
            }
            else {
                $("#invoice_edit_div-amount_paid").addClass('hidden');
            }
        });

        $("#invoice_edit_repeater").repeater({
            show: function () {
                $(this).slideDown();
                $(this).find('.select2-container').remove();
                $(this).find('.invoice_edit_products').html('<option selected="selected">Please select the product</option>');
                editInvoiceProductsInit();
            },
            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            },
            isFirstItemUndeletable: true
        });

        $.fn.select2.defaults.set("theme", "bootstrap");
        $("#invoice_edit_customer_id").select2({
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
    </script>
@endsection
