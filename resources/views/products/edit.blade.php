<div id="productEditModal" data-target="" class="modal fade bs-modal-md" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form class="form-horizontal submit_form_response" role="form" method="post" action="{{ route('products-update') }}">
                @csrf
                <input type="hidden" name="product_id" id="product_edit_product_id">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Edit Product (ID#: <span id="product_edit_span_product_id"></span>)</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="product_edit_name" class="col-md-3 control-label">Name</label>
                                <div class="col-md-8">
                                    <input type="text" id="product_edit_name" name="name" class="form-control" placeholder="Name">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="product_edit_part_number" class="col-md-3 control-label">Part Number</label>
                                <div class="col-md-8">
                                    <input type="text" id="product_edit_part_number" name="part_number" class="form-control" placeholder="Part Number">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn green">Edit</button>
                    <button type="reset" data-dismiss="modal" class="btn default">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@section('extraJS')
    @parent
    <script>
        function showProductEditModal(productId) {
            let url = "{{ route('product-get-details') }}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: url,
                data: { product_id: productId },
                success: function (response, status, xhr, $form) {
                    $("#productEditModal").modal('show');
                    $("#product_edit_span_product_id").html(response['id']);
                    $("#product_edit_product_id").val(response['id']);
                    $("#product_edit_name").val(response['name']);
                    $("#product_edit_part_number").val(response['part_number']);
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
