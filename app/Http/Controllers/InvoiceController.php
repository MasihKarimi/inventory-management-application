<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Invoice;
use App\InvoiceItem;
use App\Product;
use App\Transaction;
use Illuminate\Http\Request;
use DataTables;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function viewInvoices()
    {
        return view('invoices.index');
    }

    public function viewInvoicesDataTable()
    {
        $invoiceTotal = InvoiceItem::select([
            'invoice_id',
            \DB::raw('SUM(quantity * price) total')
        ])->groupBy('invoice_id');

        $invoices = Invoice::select([
            'invoices.id',
            'customers.name',
            'customers.phone',
            'payment_types.name as payment_type',
            'invoice_total.total',
            'invoices.order_number',
            'invoices.order_date',
            'invoices.date',
        ])->join('payment_types', 'payment_types.id', 'invoices.payment_type_id')
            ->joinSub($invoiceTotal, 'invoice_total', function($join) {
                $join->on('invoice_total.invoice_id', 'invoices.id');
        })->join('customers', 'customers.id', 'invoices.customer_id')
            ->where('invoices.invoice_type_id', 1)
            ->orderBy('invoices.id', 'desc');

        return Datatables::of($invoices)
            ->editColumn('total', function ($invoices) {
                return number_format($invoices->total);
            })->addColumn('actions', function ($invoices) {
                $button = <<<HTML
<div class="btn-group">
    <button class="btn btn-xs green dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"> Actions
        <i class="fa fa-angle-down"></i>
    </button>
    <ul class="dropdown-menu pull-left" role="menu">
HTML;
                $button .= "<li><a href='javascript:void(0);' onclick=\"showInvoiceItems('$invoices->id')\"><i class='fa fa-info'></i>Show Invoice Items</a></li>
<li><a href='javascript:void(0);' onclick=\"showPrintInvoiceModal('$invoices->id')\"><i class='fa fa-print'></i>Print Invoice</a></li>
<li><a href='javascript:void(0);' onclick=\"showPrintDeliveryNoteModal('$invoices->id')\"><i class='fa fa-print'></i>Print Delivery Note</a></li>";
                if (\Auth::user()->hasRole('Admin')) {
                    $button .= "<li><a href='javascript:void(0);' onclick=\"showEditInvoiceModal('$invoices->id')\"><i class='fa fa-edit'></i>Edit Invoice</a></li>
 <li><a href='javascript:void(0);' onclick=\"deleteRecord('$invoices->id')\"><i class='fa fa-trash'></i>Delete Invoice</a></li>";
                }
                $button .= "</ul></div>";
                return $button;
            })->rawColumns(['actions'])->make(true);
    }

    public function newInvoicePost(Request $request)
    {
        $this->validate($request, [
            'customer_id'       => 'required|numeric',
            'invoiceData'       => 'required|string',
            'date'              => 'required|date',
            'order_number'      => 'nullable|numeric',
            'order_date'        => 'nullable|date',
            'payment_type_id'   => 'required|numeric'
        ]);

        if ($request['payment_type_id'] == 3)
            $this->validate($request, [
                'amount_paid' => 'required'
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

        $invoiceData = json_decode($request['invoiceData'], true);

        foreach ($invoiceData['productIds'] as $item) {
            if (Product::where('id', $item)->count() == 0) {
                return \Response::json([
                    'type' => 'error',
                    'message' => 'One of the selected products is invalid.'
                ]);
            }
        }

        $invoice = new Invoice();
        $invoice->customer_id = $customer->id;
        $invoice->payment_type_id = $request['payment_type_id'];
        $invoice->invoice_type_id = 1;
        $invoice->order_number = $request['order_number'];
        $invoice->order_date = $request['order_date'] != NULL ? date('Y-m-d', strtotime($request['order_date'])) : NULL;
        $invoice->date = date('Y-m-d', strtotime($request['date']));

        \DB::transaction(function () use ($invoice, $invoiceData, $request) {
            $invoice->save();
            $total = 0;
            foreach ($invoiceData['productIds'] as $index => $item) {
                $invoiceItem = new InvoiceItem();
                $invoiceItem->invoice_id = $invoice->id;
                $invoiceItem->product_id = $item;
                $invoiceItem->quantity = $invoiceData['quantities'][$index];
                $invoiceItem->price = $invoiceData['prices'][$index];
                $invoiceItem->remark = $invoiceData['remarks'][$index];
                $invoiceItem->save();

                $total += $invoiceData['quantities'][$index] * $invoiceData['prices'][$index];
            }

            if ($request['payment_type_id'] != 1) {
                $transaction = new Transaction();
                $transaction->customer_id = $request['customer_id'];
                $transaction->transaction_type_id = 2;
                $transaction->deal_type_id = 1;
                $transaction->invoice_id = $invoice->id;
                $transaction->amount = $request['payment_type_id'] == 3 ? $total - $request['amount_paid'] : $total;
                $transaction->description = 'Remaining amount from invoice #' . $invoice->id;
                $transaction->date = date('Y-m-d', strtotime($request['date']));
                $transaction->save();
            }
        });

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'Invoice/Sale saved. You can print the invoice from invoices list.',
                'form_clean' => true,
                'modal' => 'newInvoiceModal',
                'script' => 'dataTable.ajax.reload();$("a[data-repeater-delete]").click();
                            $("#invoice_registration_customer_name").val("").trigger("change");
                            $(".products").off("change");
                            $(".products").val("").trigger("change");
                            undoPay(); $("#log").empty(); productsInit();'
            ]);
        }

        return redirect()->route('invoices-view');
    }
    public function deleteInvoicePost(Request $request)
    {
        $invoice = Invoice::findOrFail($request['id']);

        foreach (InvoiceItem::where('invoice_id', $invoice->id)->get('id') as $invoiceItem) {
            $stock = InvoiceItem::findOrFail($invoiceItem->id);
            $stock->forceDelete();
        }

        $transaction = Transaction::where('invoice_id', $invoice->id)->first();
        if ($transaction)
            $transaction->forceDelete();

        $invoice->forceDelete();

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'Selected invoice has been deleted successfully.',
                'form_clean' => true,
                'modal' => 'invoiceDeleteModal',
                'script' => 'dataTable.ajax.reload();$(".tooltips").tooltip();'
            ]);
        }

        return redirect()->route('products-view');
    }

    public function invoiceItemsDataTable($invoice_id)
    {
        $invoiceItems = InvoiceItem::select([
            'products.name',
            'products.part_number',
            'invoice_items.quantity',
            'invoice_items.price'
        ])->join('products', 'products.id', 'invoice_items.product_id')
            ->where('invoice_items.invoice_id', $invoice_id);

        return Datatables::of($invoiceItems)
            ->editColumn('price', function ($invoiceItems) {
                return number_format($invoiceItems->price);
            })->addColumn('total', function ($invoiceItems) {
                return number_format($invoiceItems->quantity * $invoiceItems->price);
            })->make(true);
    }

    public function invoicePrint(Request $request)
    {
        $this->validate($request, [
            'invoice_id' => 'required|numeric'
        ]);

        $invoice = Invoice::with([
            'customer',
            'paymentType'
        ])->where('id', $request['invoice_id'])
            ->where('invoice_type_id', 1)
            ->firstOrFail();

        $invoiceItems = InvoiceItem::with([
            'product'
        ])->where('invoice_id', $invoice->id)
            ->get();

        $invoiceForPrint = [
            'customer' => [
                'Name' => $invoice->customer->name,
                'Focal Point' => $invoice->customer->focal_point_person,
                'Address' => $invoice->customer->address,
                'Phone' => $invoice->customer->phone,
                'TIN #' => $invoice->customer->TIN_number,
                'License #' => $invoice->customer->license_number,
                'Registration #' => $invoice->customer->registration_number
            ],
            'invoice' => [
                'Invoice #' => $invoice->id,
                'Payment Type' => $invoice->paymentType->name,
                'Po No' => $invoice->order_number ? $invoice->order_number : '',
                'Date' => $invoice->date
            ],
            'order_date' => $invoice->order_date
        ];

        $total = 0;
        foreach ($invoiceItems as $item) {
            $total += $item->quantity * $item->price;
            $invoiceForPrint['items'][] = [
                'name' => $item->product->name . ' (' . $item->product->part_number . ')',
                'product_name' => $item->product->name,
                'product_part_number' => $item->product->part_number,
                'quantity' => $item->quantity,
                'price' => __('app.invoice_currency') . number_format($item->price),
                'total' => __('app.invoice_currency') . number_format($item->quantity * $item->price),
                'remark' => $item->remark != NULL ? $item->remark : ''
            ];
        }
        $invoiceForPrint['invoice']['total'] = __('app.invoice_currency') . number_format($total);

        return response()->json($invoiceForPrint);
    }

    public function invoiceDetails(Request $request)
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
            'order_date',
            'order_number'
        ])->where('id', $request['invoice_id'])->firstOrFail();

        $invoiceData = [
            'id' => $invoice->id,
            'customer_name' => $invoice->customer->name . ' (' . $invoice->customer->phone . ')',
            'customer_id' => $invoice->customer_id,
            'payment_type_id' => $invoice->payment_type_id,
            'date' => $invoice->date,
            'order_date' => $invoice->order_date,
            'order_number' => $invoice->order_number,
            'amount_paid' => $invoice->invoiceItems->sum(function ($item) {
                return $item->quantity * $item->price;
            }) - (isset($invoice->transaction) ? $invoice->transaction->amount : 0)
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

    public function editInvoicePost(Request $request)
    {
        $this->validate($request, [
            'invoice_id'        => 'required|numeric',
            'customer_id'       => 'required|numeric',
            'invoiceData'       => 'required|string',
            'date'              => 'required|date',
            'order_number'      => 'nullable|numeric',
            'order_date'        => 'nullable|date',
            'payment_type_id'   => 'required|numeric'
        ]);

        $customer = Customer::find($request['customer_id']);
        if (!$customer) {
            return \Response::json([
                'type' => 'error',
                'message' => 'The selected customer is invalid.'
            ]);
        }

        $invoiceData = json_decode($request['invoiceData'], true);

        $total = 0;
        foreach ($invoiceData['productIds'] as $index => $item) {
            if (Product::where('id', $item)->count() == 0) {
                return \Response::json([
                    'type' => 'error',
                    'message' => 'One of the selected products is invalid.'
                ]);
            }
            $total += $invoiceData['quantities'][$index] * $invoiceData['prices'][$index];
        }

        if ($request['payment_type_id'] == 3)
            $this->validate($request, [
                'amount_paid' => 'required|lt:' . $total
            ]);

        $invoice = Invoice::findOrFail($request['invoice_id']);
        $invoice->customer_id = $customer->id;
        $invoice->payment_type_id = $request['payment_type_id'];
        $invoice->invoice_type_id = 1;
        $invoice->order_number = $request['order_number'];
        $invoice->order_date = $request['order_date'] != NULL ? date('Y-m-d', strtotime($request['order_date'])) : NULL;
        $invoice->date = date('Y-m-d', strtotime($request['date']));

        \DB::transaction(function () use ($invoice, $invoiceData, $request, $total) {
            $invoice->save();
            foreach ($invoiceData['productIds'] as $index => $item) {
                $invoiceItem = InvoiceItem::find($invoiceData['invoiceItemIds'][$index]);
                if (!$invoiceItem) {
                    $invoiceItem = new InvoiceItem();
                }
                $invoiceItem->invoice_id = $invoice->id;
                $invoiceItem->product_id = $item;
                $invoiceItem->quantity = $invoiceData['quantities'][$index];
                $invoiceItem->price = $invoiceData['prices'][$index];
                $invoiceItem->remark = $invoiceData['remarks'][$index];
                $invoiceItem->save();
                if (!InvoiceItem::find($invoiceData['invoiceItemIds'][$index])) {
                    $invoiceData['invoiceItemIds'][$index] = $invoiceItem->id;
                }
            }

            foreach (InvoiceItem::where('invoice_id', $request['invoice_id'])->whereNotIn('id', $invoiceData['invoiceItemIds'])->select('id')->get() as $item) {
                $invoiceItem = InvoiceItem::findOrFail($item->id);
                $invoiceItem->forceDelete();
            }

            if ($request['payment_type_id'] != 1) {
                $transaction = Transaction::where('invoice_id', $request['invoice_id'])->first();
                if (!$transaction) {
                    $transaction = new Transaction();
                }
                $transaction->customer_id = $request['customer_id'];
                $transaction->transaction_type_id = 2;
                $transaction->deal_type_id = 1;
                $transaction->invoice_id = $invoice->id;
                $transaction->amount = $request['payment_type_id'] == 3 ? $total - $request['amount_paid'] : $total;
                $transaction->description = 'Remaining amount from invoice #' . $invoice->id;
                $transaction->date = date('Y-m-d', strtotime($request['date']));
                $transaction->save();
            } else {
                $transaction = Transaction::where('invoice_id', $request['invoice_id'])->first();
                if ($transaction) $transaction->forceDelete();
            }
        });

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'Invoice/Sale edited',
                'form_clean' => true,
                'modal' => 'editInvoiceModal',
                'script' => 'dataTable.ajax.reload();'
            ]);
        }

        return redirect()->route('invoices-view');
    }
}
