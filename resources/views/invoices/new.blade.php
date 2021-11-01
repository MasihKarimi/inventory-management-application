<div id="newInvoiceModal" data-target="" class="modal fade modal-scroll bs-modal-lg" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal submit_form_response" role="form" method="post" action="{{ route('invoices-new-submit') }}">
                @csrf
                <input type="hidden" name="invoiceData" id="invoiceData">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="modal-title">Add New Invoice/Sale</h4>
                        </div>
                        <div class="col-md-5 pull-right">
                            <button type="button" onclick="showCustomerRegisterModal()" class="btn btn-xs btn-primary">
                                <i class="fa fa-plus"></i> Register New Customer</button>
                            <button type="button" onclick="showProductRegisterModal()" class="btn btn-xs btn-info">
                                <i class="fa fa-cart-plus"></i> Register Product</button>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="invoice_registration_customer_id" class="col-md-4 control-label">Select Customer</label>
                                <div class="col-md-8">
                                    <select id="invoice_registration_customer_id" name="customer_id" class="form-control select2">
                                        <option selected disabled>Please select the customer</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="invoice_registration_date" class="col-md-4 control-label">Date</label>
                                <div class="col-md-8">
                                    <input type="text" id="invoice_registration_date" name="date" class="form-control date-picker" autocomplete="off" placeholder="Invoice Date">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row hidden" id="one_time_customer">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="invoice_registration_customer_name" class="col-md-4 control-label">Customer Name</label>
                                <div class="col-md-8">
                                    <input type="text" id="invoice_registration_customer_name" name="customer_name" class="form-control" placeholder="Customer Name">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="invoice_registration_customer_phone" class="col-md-4 control-label">Customer Phone</label>
                                <div class="col-md-8">
                                    <input type="text" id="invoice_registration_customer_phone" name="customer_phone" class="form-control" placeholder="Customer Phone">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="invoice_registration_order_number" class="col-md-4 control-label">Order Number</label>
                                <div class="col-md-8">
                                    <input type="text" id="invoice_registration_order_number" name="order_number" class="form-control" placeholder="Order Number">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="invoice_registration_order_date" class="col-md-4 control-label">Order Date</label>
                                <div class="col-md-8">
                                    <input type="text" id="invoice_registration_order_date" name="order_date" class="form-control date-picker" autocomplete="off" placeholder="Order Date">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="invoice_registration_currency" class="col-md-4 control-label">Currency</label>
                                <div class="col-md-8">
                                    <input type="text" id="invoice_registration_currency" name="currency" class="form-control" placeholder="Currency">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="product_id" class="col-md-2 control-label">Product</label>
                                <div class="col-md-10">
                                    <div class="mt-repeater">
                                        <div data-repeater-list="group-b" id="product_selection">
                                            <div data-repeater-item class="row margin-bottom-10">
                                                <div class="col-md-11">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <select name="product_id[]" class="form-control select2 products">
                                                                <option  disabled selected>Please select the product</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="number" name="quantity[]" placeholder="Quantity" disabled class="form-control quantities" />
                                                        </div>
                                                        <div class="col-md-3" style="margin-right: -13px;">
                                                            <input type="number" step="0.01" name="price[]" placeholder="Price" disabled class="form-control prices" />
                                                        </div>
                                                    </div>
                                                    <div class="row margin-top-10">
                                                        <div class="col-md-12">
                                                            <input type="text" name="remark[]" placeholder="Remark" class="form-control remarks" />
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
                                        <button type="button" data-repeater-create class="btn btn-info mt-repeater-add" id="repeater_button">
                                            <i class="fa fa-plus"></i> Add Product
                                        </button>
                                        <button type="button" class="btn btn-info hidden" onclick="undoPay()" id="edit_product_button">
                                            <i class="fa fa-edit"></i> Edit Products
                                        </button> &nbsp;&nbsp;
                                        <button type="button" class="btn btn-success" onclick="payInvoice()" id="pay_button">
                                            <i class="fa fa-money"></i> Pay
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row margin-bottom-20" id="div-log">
                        <div class="col-md-12">
                            <textarea id="log" disabled rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row hidden" id="div-pay">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="invoice_registration_payment_type_id" class="col-md-4 control-label">Payment Type</label>
                                <div class="col-md-8">
                                    <select id="invoice_registration_payment_type_id" name="payment_type_id" class="form-control">
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
                    <button type="submit" class="btn green hidden" id="invoice_registration_submit_button">Add</button>
                    <button type="reset" data-dismiss="modal" class="btn default">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('customers.register')
