<style type="text/css" media="print">
    body * {
        visibility: hidden;
    }
    #invoice_printing_area, #invoice_printing_area * {
        visibility: visible;
    }
    #invoice_printing_area {
        margin-top: -60px;
        position: absolute;
        left: 0;
        top: 0;
    }
    #invoice_printing_area tr th {
        padding: 6px;
        font-size: 12px !important;
    }
    #invoice_printing_area tr td {
        padding: 4px;
        font-size: 10px !important;
    }
    #invoice_printing_area h3 {
        font-size: 18px !important;
    }
    #invoice_printing_area th, #invoice_printing_area td {
        text-align: center !important;
        vertical-align: middle !important;
    }
</style>
<style>
    #invoice_printing_area th, #invoice_printing_area td {
        text-align: center !important;
        vertical-align: middle !important;
    }
</style>
<div id="printInvoiceModal" data-target="" class="modal fade modal-scroll bs-modal-lg" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Print Invoice</h4>
            </div>
            <div class="modal-body">
                <div class="invoice" id="invoice_printing_area">
                    <div class="row">
                        <div class="col-xs-4">
                            <h3>Customer:</h3>
                            <ul class="list-unstyled" id="invoice_client_info" style="font-size: 12px;"></ul>
                        </div>
                        <div class="col-xs-4 text-center">
                            <img src="{{ asset('logo.png') }}" class="img-responsive" style="margin:0 auto;" alt="{{ __('app.name') }}" />
                            <h4 class="no-margin">Genuine Auto Parts</h4>
                        </div>
                        <div class="col-xs-4 invoice-payment text-right">
                            <h3>Invoice Issued by:</h3>
                            <ul class="list-unstyled font-sm" style="font-size: 12px;">
                                <li><strong>Name:</strong> {{ __('app.name') }}</li>
                                <li><strong>Address:</strong> {{ __('app.address') }}</li>
                                <li><strong>Phone:</strong> {{ __('app.phone1') }}</li>
                                <li>{{ __('app.phone2') }}</li>
                                <li>{{ __('app.phone3') }}</li>
                            </ul>
                        </div>
                        <div class="col-xs-8" id="invoice_print_invoice_information"></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th rowspan="2"> # </th>
                                    <th style="width: 40%" colspan="2"> Item </th>
                                    <th rowspan="2"> Qty </th>
                                    <th rowspan="2"> Unit Cost </th>
                                    <th rowspan="2"> Total </th>
                                    <th rowspan="2"> Remarks </th>
                                </tr>
                                <tr>
                                    <th style="width: 10%">Part #</th>
                                    <th style="width: 30%">Name</th>
                                </tr>
                                </thead>
                                <tbody id="invoice_print_table_body"></tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="4"></td>
                                    <td><strong>Grand Total</strong></td>
                                    <td colspan="2"><strong id="invoice_print_total"></strong></td>
                                </tr>
                                </tfoot>
                            </table>
                            <div class="row margin-top-10">
                                <div class="col-xs-3 text-center">
                                    <h5>Customer Signature</h5>
                                    <hr/>
                                </div>
                                <div class="col-xs-6">
                                    <div class="row">
                                        <div class="col-xs-4">
                                            <img src="{{ asset('theme/images/nissan.jpg') }}" alt="Nissan" class="img-responsive">
                                        </div>
                                        <div class="col-xs-4">
                                            <img src="{{ asset('theme/images/toyota.jpg') }}" alt="Toyoya" class="img-responsive">
                                        </div>
                                        <div class="col-xs-4">
                                            <img src="{{ asset('theme/images/ford.jpg') }}" alt="Ford" class="img-responsive">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-3 text-center">
                                    <h5>Seller Signature</h5>
                                    <hr/>
                                </div>
                            </div>
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
                    let customerInfo = '', invoiceInfo = '', items = '', counter = 0; let totalItemRemaining = 30;
                    $.each(response['customer'], function (index, value) {
                        let val = value === null ? '' : value;
                        customerInfo += '<li><strong>' + index + ':</strong> ' + val + '</li>';
                    });
                    $.each(response['invoice'], function (index, value) {
                        if (index === 'total') return;
                        invoiceInfo += '<strong>' + index + ':</strong> ' + value + ' &nbsp; ';
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
                    $("#invoice_print_invoice_information").html(invoiceInfo);
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
