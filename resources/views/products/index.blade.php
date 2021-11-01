@extends('layouts.main')

@section('title')
    Manage Products
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
                <span>Products</span>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Manage</span>
            </li>
        </ul>
    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title">Manage Products</h1>
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title no-print">
                    <div class="caption font-green-sharp">
                        <i class="icon-handbag font-red"></i>
                        <span class="caption-subject font-red bold uppercase">Manage Products</span>
                    </div>
                    <div class="actions">
                        <button onclick="showProductPurchaseModal()" class="btn btn-circle btn-info">
                            <i class="fa fa-cart-plus"></i> Purchase Product</button>
                        {{--<button onclick="showProductRegisterModal()" class="btn btn-circle btn-primary">
                            <i class="fa fa-plus"></i> Register New Product</button>--}}
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;"> </a>
                    </div>
                </div>
                <div class="portlet-body form">
                    <table class="table table-striped table-bordered table-hover" id="productsDataTable"
                           data-action="{{ route('products-view-data') }}">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Part Number</th>
                            <th>Name</th>
                            <th>Net Price</th>
                            <th>Sale Price</th>
                            <th>Quantity</th>
                            <th style="min-width: 100px;">Actions</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Part Number</th>
                            <th>Name</th>
                            <th>Net Price</th>
                            <th>Sale Price</th>
                            <th>Quantity</th>
                            <th class="non_searchable">Actions</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{--@include('products.register')--}}
    @include('purchases.new')
    @include('products.edit')
    @include('products.stocks-list')
    @include('common.delete-confirm', ['subject' => 'Product', 'route' => route('products-delete'), 'message' => 'All stocks and transactions related to this product will be deleted as well.'])
@endsection

@section('extraJS')
    <script>
        function deleteRecord(expenseId) {
            $("#delete_product_id").val(expenseId);
            $("#productDeleteModal").modal('show');
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let table = $('#productsDataTable');
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
                {data: 'id', name: 'products.id'},
                {data: 'part_number', name: 'products.part_number'},
                {data: 'name', name: 'products.name'},
                {data: 'net_price', name: 'latest_prices.net_price'},
                {data: 'sale_price', name: 'latest_prices.sale_price'},
                {data: 'quantity', name: 'latest_prices.quantity'},
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
