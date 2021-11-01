<div id="printInvoiceModal" data-target="" class="modal fade modal-scroll bs-modal-lg" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Print Invoice</h4>
            </div>
            <div class="modal-body">
                <div class="invoice" id="invoice_printing_area">
                    <style type="text/css" media="print">
                        #print-button {
                            visibility: hidden;
                        }
                        #invoice_printing_area {
                            position: absolute;
                            left: 0;
                            top: 0;
                        }
                        #invoice_printing_area tr th {
                            padding: 6px;
                            font-size: 14px !important;
                            font-weight: 600;
                            text-align: center !important;
                            -webkit-print-color-adjust: exact;
                            background-color: black !important;
                            color: white !important;
                        }
                        #invoice_printing_area tfoot{
                            -webkit-print-color-adjust: exact;
                            background-color: black !important;
                            color: white !important;
                        }
                        #invoice_printing_area tr td {
                            padding: 4px;
                            font-size: 12px !important;
                            font-weight: 600;
                        }
                        #invoice_printing_area h3 {
                            font-size: 18px !important;
                            font-weight: bold;
                        }
                        #invoice_printing_area span {
                            font-size: 20px;
                            font-weight: 600;
                            text-align: right !important;
                        }
                        #invoice_printing_area th, #invoice_printing_area td {
                            vertical-align: middle !important;
                        }
                        .col-xs-4{
                            border-top: 1px solid #656565;
                            border-bottom: 1px solid #656565;;
                            padding: 0 !important;
                            margin: 0 !important;
                            height: 105px !important;
                        }
                        #invoice-center{
                            padding-left: 50px;
                            padding-top: 10px;
                        }
                        .col-xs-5{
                            border: 1px solid #656565;;
                            padding-right: 0 !important;
                            margin-right: 0 !important;
                            height: 105px !important;
                        }
                        .col-xs-3{
                            border: 1px solid #656565;
                            padding-left: 0 !important;
                            margin-left: 0 !important;
                            height: 105px !important;
                        }
                        #invoice_print_invoice_information{
                            padding-left: 10px;
                            padding-top: 15px;
                        }
                        #invoice_client_info{
                            padding-bottom: 10px;
                        }
                        .table td:nth-child(4) {
                            text-align: center !important;
                        }
                        .table td:nth-child(5), .table td:nth-child(6) {
                            text-align: right !important;
                        }
                        .table td:nth-child(7) {
                            text-align: center !important;
                        }
                        #tfoot td{
                            -webkit-print-color-adjust: exact;
                            background-color: black !important;
                            color: white !important;
                        }
                        #tfoot td strong{
                            color: white !important;
                        }
                        #tfoot td center{
                            color: white !important;
                        }
                    </style>
                    <style>
                        #invoice_printing_area th, #invoice_printing_area td {
                            vertical-align: middle !important;
                        }
                        .table td:nth-child(4) {
                            text-align: center !important;
                        }
                        .table td:nth-child(5), .table td:nth-child(6) {
                            text-align: right !important;
                        }
                        .table td:nth-child(7) {
                            text-align: center !important;
                        }
                        #invoice_printing_area thead{
                            -webkit-print-color-adjust: exact;
                            background-color: black !important;
                            color: white !important;
                        }
                    </style>
                    <div class="row" id="print-button" hidden>
                        <div class="col-md-2">
                            <button onclick="window.print()">Print</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-5">
                            <span >Customer</span>
                            <ul class="list-unstyled" id="invoice_client_info" style="font-size: 12px" >
                            </ul>
                        </div>
                        <div class="col-xs-4 text-left" >
                            <div id="invoice-center">
                                <span >INVOICE</span>
                                <br>
                                <div id="otherInfo" style="font-size: 12px"></div>
                            </div>
                        </div>
                        <div class="col-xs-3 text-left" >
                            <div id="invoice_print_invoice_information" style="font-size: 12px"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th rowspan="2" style="background-color: black !important; color: white !important;"> NO </th>
                                    <th style="width: 20%; background-color: black !important; color: white !important;">Part #</th>
                                    <th style="width: 30%; background-color: black !important; color: white !important;">Name</th>
                                    <th rowspan="2" style="width: 5%; background-color: black !important; color: white !important;"> Qty </th>
                                    <th rowspan="2" style="width: 15%; background-color: black !important; color: white !important;"> Unit Cost </th>
                                    <th rowspan="2" style="width: 15%; background-color: black !important; color: white !important;"> Total </th>
                                    <th rowspan="2" style="width: 15%; background-color: black !important; color: white !important;"> Remarks </th>
                                </tr>
                                </thead>
                                <tbody id="invoice_print_table_body"></tbody>
                            </table>
                            <div class="row margin-top-10">
                                <div class="col-xs-12 text-center"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn green" onclick="printSection('invoice_printing_area', '<link href=\'' + '{{ asset('theme/ltr/global/plugins/bootstrap/css/bootstrap.min.css') }}\'' + ' rel=\'stylesheet\' type=\'text/css\'/>')">
                    <i class="fa fa-print"></i>Print Preview
                </button>
                <button data-dismiss="modal" class="btn default">Cancel</button>
            </div>
        </div>
    </div>
