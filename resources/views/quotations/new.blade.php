<div id="newQuotationModal" data-target="" class="modal fade modal-scroll bs-modal-lg" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal submit_form_response" role="form" method="post" action="{{ route('quotations-new-submit') }}">
                @csrf
                <input type="hidden" name="quotationData" id="quotationData">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="modal-title">Add New Quotation</h4>
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
                                <label for="quotation_registration_customer_id" class="col-md-4 control-label">Select Customer</label>
                                <div class="col-md-8">
                                    <select id="quotation_registration_customer_id" name="customer_id" class="form-control select2">
                                        <option selected disabled>Please select the customer</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quotation_registration_date" class="col-md-4 control-label">Date</label>
                                <div class="col-md-8">
                                    <input type="text" id="quotation_registration_date" name="date" class="form-control date-picker" autocomplete="off" placeholder="Quotation Date">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quotation_registration_rfq_number" class="col-md-4 control-label">RFQ Number</label>
                                <div class="col-md-8">
                                    <input type="text" id="quotation_registration_rfq_number" name="rfq_number" class="form-control" autocomplete="off" placeholder="RFQ Number">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quotation_registration_currency" class="col-md-4 control-label">Currency</label>
                                <div class="col-md-8">
                                    <input type="text" id="quotation_registration_currency" name="currency" class="form-control" placeholder="Currency">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row hidden" id="one_time_customer">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quotation_registration_customer_name" class="col-md-4 control-label">Customer Name</label>
                                <div class="col-md-8">
                                    <input type="text" id="quotation_registration_customer_name" name="customer_name" class="form-control" placeholder="Customer Name">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quotation_registration_customer_phone" class="col-md-4 control-label">Customer Phone</label>
                                <div class="col-md-8">
                                    <input type="text" id="quotation_registration_customer_phone" name="customer_phone" class="form-control" placeholder="Customer Phone">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn green" id="quotation_form_add">Add</button>
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
        function showNewQuotationModal() {
            $("#newQuotationModal").modal('show');
        }

        $("#quotation_form_add").click(function () {
            if (validation()) {
                $(this).submit();
            } else {
                displayToastr(null, 'Please enter all the product names, quantities and prices to proceed.', 'error', null)
            }
        });

        let quotationData = {};
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
            quotationData['productIds'] = productIds;
            quotationData['quantities'] = quantities;
            quotationData['prices'] = prices;
            quotationData['remarks'] = remarks;
            $("#quotationData").val(JSON.stringify(quotationData));
            return productIds.length == quantities.length && quantities.length == prices.length && productIds.length != 0;
        }

        $("#quotation_registration_customer_id").change(function () {
            if (this.value == -1)
                $("#one_time_customer").removeClass('hidden');
            else
                $("#one_time_customer").addClass('hidden');
        });

        $.fn.select2.defaults.set("theme", "bootstrap");
        $("#quotation_registration_customer_id").select2({
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

            let productIds = $(".products").map(function() {
                if($(this).val() !== "")
                    return $(this).val();
            }).get();
            $(".products").select2({
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
