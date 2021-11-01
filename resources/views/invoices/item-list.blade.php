<div id="invoiceItemsListModal" data-target="" class="modal fade bs-modal-lg" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Invoice Items</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="invoiceItemsListDataTable"
                               data-action="{{ route('invoice-items-view-data', 0) }}">
                            <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Part Number</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Product Name</th>
                                <th>Part Number</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
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
        let invoiceItemsDataTable;
        function showInvoiceItems(invoiceId) {
            let table = $('#invoiceItemsListDataTable');
            table.DataTable().clear().destroy();
            let url = table.attr('data-action');
            url = url.replace('0', invoiceId);
            let invoiceItemsDataTable = table.DataTable({
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
                    {data: 'part_number', name: 'products.part_number'},
                    {data: 'quantity', name: 'invoice_items.quantity'},
                    {data: 'price', name: 'invoice_items.price'},
                    {data: 'total', name: 'total'},
                ],
                bPaginate: false,
                responsive: true,
                bLengthChange: false,
                "dom": "<'row'>t<'row'<'col-sm-12'<'text-center'p>>>",
                initComplete: function () {

                }
            });
            $("#invoiceItemsListModal").modal('show');
        }
    </script>
@endsection
