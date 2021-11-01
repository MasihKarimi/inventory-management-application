<div id="editPurchaseModal" data-target="" class="modal fade modal-scroll bs-modal-lg" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal submit_form_response" role="form" method="post" action="{{ route('purchase-edit-submit') }}">
                @csrf
                <input type="hidden" name="purchase_id" id="purchase_edit_purchase_id">
                <input type="hidden" name="purchaseData" id="purchase_edit_purchase_data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Edit Purchase #: <span id="purchase_edit_purchase_number"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purchase_edit_customer_id" class="col-md-4 control-label">Select Vendor</label>
                                <div class="col-md-8">
                                    <select id="purchase_edit_customer_id" name="customer_id" class="form-control select2"></select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purchase_edit_reference" class="col-md-4 control-label">Reference</label>
                                <div class="col-md-8">
                                    <input type="text" id="purchase_edit_reference" name="reference" class="form-control" placeholder="Reference">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purchase_edit_date" class="col-md-4 control-label">Date</label>
                                <div class="col-md-8">
                                    <input type="text" id="purchase_edit_date" name="date" class="form-control date-picker" autocomplete="off" placeholder="Date">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="product_id" class="col-md-2 control-label">Product</label>
                                <div class="col-md-10">
                                    <div id="purchase_edit_repeater">
                                        <div data-repeater-list="group-b" id="purchase_edit_product_selection">
                                            <div data-repeater-item class="row margin-bottom-10">
                                                <div class="col-md-11">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <input type="hidden" name="purchase_item_id[]" class="purchase_edit_purchase_item_ids">
                                                            <select name="product_id[]" class="form-control select2 purchase_edit_products"></select>
                                                        </div>
                                                        <div class="col-md-6" style="margin-right: -13px;">
                                                            <input type="number" name="quantity[]" placeholder="Quantity" class="form-control purchase_edit_quantities" />
                                                        </div>
                                                    </div>
                                                    <div class="row margin-top-10">
                                                        <div class="col-md-6">
                                                            <input type="number" step="0.01" name="net_price[]" placeholder="Net Price" class="form-control purchase_edit_net_prices" />
                                                        </div>
                                                        <div class="col-md-6" style="margin-right: -13px;">
                                                            <input type="number" step="0.01" name="sale_price[]" placeholder="Sale Price" class="form-control purchase_edit_sale_prices" />
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
                                        <button type="button" data-repeater-create class="btn btn-info mt-repeater-add" id="purchase_edit_repeater_button">
                                            <i class="fa fa-plus"></i> Add Product
                                        </button>
                                        <button type="button" class="btn btn-info hidden" onclick="undoEditPay()" id="purchase_edit_edit_product_button">
                                            <i class="fa fa-edit"></i> Edit Products
                                        </button> &nbsp;&nbsp;
                                        <button type="button" class="btn btn-success" onclick="payEditPurchase()" id="purchase_edit_pay_button">
                                            <i class="fa fa-money"></i> Pay
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purchase_edit_payment_type_id" class="col-md-4 control-label">Payment Type</label>
                                <div class="col-md-8">
                                    <select id="purchase_edit_payment_type_id" name="payment_type_id" class="form-control">
                                        <option disabled selected>Please select the payment type</option>
                                        @foreach(\App\PaymentType::all() as $paymentType)
                                            <option value="{{ $paymentType->id }}">{{ $paymentType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 hidden" id="purchase_edit_div-amount_paid">
                            <div class="form-group">
                                <label for="purchase_edit_amount_paid" class="col-md-4 control-label">Amount Paid</label>
                                <div class="col-md-8">
                                    <input type="number" min="0" step="0.01" id="purchase_edit_amount_paid" name="amount_paid" class="form-control" placeholder="Amount Paid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn green hidden" id="purchase_edit_submit_button">Save</button>
                    <button type="reset" data-dismiss="modal" class="btn default">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@section('extraJS')
    @parent
    <script>
        function showEditPurchaseModal(purchaseId) {
            $.ajax({
                type: 'POST',
                url: '{{ route('purchase-details') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { purchase_id: purchaseId },
                success: function (response, status, xhr, $form) {
                    $("#editPurchaseModal").modal('show');
                    $("[data-repeater-item]").not("[data-repeater-item]:first").remove();
                    $("#purchase_edit_purchase_number").html(response['id']);
                    $("#purchase_edit_purchase_id").val(response['id']);
                    $("#purchase_edit_customer_id").html('<option selected value="' + response['customer_id'] + '">' + response['customer_name'] + '</option>');
                    $("#purchase_edit_reference").val(response['reference']);
                    $("#purchase_edit_date").val(response['date']);
                    $("#purchase_edit_payment_type_id").val(response['payment_type_id']).trigger('change');
                    if (response['payment_type_id'] == 3)
                        $("#purchase_edit_amount_paid").val(response['amount_paid']);
                    $.each(response['items'], function (index, value) {
                        $("#purchase_edit_repeater_button").click();
                        $(".purchase_edit_purchase_item_ids").eq(index).val(value['id']);
                        $(".purchase_edit_products").eq(index).html("<option selected value='" + value['product_id'] + "'>" + value['product_name'] + "</option>");
                        $(".purchase_edit_quantities").eq(index).val(value['quantity']);
                        $(".purchase_edit_net_prices").eq(index).val(value['net_price']);
                        $(".purchase_edit_sale_prices").eq(index).val(value['sale_price']);
                    });
                    editPurchaseProductsInit();
                    payEditPurchase();
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

        function payEditPurchase() {
            if (editValidation()) {
                let scope = $("#purchase_edit_product_selection");
                scope.find('input').attr('disabled', 'disabled');
                scope.find('select').attr('disabled', 'disabled');
                $("#purchase_edit_repeater_button").addClass('hidden');
                $("#purchase_edit_edit_product_button").removeClass('hidden');
                $("#purchase_edit_div-pay").removeClass('hidden');
                $("#purchase_edit_pay_button").addClass('hidden');
                $("#purchase_edit_submit_button").removeClass('hidden');
                $("a[data-repeater-delete]").hide();
            } else {
                displayToastr(null, 'Please enter all the product names, quantities and net_prices to proceed.', 'error', null)
            }
        }

        function undoEditPay() {
            let scope = $("#purchase_edit_product_selection");
            scope.find('input').removeAttr('disabled');
            scope.find('select').removeAttr('disabled');
            $("#purchase_edit_repeater_button").removeClass('hidden');
            $("#purchase_edit_edit_product_button").addClass('hidden');
            $("#purchase_edit_div-pay").addClass('hidden');
            $("#purchase_edit_pay_button").removeClass('hidden');
            $("#purchase_edit_submit_button").addClass('hidden');
            $("a[data-repeater-delete]").show();
        }

        let editPurchaseData = {};
        function editValidation() {
            let productIds = $(".purchase_edit_products").map(function() {
                if($(this).val() !== "")
                    return $(this).val();
            }).get();
            let quantities = $(".purchase_edit_quantities").map(function() {
                if($(this).val() !== "")
                    return $(this).val();
            }).get();
            let net_prices = $(".purchase_edit_net_prices").map(function() {
                if($(this).val() !== "")
                    return $(this).val();
            }).get();
            let sale_prices = $(".purchase_edit_sale_prices").map(function() {
                return $(this).val();
            }).get();
            let purchaseItemIds = $(".purchase_edit_purchase_item_ids").map(function () {
                return $(this).val();
            }).get();
            editPurchaseData['productIds'] = productIds;
            editPurchaseData['quantities'] = quantities;
            editPurchaseData['net_prices'] = net_prices;
            editPurchaseData['sale_prices'] = sale_prices;
            editPurchaseData['purchaseItemIds'] = purchaseItemIds;
            $("#purchase_edit_purchase_data").val(JSON.stringify(editPurchaseData));
            return productIds.length == quantities.length && quantities.length == net_prices.length && productIds.length != 0;
        }

        function editPurchaseProductsInit() {
            let products = $(".purchase_edit_products");
            let productIds = products.map(function() {
                if($(this).val() !== "")
                    return $(this).val();
            }).get();
            products.select2({
                width: null,
                ajax: {
                    url: "{{ route('product-search') }}",
                    dataType: 'json',
                    delay: 150,
                    data: function (params) {
                        return {
                            term: params.term || '',
                            page: params.page || 1,
                            excludeIds: productIds
                        };
                    }
                }
            });
        }

        $("#purchase_edit_payment_type_id").change(function () {
            if (this.value == 3) {
                $("#purchase_edit_div-amount_paid").removeClass('hidden');
            }
            else {
                $("#purchase_edit_div-amount_paid").addClass('hidden');
            }
        });

        $("#purchase_edit_repeater").repeater({
            show: function () {
                $(this).slideDown();
                $(this).find('.select2-container').remove();
                $(this).find('.purchase_edit_products').html('<option selected="selected">Please select the product</option>');
                editPurchaseProductsInit();
            },
            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            },
            isFirstItemUndeletable: true
        });

        $.fn.select2.defaults.set("theme", "bootstrap");
        $("#purchase_edit_customer_id").select2({
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
