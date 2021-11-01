<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Invoice;
use App\InvoiceItem;
use App\Product;
use App\Transaction;
use Illuminate\Http\Request;
use DataTables;

class QuotationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function viewQuotations()
    {
        return view('quotations.index');
    }

    public function viewQuotationsDataTable()
    {
        $quotationTotal = InvoiceItem::select([
            'invoice_id',
            \DB::raw('SUM(quantity * price) total')
        ])->groupBy('invoice_id');

        $quotations = Invoice::select([
            'invoices.id',
            'invoices.invoice_type_id',
            'invoices.quotation_number',
            'invoices.rfq_number',
            'customers.name',
            'customers.phone',
            'quotation_total.total',
            'invoices.currency',
            'invoices.date',
        ])->joinSub($quotationTotal, 'quotation_total', function($join) {
            $join->on('quotation_total.invoice_id', 'invoices.id');
        })->join('customers', 'customers.id', 'invoices.customer_id')
            ->whereNotNull('invoices.quotation_number')
            ->orderBy('invoices.id', 'desc');

        return Datatables::of($quotations)
            ->editColumn('total', function ($quotations) {
                return $quotations->currency . ' ' . number_format($quotations->total, 2);
            })->addColumn('actions', function ($quotations) {
                $button = <<<HTML
<div class="btn-group">
    <button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Actions
        <i class="fa fa-angle-down"></i>
    </button>
    <ul class="dropdown-menu pull-left" role="menu">
HTML;
                $button .= "<li><a href='javascript:void(0);' onclick=\"showQuotationItems('$quotations->id')\"><i class='fa fa-info'></i>Show Quotation Items</a></li>
<li><a href='javascript:void(0);' onclick=\"showPrintQuotationModal('$quotations->id')\"><i class='fa fa-print'></i>Print Quotation</a></li>";
                if ($quotations->invoice_type_id == 2) {
                    $button .= "<li><a href='javascript:void(0);' onclick=\"showSaleQuotationModal('$quotations->id')\"><i class='fa fa-balance-scale'></i>Sale Quotation</a></li>";
                    if (\Auth::user()->hasRole('Admin')) {
                        $button .= "<li><a href='javascript:void(0);' onclick=\"showEditQuotationModal('$quotations->id')\"><i class='fa fa-edit'></i>Edit Quotation</a></li>
 <li><a href='javascript:void(0);' onclick=\"deleteRecord('$quotations->id')\"><i class='fa fa-trash'></i>Delete Quotation</a></li>";
                    }
                }
                return $button;
            })->rawColumns(['actions'])->make(true);
    }

    public function newQuotationPost(Request $request)
    {
        $this->validate($request, [
            'customer_id'       => 'required|numeric',
            'rfq_number'        => 'required|string',
            'quotationData'     => 'required|string',
            'currency'          => 'nullable|string',
            'date'              => 'required|date'
        ]);

        if ($request['customer_id'] == -1) {
            $this->validate($request, [
                'customer_name'     => 'required|string',
                'customer_phone'    => 'required|numeric|digits_between:10,14',
            ]);

            $customer = Customer::where('name', $request['customer_name'])
                ->where('phone', $request['customer_phone'])
                ->where('customer_type_id', 1)->first();

            if (!$customer) {
                $customer = new Customer();
                $customer->customer_type_id = 1;
                $customer->name = $request['customer_name'];
                $customer->phone = $request['customer_phone'];
                $customer->save();
            }
        } else {
            $customer = Customer::find($request['customer_id']);
            if (!$customer) {
                return \Response::json([
                    'type' => 'error',
                    'message' => 'The selected customer is invalid.'
                ]);
            }
        }

        $quotationData = json_decode($request['quotationData'], true);

        foreach ($quotationData['productIds'] as $item) {
            if (Product::where('id', $item)->count() == 0) {
                return \Response::json([
                    'type' => 'error',
                    'message' => 'One of the selected products is invalid.'
                ]);
            }
        }

        $quotation = new Invoice();
        $quotation->customer_id = $customer->id;
        $quotation->payment_type_id = $request['payment_type_id'];
        $quotation->invoice_type_id = 2;
        $quotation->currency = $request['currency'];
        $quotation->quotation_number = Invoice::max('quotation_number') + 1;
        $quotation->rfq_number = $request['rfq_number'];
        $quotation->date = date('Y-m-d', strtotime($request['date']));

        \DB::transaction(function () use ($quotation, $quotationData, $request) {
            $quotation->save();
            foreach ($quotationData['productIds'] as $index => $item) {
                $quotationItem = new InvoiceItem();
                $quotationItem->invoice_id = $quotation->id;
                $quotationItem->product_id = $item;
                $quotationItem->quantity = $quotationData['quantities'][$index];
                $quotationItem->price = $quotationData['prices'][$index];
                $quotationItem->remark = $quotationData['remarks'][$index];
                $quotationItem->save();
            }
        });

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'Quotation generated successfully. You can print the quotation from quotations list.',
                'form_clean' => true,
                'modal' => 'newQuotationModal',
                'script' => 'dataTable.ajax.reload();
                                $("#quotation_registration_customer_name").val("").trigger("change");
                            $(".products").off("change");
                            $(".products").val("").trigger("change");
                            $("#log").empty(); productsInit();'
            ]);
        }

        return redirect()->route('quotations-view');
    }

    public function deleteQuotationPost(Request $request)
    {
        $quotation = Invoice::findOrFail($request['id']);

        foreach (InvoiceItem::where('invoice_id', $quotation->id)->get('id') as $quotationItem) {
            $stock = InvoiceItem::findOrFail($quotationItem->id);
            $stock->forceDelete();
        }

        $quotation->forceDelete();

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'Selected quotation has been deleted successfully.',
                'form_clean' => true,
                'modal' => 'quotationDeleteModal',
                'script' => 'dataTable.ajax.reload();$(".tooltips").tooltip();'
            ]);
        }

        return redirect()->route('products-view');
    }

    public function quotationItemsDataTable($quotation_id)
    {
        $quotationItems = InvoiceItem::select([
            'products.name',
            'products.part_number',
            'invoice_items.quantity',
            'invoice_items.price'
        ])->join('products', 'products.id', 'invoice_items.product_id')
            ->where('invoice_items.invoice_id', $quotation_id);

        return Datatables::of($quotationItems)
            ->editColumn('price', function ($quotationItems) {
                return number_format($quotationItems->price, 2);
            })
            ->addColumn('total', function ($quotationItems) {
                return number_format($quotationItems->quantity * $quotationItems->price, 2);
            })->make(true);
    }

    public function quotationPrint(Request $request)
    {
        $this->validate($request, [
            'quotation_id' => 'required|numeric'
        ]);

        $quotation = Invoice::with([
            'customer',
            'paymentType'
        ])->where('id', $request['quotation_id'])->firstOrFail();

        $quotationItems = InvoiceItem::with([
            'product'
        ])->where('invoice_id', $quotation->id)->get();

        $quotationForPrint = [
            'customer' => [
                'Name' => $quotation->customer->name,
                //'Focal Point' => $quotation->customer->focal_point_person,
                'Address' => $quotation->customer->address,
                'Phone' => $quotation->customer->phone,
                //'TIN #' => $quotation->customer->TIN_number,
                //'License #' => $quotation->customer->license_number,
               // 'Registration #' => $quotation->customer->registration_number
            ],
            'quotation' => [
              'RFQ #' => $quotation->rfq_number != NULL ? $quotation->rfq_number : '',

            ],
            'otherInfo' => [
                'Quotation #' => $quotation->quotation_number,
                'RFQ #' => $quotation->rfq_number != NULL ? $quotation->rfq_number : '',
                'Date' => $quotation->date

    ]

        ];

        $total = 0;
        foreach ($quotationItems as $item) {
            $total += $item->quantity * $item->price;
            $quotationForPrint['items'][] = [
                'name' => $item->product->name . ' (' . $item->product->part_number . ')',
                'product_name' => $item->product->name,
                'product_part_number' => $item->product->part_number,
                'quantity' => $item->quantity,
                'price' => $quotation->currency . ' ' . number_format($item->price, 2),
                'total' => $quotation->currency . ' ' . number_format($item->quantity * $item->price, 2),
                'remark' => $item->remark != NULL ? $item->remark : ''
            ];
        }
        $quotationForPrint['quotation']['Total'] = $quotation->currency . ' ' . number_format($total, 2);

        return response()->json($quotationForPrint);
    }

    public function quotationDetailsForSale(Request $request)
    {
        $quotation = Invoice::where('id', $request['quotation_id'])
            ->where('invoice_type_id', 2)
            ->firstOrFail();

        $quotationCustomer = Customer::findOrFail($quotation->customer_id);
        $quotationItems = InvoiceItem::with([
            'product'
        ])->where('invoice_id', $quotation->id)->get();

        $quotationDetail = [
            'invoice_id' => $quotation->id,
            'customer_name' => $quotationCustomer->name,
            'customer_phone' => $quotationCustomer->phone
        ];

        foreach ($quotationItems as $item) {
            $quotationDetail['items'][] = [
                'product_id' => $item->product_id,
                'product_name' => $item->product->part_number . ' (' . $item->product->name . ')',
                'quantity' => $item->quantity,
                'price' => $item->price
            ];
        }

        return response()->json($quotationDetail);
    }

    public function sellQuotationPost(Request $request)
    {
        $this->validate($request, [
            'invoice_id'        => 'required|numeric',
            'date'              => 'required|date',
            'order_number'      => 'nullable|string',
            'order_date'        => 'nullable|date',
            'payment_type_id'   => 'required|numeric'
        ]);

        if ($request['payment_type_id'] == 3)
            $this->validate($request, [
                'amount_paid' => 'required'
            ]);

        $invoice = Invoice::findOrFail($request['invoice_id']);
        $invoice->payment_type_id = $request['payment_type_id'];
        $invoice->invoice_type_id = 1;
        $invoice->order_number = $request['order_number'];
        $invoice->order_date = $request['order_date'] != NULL ? date('Y-m-d', strtotime($request['order_date'])) : NULL;
        $invoice->date = date('Y-m-d', strtotime($request['date']));
        $invoice->save();

        if ($request['payment_type_id'] != 1) {
            $invoiceItems = InvoiceItem::where('invoice_id', $invoice->id)->get();
            $total = 0;
            foreach ($invoiceItems as $item) {
                $invoiceItem = InvoiceItem::findOrFail($item->id);
                $total += $invoiceItem->quantity * $invoiceItem->price;
            }

            $transaction = new Transaction();
            $transaction->customer_id = $invoice->customer_id;
            $transaction->transaction_type_id = 2;
            $transaction->deal_type_id = 1;
            $transaction->invoice_id = $invoice->id;
            $transaction->amount = $request['payment_type_id'] == 3 ? $total - $request['amount_paid'] : $total;
            $transaction->description = 'Remaining amount from invoice #' . $invoice->id;
            $transaction->date = date('Y-m-d', strtotime($request['date']));
            $transaction->save();
        }

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'Quotation changed to invoice. You can print the invoice from invoices list.',
                'form_clean' => true,
                'redirect' => route('invoices-view')
            ]);
        }

        return redirect()->route('invoices-view');
    }

    public function quotationDetails(Request $request)
    {
        $this->validate($request, [
            'invoice_id' => 'required|numeric'
        ]);

        $invoice = Invoice::with([
            'customer',
            'invoiceItems',
            'invoiceItems.product',
            'transaction'
        ])->select([
            'id',
            'customer_id',
            'payment_type_id',
            'date',
            'currency',
            'quotation_number',
            'rfq_number'
        ])->where('id', $request['invoice_id'])->firstOrFail();

        $invoiceData = [
            'id' => $invoice->id,
            'customer_name' => $invoice->customer->name . ' (' . $invoice->customer->phone . ')',
            'customer_id' => $invoice->customer_id,
            'payment_type_id' => $invoice->payment_type_id,
            'currency' => $invoice->currency,
            'date' => $invoice->date,
            'quotation_number' => $invoice->quotation_number,
            'rfq_number' => $invoice->rfq_number
        ];

        foreach ($invoice->invoiceItems as $item) {
            $invoiceData['items'][] = [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->part_number . ' (' . $item->product->name . ')',
                'quantity' => $item->quantity,
                'price' => $item->price,
                'remark' => $item->remark
            ];
        }

        return response()->json($invoiceData);
    }

    public function editQuotationPost(Request $request)
    {
        $this->validate($request, [
            'invoice_id'        => 'required|numeric',
            'customer_id'       => 'required|numeric',
            'currency'          => 'nullable|string',
            'rfq_number'        => 'required|string',
            'quotationData'     => 'required|string',
            'date'              => 'required|date'
        ]);

        $customer = Customer::find($request['customer_id']);
        if (!$customer) {
            return \Response::json([
                'type' => 'error',
                'message' => 'The selected customer is invalid.'
            ]);
        }

        $quotationData = json_decode($request['quotationData'], true);

        foreach ($quotationData['productIds'] as $index => $item) {
            if (Product::where('id', $item)->count() == 0) {
                return \Response::json([
                    'type' => 'error',
                    'message' => 'One of the selected products is invalid.'
                ]);
            }
        }

        $invoice = Invoice::where('invoice_type_id', 2)
            ->where('id', $request['invoice_id'])
            ->firstOrFail();
        $invoice->customer_id = $customer->id;
        $invoice->currency = $request['currency'];
        $invoice->rfq_number = $request['rfq_number'];
        $invoice->date = date('Y-m-d', strtotime($request['date']));

        \DB::transaction(function () use ($invoice, $quotationData, $request) {
            $invoice->save();
            foreach ($quotationData['productIds'] as $index => $item) {
                $invoiceItem = InvoiceItem::find($quotationData['quotationItemIds'][$index]);
                if (!$invoiceItem) {
                    $invoiceItem = new InvoiceItem();
                }
                $invoiceItem->invoice_id = $invoice->id;
                $invoiceItem->product_id = $item;
                $invoiceItem->quantity = $quotationData['quantities'][$index];
                $invoiceItem->price = $quotationData['prices'][$index];
                $invoiceItem->remark = $quotationData['remarks'][$index];
                $invoiceItem->save();
                if (!InvoiceItem::find($quotationData['quotationItemIds'][$index])) {
                    $quotationData['quotationItemIds'][$index] = $invoiceItem->id;
                }
            }

            foreach (InvoiceItem::where('invoice_id', $request['invoice_id'])->whereNotIn('id', $quotationData['quotationItemIds'])->select('id')->get() as $item) {
                $invoiceItem = InvoiceItem::findOrFail($item->id);
                $invoiceItem->forceDelete();
            }
        });

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'Quotation successfully edited',
                'form_clean' => true,
                'modal' => 'editQuotationModal',
                'script' => 'dataTable.ajax.reload();'
            ]);
        }

        return redirect()->route('invoices-view');
    }
}
