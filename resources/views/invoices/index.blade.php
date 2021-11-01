@extends('layouts.main')

@section('title')
    Manage Invoices
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
                <span>Invoices</span>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Manage</span>
            </li>
        </ul>
    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title">Manage Invoices</h1>
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title no-print">
                    <div class="caption font-green-sharp">
                        <i class="icon-users font-red"></i>
                        <span class="caption-subject font-red bold uppercase">Manage Invoices</span>
                    </div>
                    <div class="actions">
                        <button onclick="showNewInvoiceModal()" class="btn btn-circle btn-primary">
                            <i class="fa fa-plus"></i> New Invoice</button>
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;"> </a>
                    </div>
                </div>
                <div class="portlet-body form">
                    <table class="table table-striped table-bordered table-hover" id="invoicesDataTable"
                           data-action="{{ route('invoices-view-data') }}">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Customer Phone</th>
                            <th>Payment Type</th>
                            <th>Order Number</th>
                            <th>Order Date</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th style="min-width: 100px;">Actions</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Customer Phone</th>
                            <th>Payment Type</th>
                            <th>Order Number</th>
                            <th>Order Date</th>
                            <th>Total</th>
                            <th>Date</th>
                            <th class="non_searchable">Actions</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('invoices.new')
    @include('invoices.item-list')
    @include('invoices.print')
    @include('invoices.delivery-note-print')
    @include('invoices.edit')
    @include('common.delete-confirm', ['subject' => 'Invoice', 'route' => route('invoices-delete')])
@endsection

@section('extraJS')
    <script>
        function deleteRecord(invoiceId) {
            $("#delete_invoice_id").val(invoiceId);
            $("#invoiceDeleteModal").modal('show');
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let table = $('#invoicesDataTable');
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
                {data: 'id', name: 'invoices.id'},
                {data: 'name', name: 'customers.name'},
                {data: 'phone', name: 'customers.phone'},
                {data: 'payment_type', name: 'payment_types.name as payment_type'},
                {data: 'order_number', name: 'invoices.order_number'},
                {data: 'order_date', name: 'invoices.order_date'},
                {data: 'total', name: 'invoice_total.total'},
                {data: 'date', name: 'invoices.date'},
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
