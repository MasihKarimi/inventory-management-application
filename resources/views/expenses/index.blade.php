@extends('layouts.main')

@section('title')
    Manage Expenses
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
                <span>Expenses</span>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Manage</span>
            </li>
        </ul>
    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title">Manage Expenses</h1>
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-green-sharp">
                        <i class="icon-wallet font-red"></i>
                        <span class="caption-subject font-red bold uppercase">Manage Expenses</span>
                    </div>
                    <div class="actions">
                        <button onclick="showExpenseRegisterModal()" class="btn btn-circle btn-primary">
                            <i class="fa fa-plus"></i> Add New Expense</button>
                        <button onclick="showExpenseTypeModal()" class="btn btn-circle btn-warning">
                            <i class="fa fa-plus"></i> View Expense Types</button>
                        <button onclick="showExpenseTypeRegisterModal()" class="btn btn-circle btn-success">
                            <i class="fa fa-plus"></i> Add New Expense Type</button>
                        <a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;"> </a>
                    </div>
                </div>
                <div class="portlet-body form">
                    <table class="table table-striped table-bordered table-hover" id="expensesDataTable"
                           data-action="{{ route('expenses-view-data') }}">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>By</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Remark</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>By</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Remark</th>
                            <th class="non_searchable">Actions</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('expenses.register')
    @include('expenses.type-index')
    @include('expenses.type-register')
    @include('common.delete-confirm', ['subject' => 'Expense', 'route' => route('expenses-delete')])
@endsection

@section('extraJS')
    <script>
        function deleteRecord(expenseId) {
            $("#delete_expense_id").val(expenseId);
            $("#expenseDeleteModal").modal('show');
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let table = $('#expensesDataTable');
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
                {data: 'id', name: 'expenses.id'},
                {data: 'type', name: 'expense_types.name as type'},
                {data: 'expense_by', name: 'expenses.expense_by'},
                {data: 'amount', name: 'expenses.amount'},
                {data: 'date', name: 'expenses.date'},
                {data: 'remark', name: 'expenses.remark'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false}
            ],
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
    </script>
@endsection
