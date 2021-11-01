<div id="newPurchaseModal" data-target="" class="modal fade bs-modal-lg" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal submit_form_response" role="form" method="post" action="{{ route('purchases-register-submit') }}">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="modal-title">Purchase Product</h4>
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
                                <label for="purchase_registration_customer_id" class="col-md-4 control-label">Select Vendor</label>
                                <div class="col-md-8">
                                    <select id="purchase_registration_customer_id" name="customer_id" class="form-control select2">
                                        <option selected disabled>Please select the vendor</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purchase_registration_reference" class="col-md-4 control-label">Reference</label>
                                <div class="col-md-8">
                                    <input type="text" id="purchase_registration_reference" name="reference" class="form-control" placeholder="Reference">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purchase_registration_date" class="col-md-4 control-label">Date</label>
                                <div class="col-md-8">
                                    <input type="text" id="purchase_registration_date" name="date" class="form-control date-picker" autocomplete="off" placeholder="Date">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div id="purchase_product_selection">
                            <div data-repeater-list="group">
                                <div data-repeater-item class="row margin-bottom-10">
                                    <div class="col-md-11">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="purchase_registration_product_id" class="col-md-4 control-label">Select Product</label>
                                                    <div class="col-md-8">
                                                        <select name="product_id" id="purchase_registration_product_id" class="form-control select2 purchase_products">
                                                            <option selected disabled>Please select the product</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="purchase_registration_quantity" class="col-md-4 control-label">Quantity</label>
                                                    <div class="col-md-8">
                                                        <input type="number" id="purchase_registration_quantity" name="quantity" class="form-control purchase_quantities" placeholder="Quantity">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="purchase_registration_net_price" class="col-md-4 control-label">Net Price</label>
                                                    <div class="col-md-8">
                                                        <input type="number" step="0.01" id="purchase_registration_net_price" name="net_price" class="form-control purchase_net_prices" placeholder="Net Price">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="purchase_registration_sale_price" class="col-md-4 control-label">Sale Price</label>
                                                    <div class="col-md-8">
                                                        <input type="number" step="0.01" id="purchase_registration_sale_price" name="sale_price" class="form-control purchase_sale_prices" placeholder="Sale Price">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <a href="javascript:;" data-repeater-delete class="btn btn-danger" style="margin-left: -15px;">
                                            <i class="fa fa-close"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <button type="button" data-repeater-create class="btn btn-info mt-repeater-add" style="margin-left: 20px;">
                                <i class="fa fa-plus"></i> Add Product
                            </button>
                        </div>
                    </div>
                    <div class="row" id="purchase_registration_payment_type_div" hidden>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purchase_registration_payment_type_id" class="col-md-4 control-label">Payment Type</label>
                                <div class="col-md-8">
                                    <select id="purchase_registration_payment_type_id" name="payment_type_id" class="form-control">
                                        <option disabled selected>Please select the payment type</option>
                                        @foreach(\App\PaymentType::all() as $paymentType)
                                            <option value="{{ $paymentType->id }}">{{ $paymentType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 hidden" id="purchase_registration_div-amount_paid">
                            <div class="form-group">
                                <label for="purchase_registration_amount_paid" class="col-md-4 control-label">Amount Paid</label>
                                <div class="col-md-8">
                                    <input type="number" min="0" id="purchase_registration_amount_paid" name="amount_paid" class="form-control" placeholder="Amount Paid">
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
@include('customers.register')
@include('products.register')
@section('extraJS')
    @parent
    <script>
        function showProductPurchaseModal() {
            $("#newPurchaseModal").modal('show');
            purchaseProductsInit();
        }

        $("#purchase_registration_payment_type_id").change(function () {
            if (this.value == 3) {
                $("#purchase_registration_div-amount_paid").removeClass('hidden');
            }
            else {
                $("#purchase_registration_div-amount_paid").addClass('hidden');
            }
        });

        $("#purchase_registration_customer_id").change(function () {
            if (this.value == -1) {
                $("#purchase_registration_payment_type_div").hide();
            }
            else {
                $("#purchase_registration_payment_type_div").show();
            }
        });

        $.fn.select2.defaults.set("theme", "bootstrap");
        $("#purchase_registration_customer_id").select2({
            width: null,
            ajax: {
                url: "{{ route('customer-search') }}",
                dataType: 'json',
                delay: 150,
                data: function (params) {
                    return {
                        term: params.term || '',
                        page: params.page || 1,
                        unknown_customer: true
                    };
                }
            }
        });

        function purchaseProductsInit() {
            $(".purchase_products").off('change');
            $(".purchase_products").on('select2:select', function (e) {
                purchaseProductsInit();
            });
            let productIds = $(".purchase_products").map(function() {
                if($(this).val() !== "")
                    return $(this).val();
            }).get();
            $(".purchase_products").select2({
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

        $("#purchase_product_selection").repeater({
            show: function () {
                $(this).slideDown();
                $(this).find('.select2-container').remove();
                $(this).find('.purchase_products').html('<option selected="selected">Please select the product</option>');
                purchaseProductsInit();
            },
            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            },
            isFirstItemUndeletable: true
        });
    </script>
@endsection
