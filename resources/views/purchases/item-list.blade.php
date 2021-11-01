<div id="purchaseItemListModal" data-target="" class="modal fade bs-modal-lg" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Purchase Items</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="purchaseItemListDataTable"
                               data-action="{{ route('purchase-products-view-data', 0) }}">
                            <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Net Price</th>
                                <th>Net Total</th>
                                <th>Sale Price</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Net Price</th>
                                <th>Net Total</th>
                                <th>Sale Price</th>
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
@section('extraJS')
    @parent
    <script>
        let purchaseProductsDataTable;
        function showPurchaseProducts(purchaseId) {
            let table = $('#purchaseItemListDataTable');
            table.DataTable().clear().destroy();
            let url = table.attr('data-action');
            url = url.replace('0', purchaseId);
            purchaseProductsDataTable = table.DataTable({
                serverSide: true,
                language: {
                    url: "{{ asset("data-table-languages/" . LaravelLocalization::getCurrentLocale() . ".json") }}"
                },
                ajax: {
                    url: url,
                    type: "POST"
                },
                columns: [
                    {data: 'name', name: 'products.name'},
                    {data: 'quantity', name: 'stocks.quantity'},
                    {data: 'net_price', name: 'stocks.net_price'},
                    {data: 'net_total', name: 'net_total'},
                    {data: 'sale_price', name: 'stocks.sale_price'}
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
            $("#purchaseItemListModal").modal('show');
        }
    </script>
@endsection
