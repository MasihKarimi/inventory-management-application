<?php

namespace App\Http\Controllers;

use App\Customer;
use App\InvoiceItem;
use App\Product;
use App\Purchase;
use App\Stock;
use App\Transaction;
use Illuminate\Http\Request;
use DataTables;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function viewPurchases()
    {
        return view('purchases.index');
    }

    public function viewPurchasesDataTable()
    {
        $purchaseTotal = Stock::select([
            'purchase_id',
            \DB::raw('SUM(quantity * net_price) total')
        ])->groupBy('purchase_id');

        $purchases = Purchase::select([
            'purchases.id',
            'customers.name',
            'customers.phone',
            'payment_types.name as payment_type',
            'purchases.reference',
            'purchases.date',
            'purchase_total.total'
        ])->join('payment_types', 'payment_types.id', 'purchases.payment_type_id')
            ->joinSub($purchaseTotal, 'purchase_total', function($join) {
                $join->on('purchase_total.purchase_id', 'purchases.id');
            })
            ->join('customers', 'customers.id', 'purchases.customer_id')
            ->orderBy('purchases.id', 'desc');

        return Datatables::of($purchases)
            ->editColumn('total', function ($invoices) {
                return $invoices->currency . ' ' . number_format($invoices->total, 2);
            })
            ->addColumn('actions', function ($purchases) {
                $button = "<button onclick=\"showPurchaseProducts('$purchases->id')\" class='btn btn-xs btn-info tooltips' data-original-title='Show purchased items'><i class='fa fa-info'></i></button>";
                if (\Auth::user()->hasRole('Admin')) {
                    $button .= "<button onclick=\"showEditPurchaseModal('$purchases->id')\" class='btn btn-xs btn-success tooltips' data-original-title='Edit the purchase'><i class='fa fa-edit'></i></button>";
                    $button .= "<button onclick=\"deleteRecord('$purchases->id')\" class='btn btn-xs btn-danger tooltips' data-original-title='Delete the purchase'><i class='fa fa-trash'></i></button>";
                }
                return $button;
            })->rawColumns(['actions'])->make(true);
    }

    public function registerNewPurchasePost(Request $request)
    {
        $this->validate($request, [
            'customer_id'       => 'required|numeric',
            'date'              => 'required|date',
            'reference'         => 'string|nullable'
        ]);

        $customer = Customer::find($request['customer_id']);
        if (!$customer && $request['customer_id'] != -1) {
            return \Response::json([
                'type' => 'error',
                'message' => 'The selected customer is invalid.'
            ]);
        }

        if ($request['customer_id'] != -1) {
            $this->validate($request, [
                'payment_type_id' => 'required|numeric'
            ]);
        }
        else {
            $request['payment_type_id'] = 1;
        }

        if ($request['payment_type_id'] == 3) {
            $this->validate($request, [
                'amount_paid' => 'required|numeric'
            ]);
        }

        $purchases = [];
        foreach ($request['group'] as $items) {
            if ($items['product_id'] && $items['quantity'] && $items['net_price']) {
                if (!Product::find($items['product_id'])) {
                    return \Response::json([
                        'type' => 'error',
                        'message' => 'One of selected products is invalid.'
                    ]);
                }

                $purchases[] = [
                    'product_id' => $items['product_id'],
                    'quantity' => $items['quantity'],
                    'net_price' => $items['net_price'],
                    'sale_price' => $items['sale_price']
                ];
            } else {
                return \Response::json([
                    'type' => 'error',
                    'message' => 'Please enter all the product names, quantities and prices to proceed.'
                ]);
            }
        }

        $purchase = new Purchase();
        $purchase->customer_id = isset($customer->id) ? $customer->id : null;
        $purchase->payment_type_id = $request['payment_type_id'];
        $purchase->reference = $request['reference'];
        $purchase->date = date('Y-m-d', strtotime($request['date']));
        $purchase->save();

        $total = 0;
        foreach ($purchases as $item) {
            $total += $item['net_price'] * $item['quantity'];
            $stock = new Stock();
            $stock->product_id = $item['product_id'];
            $stock->purchase_id = $purchase->id;
            $stock->quantity = $item['quantity'];
            $stock->net_price = $item['net_price'];
            $stock->sale_price = $item['sale_price'];
            $stock->save();
        }

        if ($request['payment_type_id'] != 1 && $request['customer_id'] != -1) {
            $transaction = new Transaction();
            $transaction->customer_id = $request['customer_id'];
            $transaction->purchase_id = $purchase->id;
            $transaction->transaction_type_id = 2;
            $transaction->deal_type_id = 2;
            $transaction->amount = $request['payment_type_id'] == 3 ? $total - $request['amount_paid'] : $total;
            $transaction->description = 'Purchase of products with reference #' . $request['reference'] . '.';
            $transaction->date = date('Y-m-d', strtotime($request['date']));
            $transaction->save();
        }

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'Purchase added successfully.',
                'form_clean' => true,
                'modal' => 'newPurchaseModal',
                'script' => 'dataTable.ajax.reload();purchaseProductsInit();'
            ]);
        }

        return redirect()->route('products-view');
    }

    public function deletePurchasePost(Request $request)
    {
        $purchase = Purchase::findOrFail($request['id']);

        $transaction = Transaction::where('purchase_id', $purchase->id)->first();
        if ($transaction) {
            $transaction->forceDelete();
        }

        foreach (Stock::where('purchase_id', $purchase->id)->get() as $stockItem) {
            $stock = Stock::findOrFail($stockItem->id);
            $stock->forceDelete();
        }

        $purchase->forceDelete();

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'Selected purchase has been deleted successfully.',
                'form_clean' => true,
                'modal' => 'purchaseDeleteModal',
                'script' => 'dataTable.ajax.reload();$(".tooltips").tooltip();'
            ]);
        }

        return redirect()->route('purchase-view');
    }

    public function purchaseProductsDataTable($purchase_id)
    {
        $purchaseProducts = Stock::select([
            'stocks.id',
            'products.name',
            'stocks.quantity',
            'stocks.net_price',
            'stocks.sale_price',
        ])->join('products', 'products.id', 'stocks.product_id')
            ->where('stocks.purchase_id', $purchase_id);

        return Datatables::of($purchaseProducts)
            ->addColumn('net_total', function ($purchaseProducts) {
                return $purchaseProducts->quantity * $purchaseProducts->net_price;
            })->make(true);
    }

    public function purchaseDetails(Request $request)
    {
        $this->validate($request, [
            'purchase_id' => 'required|numeric'
        ]);

        $purchase = Purchase::with([
            'customer',
            'stocks',
            'stocks.product',
            'transaction'
        ])->select([
            'id',
            'customer_id',
            'payment_type_id',
            'reference',
            'date'
        ])->where('id', $request['purchase_id'])->firstOrFail();

        $purchaseData = [
            'id' => $purchase->id,
            'customer_name' => $purchase->customer->name . ' (' . $purchase->customer->phone . ')',
            'customer_id' => $purchase->customer_id,
            'payment_type_id' => $purchase->payment_type_id,
            'reference' => $purchase->reference,
            'date' => $purchase->date,
            'amount_paid' => $purchase->stocks->sum(function ($item) {
                    return $item->quantity * $item->price;
                }) - (isset($purchase->transaction) ? $purchase->transaction->amount : 0)
        ];

        foreach ($purchase->stocks as $item) {
            $purchaseData['items'][] = [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->part_number . ' (' . $item->product->name . ')',
                'quantity' => $item->quantity,
                'net_price' => $item->net_price,
                'sale_price' => $item->sale_price
            ];
        }

        return response()->json($purchaseData);
    }

    public function editPurchasePost(Request $request)
    {
        $this->validate($request, [
            'purchase_id'       => 'required|numeric',
            'customer_id'       => 'required|numeric',
            'purchaseData'      => 'required|string',
            'reference_number'  => 'nullable|string',
            'date'              => 'required|date',
            'payment_type_id'   => 'required|numeric'
        ]);

        $customer = Customer::find($request['customer_id']);
        if (!$customer) {
            return \Response::json([
                'type' => 'error',
                'message' => 'The selected customer is invalid.'
            ]);
        }

        $purchaseData = json_decode($request['purchaseData'], true);

        $total = 0;
        foreach ($purchaseData['productIds'] as $index => $item) {
            if (Product::where('id', $item)->count() == 0) {
                return \Response::json([
                    'type' => 'error',
                    'message' => 'One of the selected products is invalid.'
                ]);
            }
            $total += $purchaseData['quantities'][$index] * $purchaseData['net_prices'][$index];
        }

        if ($request['payment_type_id'] == 3)
            $this->validate($request, [
                'amount_paid' => 'required|lt:' . $total
            ]);

        $purchase = Purchase::findOrFail($request['purchase_id']);
        $purchase->customer_id = $customer->id;
        $purchase->payment_type_id = $request['payment_type_id'];
        $purchase->reference = $request['reference'];
        $purchase->date = date('Y-m-d', strtotime($request['date']));

        \DB::transaction(function () use ($purchase, $purchaseData, $request, $total) {
            $purchase->save();
            foreach ($purchaseData['productIds'] as $index => $item) {
                $purchaseItem = Stock::find($purchaseData['purchaseItemIds'][$index]);
                if (!$purchaseItem) {
                    $purchaseItem = new Stock();
                }
                $purchaseItem->purchase_id = $purchase->id;
                $purchaseItem->product_id = $item;
                $purchaseItem->quantity = $purchaseData['quantities'][$index];
                $purchaseItem->net_price = $purchaseData['net_prices'][$index];
                $purchaseItem->sale_price = $purchaseData['sale_prices'][$index];
                $purchaseItem->save();
                if (!Stock::find($purchaseData['purchaseItemIds'][$index])) {
                    $purchaseData['purchaseItemIds'][$index] = $purchaseItem->id;
                }
            }

            foreach (Stock::where('purchase_id', $request['purchase_id'])->whereNotIn('id', $purchaseData['purchaseItemIds'])->select('id')->get() as $item) {
                $purchaseItem = Stock::findOrFail($item->id);
                $purchaseItem->forceDelete();
            }

            if ($request['payment_type_id'] != 1) {
                $transaction = Transaction::where('purchase_id', $request['purchase_id'])->first();
                if (!$transaction) {
                    $transaction = new Transaction();
                }
                $transaction->customer_id = $request['customer_id'];
                $transaction->transaction_type_id = 2;
                $transaction->deal_type_id = 1;
                $transaction->purchase_id = $purchase->id;
                $transaction->amount = $request['payment_type_id'] == 3 ? $total - $request['amount_paid'] : $total;
                $transaction->description = 'Remaining amount from purchase #' . $purchase->id;
                $transaction->date = date('Y-m-d', strtotime($request['date']));
                $transaction->save();
            } else {
                $transaction = Transaction::where('purchase_id', $request['purchase_id'])->first();
                if ($transaction) $transaction->forceDelete();
            }
        });

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'Purchase edited',
                'form_clean' => true,
                'modal' => 'editPurchaseModal',
                'script' => 'dataTable.ajax.reload();'
            ]);
        }

        return redirect()->route('purchases-view');
    }
}
