@extends('layouts.main')

@section('title')
    Manage Purchases
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
                <span>Purchases</span>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Manage</span>
            </li>
        </ul>
    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title">Manage Purchases</h1>
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title no-print">
                    <div class="caption font-green-sharp">
                        <i class="icon-handbag font-red"></i>
                        <span class="caption-subject font-red bold uppercase">Manage Purchases</span>
                    </div>
                    <div class="actions">
                        <button onclick="showProductPurchaseModal()" class="btn btn-circle btn-info">
                            <i class="fa fa-cart-plus"></i> Purchase Product</button>
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;"> </a>
                    </div>
                </div>
                <div class="portlet-body form">
                    <table class="table table-striped table-bordered table-hover" id="purchasesDataTable"
                           data-action="{{ route('purchases-view-data') }}">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Vendor Name</th>
                            <th>Vendor Phone</th>
                            <th>Payment Type</th>
                            <th>Reference</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th style="min-width: 100px;">Actions</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Vendor Name</th>
                            <th>Vendor Phone</th>
                            <th>Payment Type</th>
                            <th>Reference</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th class="non_searchable">Actions</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('purchases.new')
    @include('purchases.item-list')
    @include('purchases.edit')
    @include('common.delete-confirm', ['subject' => 'Purchase', 'route' => route('purchases-delete'), 'message' => 'All products related to this purchase will be deleted as well.'])
@endsection

@section('extraJS')
    <script>
        function deleteRecord(purchaseId) {
            $("#delete_purchase_id").val(purchaseId);
            $("#purchaseDeleteModal").modal('show');
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let table = $('#purchasesDataTable');
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
                {data: 'id', name: 'purchases.id'},
                {data: 'name', name: 'customers.name'},
                {data: 'phone', name: 'customers.phone'},
                {data: 'payment_type', name: 'payment_types.name as payment_type'},
                {data: 'reference', name: 'purchases.reference'},
                {data: 'date', name: 'purchases.date'},
                {data: 'total', name: 'purchase_total.total'},
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
