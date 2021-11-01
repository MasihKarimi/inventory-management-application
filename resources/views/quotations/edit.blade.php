<div id="editQuotationModal" data-target="" class="modal fade modal-scroll bs-modal-lg" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal submit_form_response" role="form" method="post" action="{{ route('quotations-edit') }}">
                @csrf
                <input type="hidden" name="invoice_id" id="quotation_edit_quotation_id">
                <input type="hidden" name="quotationData" id="quotation_edit_quotation_data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Edit Quotation #: <span id="quotation_edit_quotation_number"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quotation_edit_customer_id" class="col-md-4 control-label">Select Customer</label>
                                <div class="col-md-8">
                                    <select id="quotation_edit_customer_id" name="customer_id" class="form-control select2"></select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quotation_edit_date" class="col-md-4 control-label">Date</label>
                                <div class="col-md-8">
                                    <input type="text" id="quotation_edit_date" name="date" class="form-control date-picker" autocomplete="off" placeholder="Quotation Date">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quotation_edit_rfq_number" class="col-md-4 control-label">RFQ Number</label>
                                <div class="col-md-8">
                                    <input type="text" id="quotation_edit_rfq_number" name="rfq_number" class="form-control" autocomplete="off" placeholder="RFQ Number">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quotation_edit_currency" class="col-md-4 control-label">Currency</label>
                                <div class="col-md-8">
                                    <input type="text" id="quotation_edit_currency" name="currency" class="form-control" placeholder="Currency">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="product_id" class="col-md-2 control-label">Product</label>
                                <div class="col-md-10">
                                    <div id="quotation_edit_repeater">
                                        <div data-repeater-list="group-b" id="quotation_edit_product_selection">
                                            <div data-repeater-item class="row margin-bottom-10">
                                                <div class="col-md-11">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <input type="hidden" name="quotation_item_id[]" class="quotation_edit_quotation_item_ids">
                                                            <select name="product_id[]" class="form-control select2 quotation_edit_products"></select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="number" name="quantity[]" placeholder="Quantity" class="form-control quotation_edit_quantities" />
                                                        </div>
                                                        <div class="col-md-3" style="margin-right: -13px;">
                                                            <input type="number" step="0.01" name="price[]" placeholder="Price" class="form-control quotation_edit_prices" />
                                                        </div>
                                                    </div>
                                                    <div class="row margin-top-10">
                                                        <div class="col-md-12">
                                                            <input type="text" name="remark[]" placeholder="Remark" class="form-control quotation_edit_remarks" />
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
                                        <button type="button" data-repeater-create class="btn btn-info mt-repeater-add" id="quotation_edit_repeater_button">
                                            <i class="fa fa-plus"></i> Add Product
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row margin-bottom-20" id="quotation_edit_div-log">
                        <div class="col-md-12">
                            <textarea id="quotation_edit_log" disabled rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn green" id="quotation_edit_submit_button">Save</button>
                    <button type="reset" data-dismiss="modal" class="btn default">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@section('extraJS')
    @parent
    <script>
        function showEditQuotationModal(quotationId) {
            $.ajax({
                type: 'POST',
                url: '{{ route('quotation-details') }}',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { invoice_id: quotationId },
                success: function (response, status, xhr, $form) {
                    $("#editQuotationModal").modal('show');
                    $("[data-repeater-item]").not("[data-repeater-item]:first").remove();
                    $("#quotation_edit_quotation_number").html(response['quotation_number']);
                    $("#quotation_edit_rfq_number").val(response['rfq_number']);
                    $("#quotation_edit_quotation_id").val(response['id']);
                    $("#quotation_edit_customer_id").html('<option selected value="' + response['customer_id'] + '">' + response['customer_name'] + '</option>');
                    $("#quotation_edit_date").val(response['date']);
                    $("#quotation_edit_currency").val(response['currency']);
                    $.each(response['items'], function (index, value) {
                        $("#quotation_edit_repeater_button").click();
                        $(".quotation_edit_quotation_item_ids").eq(index).val(value['id']);
                        $(".quotation_edit_products").eq(index).html("<option selected value='" + value['product_id'] + "'>" + value['product_name'] + "</option>");
                        $(".quotation_edit_quantities").eq(index).val(value['quantity']);
                        $(".quotation_edit_prices").eq(index).val(value['price']);
                        $(".quotation_edit_remarks").eq(index).val(value['remark']);
                    });
                    editQuotationProductsInit();
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

        $("#quotation_edit_submit_button").click(function () {
            if (editValidation()) {
                $(this).submit();
            } else {
                displayToastr(null, 'Please enter all the product names, quantities and prices to proceed.', 'error', null)
            }
        });

        let editQuotationData = {};
        function editValidation() {
            let productIds = $(".quotation_edit_products").map(function() {
                if($(this).val() !== "")
                    return $(this).val();
            }).get();
            let quantities = $(".quotation_edit_quantities").map(function() {
                if($(this).val() !== "")
                    return $(this).val();
            }).get();
            let prices = $(".quotation_edit_prices").map(function() {
                if($(this).val() !== "")
                    return $(this).val();
            }).get();
            let remarks = $(".quotation_edit_remarks").map(function() {
                return $(this).val();
            }).get();
            let quotationItemIds = $(".quotation_edit_quotation_item_ids").map(function () {
                return $(this).val();
            }).get();
            editQuotationData['productIds'] = productIds;
            editQuotationData['quantities'] = quantities;
            editQuotationData['prices'] = prices;
            editQuotationData['remarks'] = remarks;
            editQuotationData['quotationItemIds'] = quotationItemIds;
            $("#quotation_edit_quotation_data").val(JSON.stringify(editQuotationData));
            return productIds.length == quantities.length && quantities.length == prices.length && productIds.length != 0;
        }

        function editQuotationProductsInit() {
            let products = $(".quotation_edit_products");
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
                        let logText = $('#quotation_edit_log');
                        logText.append(response['name'] + ' '
                            + ' Net Price: '
                            + response['net_price']
                            + ' Sale Price: '
                            + response['sale_price'] + ' Stock: '
                            + response['quantity'] + '\n');
                        logText.scrollTop(logText[0].scrollHeight);
                        editQuotationProductsInit();
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

        $("#quotation_edit_payment_type_id").change(function () {
            if (this.value == 3) {
                $("#quotation_edit_div-amount_paid").removeClass('hidden');
            }
            else {
                $("#quotation_edit_div-amount_paid").addClass('hidden');
            }
        });

        $("#quotation_edit_repeater").repeater({
            show: function () {
                $(this).slideDown();
                $(this).find('.select2-container').remove();
                $(this).find('.quotation_edit_products').html('<option selected="selected">Please select the product</option>');
                editQuotationProductsInit();
            },
            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            },
            isFirstItemUndeletable: true
        });

        $.fn.select2.defaults.set("theme", "bootstrap");
        $("#quotation_edit_customer_id").select2({
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
