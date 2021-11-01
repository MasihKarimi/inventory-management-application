@extends('layouts.main')

@section('title')
    Generate Expense Report
@endsection

@section('extraCSS')
    <style type="text/css" media="print">
        body * {
            visibility: hidden;
        }
        #report_printing_area, #report_printing_area * {
            visibility: visible;
        }
        #report_printing_area {
            margin-top: -80px;
            position: absolute;
            left: 0;
            top: 0;
        }
    </style>
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
                <span>Reports</span>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Expenses</span>
            </li>
        </ul>
    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title">Generate Expenses Report</h1>
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class=" icon-layers font-red"></i>
                        <span class="caption-subject font-red bold uppercase">Generate Expenses Report</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form class="form-horizontal" id="expensesReportForm" action="{{ route('reports-expenses-generate') }}">
                        @csrf
                        <div class="row">
                            <div class="form-group">
                                <label for="start_date" class="control-label col-md-4">Reporting Date Range
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4">
                                    <div class="input-group input-large date-picker input-daterange" data-date-format="yyyy-mm-dd">
                                        <input id="start_date" placeholder="Start Date" type="text"
                                               class="form-control" name="start_date" autocomplete="off">
                                        <span class="input-group-addon"> &nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp; </span>
                                        <input id="end_date" placeholder="End Date" type="text"
                                               class="form-control" name="end_date" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="expense_type_id" class="control-label col-md-4">Expense Type
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4">
                                    <select name="expense_type_id[]" class="form-control" id="expense_type_id" multiple>
                                        <option selected disabled>Expense Types</option>
                                        @foreach(\App\ExpenseType::all() as $expenseType)
                                            <option value="{{ $expenseType->id }}">{{ $expenseType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-3"></label>
                                <div class="col-md-4 text-right">
                                    <button class="btn btn-success" type="submit">
                                        <i class="fa fa-search"></i>Generate
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="portlet light bordered" id="reportResult" hidden>
                <div class="portlet-title">
                    <div class="actions pull-right">
                        <button onclick="window.print();" class="btn btn-info">
                            <i class="fa fa-print"></i> Print
                        </button>
                        <form class="inline-block" method="post" id="expensesExportForm" action="{{ route('reports-expenses-export') }}">
                            @csrf
                            <input type="hidden" name="form" id="export_form">
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="fa fa-file-excel-o"></i> Export </button>
                        </form>
                        <button class="btn btn-circle btn-icon-only btn-default fullscreen"
                           data-original-title="" title=""></button>
                    </div>
                </div>
                <div class="portlet-body" id="report_printing_area">
                    <div class="row">
                        <div class="col-xs-12 text-center">
                            <h1>{{ __('app.name') }}</h1>
                            <h4>
                                Expenses Report From
                                <span id="reportResult_start"></span> To <span id="reportResult_end"></span>
                            </h4>
                        </div>
                        <div class="col-xs-12 margin-top-20">
                            <table class="table table-striped table-condensed">
                                <thead>
                                <tr style="background-color: #182c41; color: #ffffff;">
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>By</th>
                                    <th>Remark</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody id="reportResult_body">
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="4">Total</td>
                                    <td id="reportResult_total"></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extraJS')
    <script>
        function expensesReportHandle(response, xhr) {
            $("#reportResult_start").html(response['start_date']);
            $("#reportResult_end").html(response['end_date']);
            $("#reportResult_total").html(response['total']);
            let items = '';
            $.each(response['items'], function (index, value) {
                items += '<tr>' +
                    '<td>' + value['date'] +'</td>' +
                    '<td>' + value['type'] + '</td>' +
                    '<td>' + value['by'] + '</td>' +
                    '<td>' + value['remark'] + '</td>' +
                    '<td>' + value['amount'] + '</td>' +
                    '</tr>';
            });
            $("#reportResult_body").html(items);
            $("#reportResult").show();
            App.scrollTo($("#reportResult_body"));
        }

        $("#expensesReportForm").submit(function (event) {
            event.preventDefault();
            $(this).unbind('submit');
            handleReports($(this), expensesReportHandle);
            $(this).submit();
            let formData = {
                'start_date': $("#start_date").val(),
                'end_date': $("#end_date").val(),
                'expense_type_id': $("#expense_type_id").val()
            };
            $("#export_form").val(JSON.stringify(formData));
        });
    </script>
@endsection
