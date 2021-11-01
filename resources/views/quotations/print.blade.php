<style type="text/css" media="print">
    body * {
        visibility: hidden;
    }
    #quotation_printing_area, #quotation_printing_area * {
        visibility: visible;
    }
    #quotation_printing_area {
        margin-top: -60px;
        position: absolute;
        left: 0;
        top: 0;
    }
    #quotation_printing_area tr th {
        padding: 6px;
        font-size: 14px !important;
        font-weight: 600;
        text-align: center !important;
        -webkit-print-color-adjust: exact;
        background-color: black !important;
        color: white !important;
    }
    #quotation_printing_area tr td {
        padding: 4px;
        font-size: 12px !important;
        font-weight: 600;
    }
    #quotation_printing_area h3 {
        font-size: 18px !important;
    }

    #quotation_printing_area th, #quotation_printing_area td {
        vertical-align: middle !important;
    }

    #quotation_printing_area span {
        font-size: 20px;
        font-weight: 600;
        text-align: right !important;
    }
    #test{
        margin-top: 200px;
        margin-right: 0 !important;
        margin-left: 0 !important;
    }
    #quotation_printing_area th, #quotation_printing_area td {
        vertical-align: middle !important;
    }

    .col-xs-4{
        border-top: 1px solid #656565;
        border-bottom: 1px solid #656565;;
        padding: 0 !important;
        margin: 0 !important;
        height: 105px;
    }
    #invoice-payment{
        padding-left: 50px;
        padding-top: 10px;
    }
    .col-xs-5{
        border: 1px solid #656565;;
        padding-right: 0 !important;
        margin-right: 0 !important;
        height: 105px;
    }
    .col-xs-3{
        border: 1px solid #656565;
        padding-left: 0 !important;
        margin-left: 0 !important;
        height: 105px;
    }
    #invoice_print_invoice_information{
        padding-left: 10px;
        padding-top: 15px;
    }
    #invoice_client_info{
        padding-bottom: 10px;
    }
    #quotation{
        padding-left: 20px;
        padding-top: 10px;
    }
    #quotation_print_quotation_information{
        padding-left: 20px;
        padding-top: 10px;
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
    #quotation_printing_area th, #quotation_printing_area td {
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

</style>
<div id="printQuotationModal" data-target="" class="modal fade modal-scroll bs-modal-lg" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Print Quotation</h4>
            </div>
            <div class="modal-body">
                <div class="invoice" id="quotation_printing_area">
                    <div class="row" id="test">
                        <div class="col-xs-5">
                            <span>Customer</span>
                            <ul class="list-unstyled" id="quotation_client_info" style="font-size: 12px;"></ul>
                        </div>
                        <div class="col-xs-4 ">
                            <div id="quotation">
                          <span id="center">Quotation</span>
                                <br>
                            <div  id="otherInfo" style="font-size: 12px;">

                            </div>
                            </div>
                        </div>

                        <div class="col-xs-3" >
                            <div style="font-size: 12px;" id="quotation_print_quotation_information"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th rowspan="2"> NO </th>
                                    <th style="width: 20%">Part #</th>
                                    <th style="width: 30%">Name</th>
                                    <th rowspan="2" style="width: 5%"> Qty </th>
                                    <th rowspan="2" style="width: 15%"> Unit Cost </th>
                                    <th rowspan="2" style="width: 15%"> Total </th>
                                    <th rowspan="2" style="width: 15%"> Remarks </th>
                                </tr>
                                </thead>
                                <tbody id="quotation_print_table_body"></tbody>
                                <tfoot>
                                <tr id="tfoot">
                                    <td colspan="4"> <strong style="font-size: 16px;font-weight: 600;"  ><center> Thank You For Your Business ! </center></strong></td>
                                    <td><strong>Grand Total</strong></td>
                                    <td colspan="2"><strong id="quotation_print_total"></strong></td>
                                </tr>
                                </tfoot>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn green" onclick="window.print();">
                    <i class="fa fa-print"></i>Print
                </button>
                <button data-dismiss="modal" class="btn default">Cancel</button>
            </div>
        </div>
    </div>
</div>

@section('extraJS')
    @parent
    <script>
        function showPrintQuotationModal(quotationId) {
            let url = "{{ route('quotation-print', 0) }}";
            url = url.replace('0', quotationId);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'POST',
                url: url,
                data: { quotation_id: quotationId },
                success: function (response, status, xhr, $form) {
                    $("#printQuotationModal").modal('show');
                    let customerInfo = '', invoiceInfo = '', otherInfo = '', items = '', counter = 0; let totalItemRemaining = 20;
                    $.each(response['customer'], function (index, value) {
                        let val = value === null ? '' : value;
                        customerInfo += '<li><strong>' + index + ':</strong> ' + val + '</li>';
                    });
                    $.each(response['quotation'], function (index, value) {
                        if (index === 'Quotation #') value = '{{ __('app.invoice_prefix') }}' + value;
                        invoiceInfo += '<strong>' + index + ':</strong> ' + value + ' <br/> ';
                    });
                    $.each(response['otherInfo'], function (index, value) {
                        if (index === 'Quotation #') value = '{{ __('app.invoice_prefix') }}' + value;
                        otherInfo += '<strong>' + index + ':</strong> ' + value + ' <br/> ';
                    });
                    $.each(response['items'], function (index, value) {
                        counter++;
                        totalItemRemaining--;
                        items += '<tr>' +
                            '<td>' + counter +'</td>' +
                            '<td>' + value['product_part_number'] + '</td>' +
                            '<td>' + value['product_name'] + '</td>' +
                            '<td>' + value['quantity'] + '</td>' +
                            '<td>' + value['price'] + '</td>' +
                            '<td>' + value['total'] + '</td>' +
                            '<td>' + value['remark'] + '</td>' +
                            '</tr>';
                    });
                    if (totalItemRemaining > 0) {
                        for (let i = 1; i <= totalItemRemaining; i++) {
                            items += '<tr><td>&nbsp;</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
                        }
                    }
                    $("#quotation_print_quotation_information").html(invoiceInfo);
                    $("#otherInfo").html(otherInfo);
                    $("#quotation_client_info").html(customerInfo);
                    $("#quotation_print_table_body").html(items);
                    $("#quotation_print_total").html(response['quotation']['Total']);
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