@include('products.register')
@section('extraJS')
    @parent
    <script>
        function showNewInvoiceModal() {
            $("#newInvoiceModal").modal('show');
        }

        function payInvoice() {
            if (validation()) {
                let scope = $("#product_selection");
                scope.find('input').attr('disabled', 'disabled');
                scope.find('select').attr('disabled', 'disabled');
                $("#repeater_button").addClass('hidden');
                $("#edit_product_button").removeClass('hidden');
                $("#div-log").addClass('hidden');
                $("#div-pay").removeClass('hidden');
                $("#pay_button").addClass('hidden');
                $("#invoice_registration_submit_button").removeClass('hidden');
            } else {
                displayToastr(null, 'Please enter all the product names, quantities and prices to proceed.', 'error', null)
            }
        }

        function undoPay() {
            let scope = $("#product_selection");
            scope.find('input').removeAttr('disabled');
            scope.find('select').removeAttr('disabled');
            $("#repeater_button").removeClass('hidden');
            $("#edit_product_button").addClass('hidden');
            $("#div-log").removeClass('hidden');
            $("#div-pay").addClass('hidden');
            $("#pay_button").removeClass('hidden');
            $("#invoice_registration_submit_button").addClass('hidden');
            $("#payment_type_id").prop('selectedIndex', 0);
            $("#amount_paid").val('')
        }

        let invoiceData = {};
        function validation() {
            let productIds = $(".products").map(function() {
                if($(this).val() !== "")
                    return $(this).val();
            }).get();
            let quantities = $(".quantities").map(function() {
                if($(this).val() !== "")
                    return $(this).val();
            }).get();
            let prices = $(".prices").map(function() {
                if($(this).val() !== "")
                    return $(this).val();
            }).get();
            let remarks = $(".remarks").map(function() {
                return $(this).val();
            }).get();
            invoiceData['productIds'] = productIds;
            invoiceData['quantities'] = quantities;
            invoiceData['prices'] = prices;
            invoiceData['remarks'] = remarks;
            $("#invoiceData").val(JSON.stringify(invoiceData));
            return productIds.length == quantities.length && quantities.length == prices.length && productIds.length != 0;
        }

        $("#invoice_registration_payment_type_id").change(function () {
            if (this.value == 3) {
                $("#div-amount_paid").removeClass('hidden');
            }
            else {
                $("#div-amount_paid").addClass('hidden');
            }
        });

        $("#invoice_registration_customer_id").change(function () {
            if (this.value == -1)
                $("#one_time_customer").removeClass('hidden');
            else
                $("#one_time_customer").addClass('hidden');
        });

        $.fn.select2.defaults.set("theme", "bootstrap");
        $("#invoice_registration_customer_id").select2({
            width: null,
            ajax: {
                url: "{{ route('customer-search') }}",
                dataType: 'json',
                delay: 150,
                data: function (params) {
                    return {
                        term: params.term || '',
                        page: params.page || 1,
                        one_time_customer: true
                    };
                }
            }
        });

        function productsInit() {
            $(".products").off('change');
            $(".products").change(function () {
                let productInput = $(this);
                let url = "{{ route('products-get-details', 0) }}";
                url = url.replace('0', this.value);
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function (response, status, xhr, $form) {
                        productInput.parent().parent().parent().find('input[disabled]').removeAttr('disabled');
                        let logText = $('#log');
                        logText.append(response['name'] + ' '
                            + ' Net Price: '
                            + response['net_price']
                            + ' Sale Price: '
                            + response['sale_price'] + ' Stock: '
                            + response['quantity'] + '\n');
                        logText.scrollTop(logText[0].scrollHeight);
                        productsInit();
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

            $(".products").select2({
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
        productsInit();

        $('.mt-repeater').each(function(){
            $(this).repeater({
                show: function () {
                    $(this).slideDown();
                    $(this).find('.select2-container').remove();
                    $(this).find('.products').html('<option selected="selected">Please select the product</option>');
                    productsInit();
                },
                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                },
                isFirstItemUndeletable: true
            });
        });
    </script>
@endsection