</div>

@section('extraJS')
    @parent
    <script>
        function showPrintInvoiceModal(invoiceId) {
            let url = "{{ route('invoice-print', 0) }}";
            url = url.replace('0', invoiceId);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: url,
                data: { invoice_id: invoiceId },
                success: function (response, status, xhr, $form) {
                    $("#printInvoiceModal").modal('show');
                    let customerInfo = '', invoiceInfo = '',otherInfo = '', items = '', counter = 0; let totalItemRemaining = 20;
                    $.each(response['customer'], function (index, value) {
                        let val = value === null ? '' : value;
                        customerInfo += '<li><strong>' + index + ':</strong> ' + val + '</li>';
                    });
                    $.each(response['invoice'], function (index, value) {
                        if (index === 'total') return;
                        invoiceInfo += '<strong>' + index + ':</strong> ' + value + ' <br> ';
                    });

                    $.each(response['otherInfo'], function (index, value) {
                        if (index === 'total') return;
                        if (index === 'Invoice #') value = '{{ __('app.invoice_prefix') }}' + value;
                        otherInfo += '<strong>' + index + ':</strong> ' + value + ' <br> ';
                    });

                    $.each(response['items'], function (index, value) {
                        counter++;
                        totalItemRemaining--;
                        items += '<tr>' +
                            '<td>' + counter +'</td>' +
                            '<td>' + value['product_part_number'] + '</td>' +
                            '<td>' + value['product_name'] + '</td>' +
                            '<td >' + value['quantity'] + '</td>' +
                            '<td >' + value['price'] + '</td>' +
                            '<td >' + value['total'] + '</td>' +
                            '<td>' + value['remark'] + '</td>' +
                            '</tr>';
                    });
                    if (totalItemRemaining > 0) {
                        for (let i = 1; i <= totalItemRemaining; i++) {
                            items += '<tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
                        }
                    }
                    items += '<tr id="tfoot">\n' +
                        '       <td colspan="4"><strong style="font-size: 16px; font-weight: 600;"><center>Thank You For Your Business !</center></strong></td>\n' +
                        '       <td><strong style="font-size: 16px; font-weight: 600">Grand Total</strong></td>\n' +
                        '       <td colspan="2"><strong id="invoice_print_total" style="font-size: 16px;font-weight: 600"></strong></td>\n' +
                        '   </tr>';
                    $("#invoice_print_invoice_information").html(invoiceInfo);
                    $("#otherInfo").html(otherInfo);
                    $("#invoice_client_info").html(customerInfo);
                    $("#invoice_print_table_body").html(items);
                    $("#invoice_print_total").html(response['invoice']['total']);
                },
                error: function (xhr, status, error) {
                    $('[type="submit"]').removeAttr('disabled');
                    let data = JSON.parse(xhr.responseText);
                    let errors = '';
                    $.each(data.errors, function(index, error) {
                        errors += error + '<br/>';
                    });
                    displayToastr(null, errors, 'error', null);
                }
            });
        }
    </script>
@endsection
