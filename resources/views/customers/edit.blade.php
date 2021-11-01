<div id="customerEditModal" data-target="" class="modal fade bs-modal-lg" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal submit_form_response" role="form" method="post" action="{{ route('customers-update') }}">
                @csrf
                <input type="hidden" name="customer_id" id="customer_edit_customer_id">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Edit Customer (ID#: <span id="customer_edit_span_customer_id"></span>)</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_edit_name" class="col-md-3 control-label">Name</label>
                                <div class="col-md-9">
                                    <input type="text" id="customer_edit_name" name="name" class="form-control" placeholder="Name">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_edit_phone" class="col-md-3 control-label">Phone</label>
                                <div class="col-md-9">
                                    <input type="text" id="customer_edit_phone" name="phone" class="form-control" placeholder="Phone">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_edit_address" class="col-md-3 control-label">Address</label>
                                <div class="col-md-9">
                                    <input type="text" id="customer_edit_address" name="address" class="form-control" placeholder="Address">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_edit_focal_point_person" class="col-md-3 control-label">Focal Point Person</label>
                                <div class="col-md-9">
                                    <input type="text" id="customer_edit_focal_point_person" name="focal_point_person" class="form-control" placeholder="Focal Point">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_edit_TIN_number" class="col-md-3 control-label">TIN Number</label>
                                <div class="col-md-9">
                                    <input type="text" id="customer_edit_TIN_number" name="TIN_number" class="form-control" placeholder="TIN Number">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_edit_license_number" class="col-md-3 control-label">License Number</label>
                                <div class="col-md-9">
                                    <input type="text" id="customer_edit_license_number" name="license_number" class="form-control" placeholder="License Number">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_edit_registration_number" class="col-md-3 control-label">Registration Number</label>
                                <div class="col-md-9">
                                    <input type="text" id="customer_edit_registration_number" name="registration_number" class="form-control" placeholder="Registration Number">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_edit_license_number" class="col-md-3 control-label">Customer Type</label>
                                <div class="col-md-9">
                                    <select name="customer_type_id" id="customer_edit_customer_type_id" class="form-control">
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
        function showCustomerEditModal(customerId) {
            let url = "{{ route('customer-get-details') }}";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: url,
                data: { customer_id: customerId },
                success: function (response, status, xhr, $form) {
                    $("#customerEditModal").modal('show');
                    $("#customer_edit_span_customer_id").html(response['id']);
                    $("#customer_edit_customer_id").val(response['id']);
                    $("#customer_edit_name").val(response['name']);
                    $("#customer_edit_phone").val(response['phone']);
                    $("#customer_edit_address").val(response['address']);
                    $("#customer_edit_focal_point_person").val(response['focal_point_person']);
                    $("#customer_edit_TIN_number").val(response['TIN_number']);
                    $("#customer_edit_license_number").val(response['license_number']);
                    $("#customer_edit_registration_number").val(response['registration_number']);
                    $("#customer_edit_customer_type_id").val(response['customer_type_id']);
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
