<div id="transactionListModal" data-target="" class="modal fade bs-modal-lg" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Transactions</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="transactionsListDataTable"
                               data-action="{{ route('customer-transactions-view-data', 0) }}">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Deal Type</th>
                                <th>Amount</th>
                                <th>Balance</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Deal Type</th>
                                <th>Amount</th>
                                <th class="non_searchable">Balance</th>
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
@include('common.delete-confirm', ['subject' => 'Transaction', 'route' => route('transactions-delete')])
@section('extraJS')
    @parent
    <script>
        function deleteTransactionRecord(transactionId) {
            $("#delete_transaction_id").val(transactionId);
            $("#transactionDeleteModal").modal('show');
        }

        let transactionsDataTable;
        function customerTransactions(customerId) {
            let table = $('#transactionsListDataTable');
            table.DataTable().clear().destroy();
            let url = table.attr('data-action');
            url = url.replace('0', customerId);
            let transactionsDataTable = table.DataTable({
                serverSide: true,
                language: {
                    url: "{{ asset("data-table-languages/" . LaravelLocalization::getCurrentLocale() . ".json") }}"
                },
                ajax: {
                    url: url,
                    type: "POST"
                },
                columns: [
                    {data: 'date', name: 'date'},
                    {data: 'description', name: 'description'},
                    {data: 'type', name: 'type'},
                    {data: 'deal_type', name: 'deal_type'},
                    {data: 'amount', name: 'amount'},
                    {data: 'balance', name: 'balance'},
                    {data: 'actions', name: 'actions', orderable: false, searchable: false}
                ],
                order: [],
                responsive: true,
                bLengthChange: false,
                pageLength: 10,
                "dom": "<'row'>t<'row'<'col-sm-12'<'text-center'p>>>",
                initComplete: function () {
                    $(".tooltips").tooltip();
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
            $("#transactionListModal").modal('show');
        }
    </script>
@endsection
