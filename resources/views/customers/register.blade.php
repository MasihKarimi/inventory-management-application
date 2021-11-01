<div id="customerRegisterModal" data-target="" class="modal fade bs-modal-lg" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal submit_form_response" role="form" method="post" action="{{ route('customers-register-submit') }}">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Register New Customer</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_register_name" class="col-md-3 control-label">Name</label>
                                <div class="col-md-9">
                                    <input type="text" id="customer_register_name" name="name" class="form-control" placeholder="Name">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_register_phone" class="col-md-3 control-label">Phone</label>
                                <div class="col-md-9">
                                    <input type="text" id="customer_register_phone" name="phone" class="form-control" placeholder="Phone">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_register_address" class="col-md-3 control-label">Address</label>
                                <div class="col-md-9">
                                    <input type="text" id="customer_register_address" name="address" class="form-control" placeholder="Address">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_register_focal_point_person" class="col-md-3 control-label">Focal Point Person</label>
                                <div class="col-md-9">
                                    <input type="text" id="customer_register_focal_point_person" name="focal_point_person" class="form-control" placeholder="Focal Point">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_register_TIN_number" class="col-md-3 control-label">TIN Number</label>
                                <div class="col-md-9">
                                    <input type="text" id="customer_register_TIN_number" name="TIN_number" class="form-control" placeholder="TIN Number">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_register_license_number" class="col-md-3 control-label">License Number</label>
                                <div class="col-md-9">
                                    <input type="text" id="customer_register_license_number" name="license_number" class="form-control" placeholder="License Number">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_register_registration_number" class="col-md-3 control-label">Registration Number</label>
                                <div class="col-md-9">
                                    <input type="text" id="customer_register_registration_number" name="registration_number" class="form-control" placeholder="Registration Number">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_register_license_number" class="col-md-3 control-label">Customer Type</label>
                                <div class="col-md-9">
                                    <select name="customer_type_id" id="customer_register_customer_type_id" class="form-control">
                                        <option selected disabled>Customer Type</option>
                                        @foreach(\App\CustomerType::all() as $customerType)
                                            <option value="{{ $customerType->id }}">{{ $customerType->name }}</option>
                                        @endforeach
                                    </select>
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
        function showCustomerRegisterModal() {
            $("#customerRegisterModal").modal('show');
        }
    </script>
@endsection
