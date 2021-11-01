@extends('layouts.main')

@section('title')
    Manage Customers
@endsection

@section('content')
    <!-- BEGIN PAGE HEADER-->
    <!-- BEGIN PAGE BAR -->
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="{{ route('home') }}">{{ __('home.name') }}</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Customers</span>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Manage</span>
            </li>
        </ul>
    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title">Manage Customers</h1>
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title no-print">
                    <div class="caption font-green-sharp">
                        <i class="icon-users font-red"></i>
                        <span class="caption-subject font-red bold uppercase">Manage Customers</span>
                    </div>
                    <div class="actions">
                        <button onclick="showCustomerRegisterModal()" class="btn btn-circle btn-primary">
                            <i class="fa fa-plus"></i> Register New Customer</button>
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;"> </a>
                    </div>
                </div>
                <div class="portlet-body form">
                    <table class="table table-striped table-bordered table-hover" id="customersDataTable"
                           data-action="{{ route('customers-view-data') }}">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Focal Point Person</th>
                            <th>TIN #</th>
                            <th>License #</th>
                            <th>Registration #</th>
                            <th style="min-width: 80px;">Actions</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Focal Point Person</th>
                            <th>TIN #</th>
                            <th>License #</th>
                            <th>Registration #</th>
                            <th class="non_searchable">Actions</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('customers.register')
    @include('customers.edit')
    @include('common.delete-confirm', ['subject' => 'Customer', 'route' => route('customers-delete')])
@endsection

@section('extraJS')
    <script>
        function deleteRecord(customerId) {
            $("#delete_customer_id").val(customerId);
            $("#customerDeleteModal").modal('show');
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let table = $('#customersDataTable');
        let dataTable = table.DataTable({
            serverSide: true,
            language: {
                url: "{{ asset("data-table-languages/" . LaravelLocalization::getCurrentLocale() . ".json") }}"
            },
            ajax: {
                url: table.attr('data-action'),
                type: "POST"
            },
            columns: [
                {data: 'id', name: 'customers.id'},
                {data: 'type', name: 'customer_types.name as type'},
                {data: 'name', name: 'customers.name'},
                {data: 'phone', name: 'customers.phone'},
                {data: 'address', name: 'customers.address'},
                {data: 'focal_point_person', name: 'customers.focal_point_person'},
                {data: 'TIN_number', name: 'customers.TIN_number'},
                {data: 'license_number', name: 'customers.license_number'},
                {data: 'registration_number', name: 'customers.registration_number'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false}
            ],
            responsive: true,
            bLengthChange: false,
            pageLength: 10,
            "dom": "<'row'>t<'row'<'col-sm-12'<'text-center'p>>>",
            initComplete: function () {
                $('.tooltips').tooltip();
                this.api().columns().every(function () {
                    let column = this;
                    let columnClass = column.footer().className;
                    if(columnClass !== 'non_searchable') {
                        let input = document.createElement("input");
                        $(input).addClass("form-control");
                        $(input).appendTo($(column.footer()).empty())
                            .on('keyup change', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                    }
                });
            }
        });
    </script>
@endsection
