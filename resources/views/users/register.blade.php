<div id="userRegisterModal" data-target="" class="modal fade bs-modal-lg" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="form-horizontal submit_form_response" role="form" method="post" action="{{ route('users-add-submit') }}">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Add New User</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_register_name" class="col-md-3 control-label">Name</label>
                                <div class="col-md-9">
                                    <input type="text" id="user_register_name" name="name" class="form-control" placeholder="Full Name">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_register_email" class="col-md-3 control-label">Email</label>
                                <div class="col-md-9">
                                    <input type="text" id="user_register_email" name="email" class="form-control" placeholder="Email Address">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_register_password" class="col-md-3 control-label">Password</label>
                                <div class="col-md-9">
                                    <input type="password" id="user_register_password" name="password" class="form-control" placeholder="Password">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_register_password_confirmation" class="col-md-3 control-label">Confirm Password</label>
                                <div class="col-md-9">
                                    <input type="password" id="user_register_password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm Password">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_register_email" class="col-md-3 control-label">Role</label>
                                <div class="col-md-9">
                                    <select name="role_id" id="user_register_role_id" class="form-control">
                                        <option selected disabled>Select User Role</option>
                                        @foreach(\App\Role::all() as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
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
@section('extraJS')
    @parent
    <script>
        function showUserRegisterModal() {
            $("#userRegisterModal").modal('show');
        }
    </script>
@endsection
