<div id="productStocksListModal" data-target="" class="modal fade bs-modal-lg" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Product Stocks Log</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="productStocksListDataTable"
                               data-action="{{ route('product-stocks-view-data', 0) }}">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Vendor Name</th>
                                <th>Deal Type</th>
                                <th>Reference</th>
                                <th>Quantity</th>
                                <th>Net Price</th>
                                <th>Net Total</th>
                                <th>Sale Price</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Date</th>
                                <th>Vendor Name</th>
                                <th>Deal Type</th>
                                <th>Reference</th>
                                <th>Quantity</th>
                                <th>Net Price</th>
                                <th>Net Total</th>
                                <th>Sale Price</th>
                                <th class="non_searchable">Actions</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn default">Close</button>
            </div>
        </div>
    </div>
</div>
@include('common.delete-confirm', ['subject' => 'Stock', 'route' => route('products-stock-delete'), 'message' => 'All purchases related to this stock will be deleted as well.'])
@section('extraJS')
    @parent
    <script>
        function deleteStockRecord(stockId) {
            $("#delete_stock_id").val(stockId);
            $("#stockDeleteModal").modal('show');
        }

        let productStocksDataTable;
        function showProductStocks(productId) {
            let table = $('#productStocksListDataTable');
            table.DataTable().clear().destroy();
            let url = table.attr('data-action');
            url = url.replace('0', productId);
            productStocksDataTable = table.DataTable({
                serverSide: true,
                language: {
                    url: "{{ asset("data-table-languages/" . LaravelLocalization::getCurrentLocale() . ".json") }}"
                },
                ajax: {
                    url: url,
                    type: "POST"
                },
                columns: [
                    {data: 'date', name: 'purchases.date'},
                    {data: 'name', name: 'customers.name'},
                    {data: 'type', name: 'payment_types.name as type'},
                    {data: 'reference', name: 'purchases.reference'},
                    {data: 'quantity', name: 'stocks.quantity'},
                    {data: 'net_price', name: 'stocks.net_price'},
                    {data: 'net_total', name: 'net_total'},
                    {data: 'sale_price', name: 'stocks.sale_price'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}
                ],
                bPaginate: false,
                responsive: true,
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
            $("#productStocksListModal").modal('show');
        }
    </script>
@endsection
