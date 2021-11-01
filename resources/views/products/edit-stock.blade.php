<div id="stockEditModal" data-target="" class="modal fade bs-modal-lg" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal submit_form_response" role="form" method="post" action="{{ route('product-stock-update') }}">
                @csrf
                <input type="hidden" id="stock_edit_stock_id" name="stock_id">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Edit Product Purchase</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stock_edit_customer_id" class="col-md-4 control-label">Vendor</label>
                                <div class="col-md-8">
                                    <select id="stock_edit_customer_id" name="customer_id" class="form-control select2"></select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stock_edit_product_id" class="col-md-4 control-label">Product</label>
                                <div class="col-md-8">
                                    <select id="stock_edit_product_id" name="product_id" class="form-control select2"></select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stock_edit_date" class="col-md-4 control-label">Date</label>
                                <div class="col-md-8">
                                    <input type="text" id="stock_edit_date" name="date" class="form-control date-picker" autocomplete="off" placeholder="Date">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="stock_edit_reference" class="col-md-4 control-label">Reference</label>
                                <div class="col-md-8">
                                    <input type="text" id="stock_edit_reference" name="reference" class="form-control" placeholder="Reference">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="stock_edit_quantity" class="col-md-4 control-label">Quantity</label>
                                <div class="col-md-8">
                                    <input type="number" id="stock_edit_quantity" name="quantity" class="form-control" placeholder="Quantity">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="stock_edit_net_price" class="col-md-4 control-label">Net Price</label>
                                <div class="col-md-8">
                                    <input type="number" id="stock_edit_net_price" step="0.01" name="net_price" class="form-control" placeholder="Net Price">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="stock_edit_sale_price" class="col-md-4 control-label">Sale Price</label>
                                <div class="col-md-8">
                                    <input type="number" id="stock_edit_sale_price" step="0.01" name="sale_price" class="form-control" placeholder="Sale Price">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn green">Update</button>
                    <button type="reset" data-dismiss="modal" class="btn default">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@section('extraJS')
    @parent
    <script>
        $.fn.select2.defaults.set("theme", "bootstrap");
        function editStockRecord(stockId) {
            let url = "{{ route('product-stocks-get-details') }}";
            $.ajax({
                type: 'POST',
                url: url,
                data: { stock_id: stockId },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function (response, status, xhr, $form) {
                    $("#stockEditModal").modal('show');
                    $("#stock_edit_stock_id").val(response['id']);
                    if(response['purchase']['customer'] && response['purchase']['customer']['id'])
                        $("#stock_edit_customer_id").html('<option value="' + response['purchase']['customer']['id'] + '">' + response['purchase']['customer']['name'] + '</option>').trigger('change');
                    $("#stock_edit_product_id").html('<option value="' + response['product']['id'] + '">' + response['product']['part_number'] + '(' + response['product']['name'] + ')</option>');
                    $("#stock_edit_date").val(response['purchase']['date']);
                    $("#stock_edit_reference").val(response['purchase']['reference']);
                    $("#stock_edit_quantity").val(response['quantity']);
                    $("#stock_edit_net_price").val(response['net_price']);
                    $("#stock_edit_sale_price").val(response['sale_price']);
                    $("#stock_edit_customer_id").select2({
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

                    $("#stock_edit_product_id").select2({
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
    </script>
@endsection
