<div id="productRegisterModal" data-target="" class="modal fade bs-modal-md" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form class="form-horizontal submit_form_response" role="form" method="post" action="{{ route('products-register-submit') }}">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Register New Product</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="product_registration_name" class="col-md-3 control-label">Name</label>
                                <div class="col-md-8">
                                    <input type="text" id="product_registration_name" name="name" class="form-control" placeholder="Name">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="product_registration_part_number" class="col-md-3 control-label">Part Number</label>
                                <div class="col-md-8">
                                    <input type="text" id="product_registration_part_number" name="part_number" class="form-control" placeholder="Part Number">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn green">Register</button>
                    <button type="reset" data-dismiss="modal" class="btn default">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@section('extraJS')
    @parent
    <script>
        function showProductRegisterModal() {
            $("#productRegisterModal").modal('show');
        }
    </script>
@endsection
