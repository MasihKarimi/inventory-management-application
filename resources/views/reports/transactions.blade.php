@extends('layouts.main')

@section('title')
    Generate Transactions Report
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
                <span>Transactions</span>
            </li>
        </ul>
    </div>
    <!-- END PAGE BAR -->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title">Generate Transactions Report</h1>
    <!-- END PAGE TITLE-->
    <!-- END PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class=" icon-layers font-red"></i>
                        <span class="caption-subject font-red bold uppercase">Generate Transactions Report</span>
                    </div>
                </div>
                <div class="portlet-body form">
                    <form class="form-horizontal" id="transactionsReportForm" action="{{ route('reports-transactions-generate') }}">
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
                                <label for="report_customer_id" class="control-label col-md-4">Customer/Vendor
                                    <span class="required"> * </span>
                                </label>
                                <div class="col-md-4">
                                    <select id="report_customer_id" name="customer_id" class="form-control select2">
                                        <option selected disabled>Please select the customer</option>
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
                        <form class="inline-block" method="post" id="transactionsExportForm" action="{{ route('reports-transactions-export') }}">
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
                                Transactions Report From
                                <span id="reportResult_start"></span> To <span id="reportResult_end"></span>
                            </h4>
                            <h4 id="reportResult_title"></h4>
                        </div>
                        <div class="col-xs-12 margin-top-20">
                            <table class="table table-striped table-condensed table-bordered" id="report_table">
                                <thead>
                                <tr style="background-color: #182c41; color: #ffffff;" id="reportResult_head"></tr>
                                </thead>
                                <tbody id="reportResult_body"></tbody>
                                <tfoot>
                                <tr>
                                    <td class="extra_colspan"></td>
                                    <td colspan="2">Opening Balance</td>
                                    <td id="reportResult_opening_balance"></td>
                                </tr>
                                <tr>
                                    <td class="extra_colspan"></td>
                                    <td colspan="2">Closing Balance</td>
                                    <td id="reportResult_closing_balance"></td>
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
        $.fn.select2.defaults.set("theme", "bootstrap");
        $("#report_customer_id").select2({
            width: null,
            ajax: {
                url: "{{ route('customer-search') }}",
                dataType: 'json',
                delay: 150,
                data: function (params) {
                    return {
                        term: params.term || '',
                        page: params.page || 1,
                        all_customers_transaction: true
                    };
                }
            }
        });

        function transactionsReportHandle(response, xhr) {
            let items = '';
            $("#reportResult_start").html(response['start_date']);
            $("#reportResult_end").html(response['end_date']);
            $("#reportResult_opening_balance").html(response['opening_balance']);
            $("#reportResult_closing_balance").html(response['closing_balance']);
            if (response['type'] === 'customer') {
                $("#reportResult_head").html('<th>#</th>' +
                    '<th style="width: 13%;">Date</th>' +
                    '<th>Description</th>' +
                    '<th style="width: 15%">Deal Type</th>' +
                    '<th style="width: 10%">Credit</th>' +
                    '<th style="width: 10%">Debit</th>' +
                    '<th style="width: 10%">Balance</th>');
                $("#reportResult_title").html('Customer: ' + response['customer']);
                $.each(response['items'], function (index, value) {
                    items += '<tr>' +
                        '<td>' + value['number'] + '</td>' +
                        '<td>' + value['date'] +'</td>' +
                        '<td>' + value['description'] + '</td>' +
                        '<td>' + value['deal_type'] + '</td>' +
                        '<td>' + value['credit'] + '</td>' +
                        '<td>' + value['debit'] + '</td>' +
                        '<td>' + value['balance'] + '</td>' +
                        '</tr>';
                });
                $(".extra_colspan").attr('colspan', 3);
                $("#report_table tfoot").show();
            } else {
                $("#reportResult_head").html('<th>#</th>' +
                    '<th>Customer</th>' +
                    '<th style="width: 20%">Credit</th>' +
                    '<th style="width: 20%">Debit</th>' +
                    '<th style="width: 20%">Balance</th>');
                $("#reportResult_title").html('All Customers Report');
                $.each(response['items'], function (index, value) {
                    items += '<tr>' +
                        '<td>' + value['number'] + '</td>' +
                        '<td>' + value['customer'] +'</td>' +
                        '<td>' + value['credit'] + '</td>' +
                        '<td>' + value['debit'] + '</td>' +
                        '<td>' + value['balance'] + '</td>' +
                        '</tr>';
                });
                $("#report_table tfoot").hide();
            }
            $("#reportResult_body").html(items);
            $("#reportResult").show();
            App.scrollTo($("#reportResult_body"));
            let formData = {
                'start_date': $("#start_date").val(),
                'end_date': $("#end_date").val(),
                'customer_id': $("#report_customer_id").val()
            };
            $("#export_form").val(JSON.stringify(formData));
        }

        $("#transactionsReportForm").submit(function (event) {
            event.preventDefault();
            $(this).unbind('submit');
            handleReports($(this), transactionsReportHandle);
            $(this).submit();
        });
    </script>
@endsection
