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

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function viewProducts()
    {
        return view('products.index');
    }

    public function viewProductsDataTable()
    {
        $soldItems = InvoiceItem::select([
            'invoice_items.product_id',
            \DB::Raw('SUM(invoice_items.quantity) as quantity')
        ])->join('invoices', 'invoices.id', 'invoice_items.invoice_id')
            ->where('invoices.invoice_type_id', 1)
            ->groupBy(['invoice_items.product_id']);

        $stockItems = Product::select([
            'products.id as product_id',
            \DB::Raw('SUM(stocks.quantity) as quantity'),
            \DB::raw('MAX(CONCAT(CASE WHEN purchases.date IS NOT NULL THEN purchases.date ELSE 1 END, CASE WHEN purchases.date IS NOT NULL THEN stocks.id ELSE 1 END)) date_id')
        ])->leftJoin('stocks', 'stocks.product_id', 'products.id')
            ->leftJoin('purchases', 'purchases.id', 'stocks.purchase_id')
            ->groupBy(['products.id']);

        $stockLatestPrices = Product::select([
            'products.id as product_id',
            'stocks.net_price',
            'stocks.sale_price',
            \DB::Raw('(COALESCE(latest_stock.quantity, 0) - COALESCE(sold.quantity, 0)) as quantity')
        ])->leftJoin('stocks', 'stocks.product_id', 'products.id')
            ->leftJoin('purchases', 'purchases.id', 'stocks.purchase_id')
            ->joinSub($stockItems, 'latest_stock', function($join) {
                $join->on('latest_stock.product_id', 'products.id');
                $join->on('latest_stock.date_id', \DB::raw('CASE WHEN latest_stock.date_id <> 11 THEN CONCAT(purchases.date, stocks.id) ELSE 11 END'));
            })->leftJoinSub($soldItems, 'sold', function ($join) {
                $join->on('sold.product_id', 'products.id');
            });

        $products = Product::select([
            'products.id',
            'products.name',
            'products.part_number',
            'latest_prices.net_price',
            'latest_prices.sale_price',
            'latest_prices.quantity'
        ])->leftJoinSub($stockLatestPrices, 'latest_prices', function($join) {
            $join->on('latest_prices.product_id', 'products.id');
        })->orderBy('products.id', 'desc');

        return Datatables::of($products)
            ->addColumn('actions', function ($products) {
                $button = "<button onclick=\"showProductStocks('$products->id')\" class='btn btn-xs btn-info tooltips' data-original-title='Show product stocks log'><i class='fa fa-info'></i></button>";
                if (\Auth::user()->hasRole('Admin')) {
                    $button .= "<button onclick=\"showProductEditModal('$products->id')\" class='btn btn-xs btn-success tooltips' data-original-title='Edit product'><i class='fa fa-edit'></i></button>";
                    $button .= "<button onclick=\"deleteRecord('$products->id')\" class='btn btn-xs btn-danger tooltips' data-original-title='Delete the product'><i class='fa fa-trash'></i></button>";
                }
                return $button;
            })->rawColumns(['actions'])->make(true);
    }

    public function registerNewProductPost(Request $request)
    {
        $this->validate($request, [
            'name'          => 'required',
            'part_number'   => 'required'
        ]);

        if (Product::where('part_number', $request['part_number'])->count() > 0) {
            return \Response::json([
                'type' => 'error',
                'message' => 'The part number already registered.'
            ]);
        }

        $product = new Product();
        $product->name = $request['name'];
        $product->part_number = $request['part_number'];
        $product->save();

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'New product registered successfully.',
                'form_clean' => true,
                'modal' => 'productRegisterModal',
                'script' => 'dataTable.ajax.reload();'
            ]);
        }

        return redirect()->route('products-view');
    }

    public function deleteProductPost(Request $request)
    {
        $product = Product::findOrFail($request['id']);

        if (InvoiceItem::where('product_id', $product->id)->count() > 0) {
            return \Response::json([
                'type' => 'error',
                'message' => 'Invoices generated for the selected product, so the product is not deletable.',
                'modal' => 'productDeleteModal'
            ]);
        }

        foreach (Stock::where('product_id', $product->id)->get() as $stockItem) {
            if ($stockItem->purchase->payment_type_id != 1) {
                $deductionAmount = $stockItem->quantity * $stockItem->net_price;
                $transaction = Transaction::where('purchase_id', $stockItem->purchase->id)->first();
                if ($transaction) {
                    // In case the transaction associated with the product is credit for customer then the deleted
                    // product related stocks entry price should be deducted from the credit of customer, and if
                    // the transaction is debit for the customer then the product stock entry price should be added to
                    // the transaction amount.
                    // In case the deduction make the transaction amount below zero then the transaction type will
                    // be changed to debit for customer.
                    if ($transaction->transaction_type_id == 1) {
                        if (($transaction->amount - $deductionAmount) > 0) {
                            $transaction->amount = $transaction->amount - $deductionAmount;
                        } else {
                            $transaction->amount = $deductionAmount - $transaction->amount;
                            $transaction->transaction_type_id = 2;
                        }
                    } else {
                        $transaction->amount = $transaction->amount + $deductionAmount;
                    }

                    if($transaction->amount == 0) {
                        $transaction->forceDelete();
                    } else {
                        $description = $transaction->description . ' ' . 'Amount ' . $deductionAmount . ' is ' .
                            ($transaction->transaction_type_id == 1 ? 'deducted' : 'added') .
                            ' to transaction amount because of the ' . $product->name . '(' . $product->part_number . ') product deletion.';
                        $transaction->description = $description;
                        $transaction->save();
                    }
                }
            }
            $stock = Stock::findOrFail($stockItem->id);
            $stock->forceDelete();
        }

        $product->forceDelete();

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'Selected product has been deleted successfully.',
                'form_clean' => true,
                'modal' => 'productDeleteModal',
                'script' => 'dataTable.ajax.reload();$(".tooltips").tooltip();'
            ]);
        }

        return redirect()->route('products-view');
    }

    public function getProductDetails($id)
    {
        $soldQuantity = InvoiceItem::select([
            'invoice_items.product_id',
            \DB::Raw('SUM(invoice_items.quantity) as quantity')
        ])->where('invoice_items.product_id', $id)
            ->groupBy(['invoice_items.product_id']);

        $productStock = Stock::select([
            'product_id',
            \DB::Raw('SUM(quantity) as quantity')
        ])->where('product_id', $id)
            ->groupBy(['product_id']);

        $productInformation = Stock::select([
            'stocks.product_id',
            'stocks.net_price',
            'stocks.sale_price'
        ])->leftJoin('purchases', 'purchases.id', 'stocks.purchase_id')
            ->where('stocks.product_id', $id)
            ->latest(\DB::raw('CONCAT(purchases.date, stocks.id)'))
            ->skip(0)
            ->take(1);

        $product = Product::select([
            'products.id',
            'products.name',
            'products.part_number',
            'stock_information.net_price',
            'stock_information.sale_price',
            \DB::Raw('(coalesce(stock.quantity, 0) - coalesce(sold.quantity, 0)) as quantity')
        ])->leftJoinSub($soldQuantity, 'sold', function($join) {
            $join->on('sold.product_id', 'products.id');
        })->leftJoinSub($productStock, 'stock', function($join) {
            $join->on('stock.product_id', 'products.id');
        })->leftJoinSub($productInformation, 'stock_information', function ($join) {
            $join->on('stock_information.product_id', 'products.id');
        })->where('products.id', $id)->first();

        return response()->json([
            'name' => $product->name . ' (' . $product->part_number . ')',
            'net_price' => $product->net_price,
            'sale_price' => $product->sale_price,
            'quantity' => $product->quantity
        ]);
    }

    public function productSearch()
    {
        $products = Product::select([
            'id',
            \DB::raw("CONCAT(part_number, ' (', name, ')') as name")
        ])->where(function ($query) {
            $query->where('name', 'LIKE', '%' . request()->get('term') . '%')
                ->orWhere('part_number', 'LIKE', '%' . request()->get('term') . '%');
        });

        if (request()->get('excludeIds')) {
            $products = $products->whereNotIn('id', request()->get('excludeIds'));
        }

        $products = $products->paginate(10);
        $result = array();
        if ((request()->get('page') == 1 || request()->get('page') == null) && request()->get('new_product'))
            $result['results'][] = ['id' => -1, 'text' => 'New Product'];

        foreach ($products as $product) {
            $result['results'][] = ['id' => $product->id, 'text' => $product->name];
        }
        $result['pagination'] = ['more' => !($products->lastPage() == request()->get('page'))];

        return response()->json($result);
    }

    public function productStocksDataTable($product_id)
    {
        $productStocks = Stock::select([
            'stocks.id',
            'purchases.date',
            'customers.name',
            'payment_types.name as type',
            'purchases.reference',
            'stocks.quantity',
            'stocks.net_price',
            'stocks.sale_price',
        ])->join('purchases', 'purchases.id', 'stocks.purchase_id')
            ->join('products', 'products.id', 'stocks.product_id')
            ->leftJoin('customers', 'customers.id', 'purchases.customer_id')
            ->leftJoin('payment_types', 'payment_types.id', 'purchases.payment_type_id')
            ->where('stocks.product_id', $product_id);

        return Datatables::of($productStocks)
            ->addColumn('net_total', function ($productStocks) {
                return $productStocks->quantity * $productStocks->net_price;
            })->addColumn('actions', function ($productStocks) {
                $button = '';
                if (\Auth::user()->hasRole('Admin')) {
                    //$button .= "<button onclick=\"editStockRecord('$productStocks->id')\" class='btn btn-xs btn-success tooltips' data-original-title='Edit stock'><i class='fa fa-edit'></i></button>";
                    $button .= "<button onclick=\"deleteStockRecord('$productStocks->id')\" class='btn btn-xs btn-danger tooltips' data-original-title='Delete stock'><i class='fa fa-trash'></i></button>";
                }
                return $button;
            })->rawColumns(['actions'])->make(true);
    }

    public function deleteStockPost(Request $request)
    {
        $stock = Stock::where('id', $request['id'])
            ->with([
                'product',
                'purchase'
            ])->firstOrFail();

        if ($stock->purchase->payment_type_id != 1) {
            $deductionAmount = $stock->quantity * $stock->net_price;
            $transaction = Transaction::where('purchase_id', $stock->purchase->id)->first();
            if ($transaction) {
                // In case the transaction associated with the product is credit for customer then the deleted
                // product related stocks entry price should be deducted from the credit of customer, and if
                // the transaction is debit for the customer then the product stock entry price should be added to
                // the transaction amount.
                // In case the deduction make the transaction amount below zero then the transaction type will
                // be changed to debit for customer.
                if ($transaction->transaction_type_id == 1) {
                    if (($transaction->amount - $deductionAmount) > 0) {
                        $transaction->amount = $transaction->amount - $deductionAmount;
                    } else {
                        $transaction->amount = $deductionAmount - $transaction->amount;
                        $transaction->transaction_type_id = 2;
                    }
                } else {
                    $transaction->amount = $transaction->amount + $deductionAmount;
                }

                if ($transaction->amount == 0) {
                    $transaction->forceDelete();
                } else {
                    $description = $transaction->description . ' ' . 'Amount ' . $deductionAmount . ' is ' .
                        ($transaction->transaction_type_id == 1 ? 'deducted' : 'added') .
                        ' to transaction amount because of the ' . $stock->product->name . '(' . $stock->product->part_number . ') stocks deletion.';
                    $transaction->description = $description;
                    $transaction->save();
                }
            }
        }
        $stock->forceDelete();

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'Selected stock entry has been deleted successfully.',
                'form_clean' => true,
                'modal' => 'stockDeleteModal',
                'script' => 'productStocksDataTable.ajax.reload();$(".tooltips").tooltip();'
            ]);
        }

        return redirect()->route('products-view');
    }

    public function getProductInformation(Request $request)
    {
        $this->validate($request, [
            'product_id' => 'required|numeric'
        ]);

        $product = Product::findOrFail($request['product_id']);

        return response()->json($product->toArray());
    }

    public function updateProduct(Request $request)
    {
        $this->validate($request, [
            'product_id'    => 'required|numeric',
            'name'          => 'required',
            'part_number'   => 'required',
        ]);

        $product = Product::findOrFail($request['product_id']);
        $product->name = $request['name'];
        $product->part_number = $request['part_number'];
        $product->save();

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'Product\'s information updated successfully.',
                'form_clean' => true,
                'modal' => 'productEditModal',
                'script' => 'dataTable.ajax.reload();$(".tooltips").tooltip();'
            ]);
        }

        return redirect()->route('products-view');
    }

    public function getProductStockInformation(Request $request)
    {
        $this->validate($request, [
            'stock_id' => 'required|numeric'
        ]);

        $stock = Stock::with([
            'product',
            'purchase',
            'purchase.customer:id,name,phone'
        ])->where('id', $request['stock_id'])
            ->firstOrFail();

        return response()->json($stock->toArray());
    }

    public function updateProductStock(Request $request)
    {
        $this->validate($request, [
            'stock_id'          => 'required|numeric',
            'customer_id'       => 'required|numeric',
            'product_id'        => 'required|numeric',
            'date'              => 'required|date',
            'reference'         => 'string|nullable',
            'quantity'          => 'required|numeric',
            'net_price'         => 'required|numeric',
            'sale_price'        => 'required|numeric'
        ]);

        $stock = Stock::find($request['stock_id']);
        if (!$stock) {
            return \Response::json([
                'type' => 'error',
                'message' => 'The selected stock item is invalid.'
            ]);
        }

        $customer = Customer::find($request['customer_id']);
        if (!$customer) {
            return \Response::json([
                'type' => 'error',
                'message' => 'The selected customer is invalid.'
            ]);
        }

        $product = Product::find($request['product_id']);
        if (!$product) {
            return \Response::json([
                'type' => 'error',
                'message' => 'The selected product is invalid.'
            ]);
        }

        $purchase = Purchase::with([
            'stocks' => function($query) {
                $query->select(['id', 'purchase_id', \DB::raw('(net_price * quantity) as amount')]);
            }
        ])->where('id', $stock->purchase_id)
            ->firstOrFail();
        $total = $purchase->stocks->sum('amount');

        $purchase->customer_id = $request['customer_id'];
        $purchase->date = date('Y-m-d', strtotime($request['date']));
        $purchase->reference = $request['reference'];
        $purchase->save();

        $stock->product_id = $request['product_id'];
        $stock->quantity = $request['quantity'];
        $stock->net_price = $request['net_price'];
        $stock->sale_price = $request['sale_price'];
        $stock->save();

        $transaction = Transaction::where('purchase_id', $purchase->id)->first();
        if ($transaction) {
            $amountPaidOld = $total - $transaction->amount;

            $purchase = Purchase::with([
                'stocks' => function($query) {
                    $query->select(['id', 'purchase_id', \DB::raw('(net_price * quantity) as amount')]);
                }
            ])->where('id', $stock->purchase_id)
                ->firstOrFail();
            if ($purchase->stocks->sum('amount') != $total) {
                $transaction->amount = $purchase->stocks->sum('amount') - $amountPaidOld;
                $transaction->save();
            }
        }

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'Stock item updated successfully.',
                'form_clean' => true,
                'modal' => 'stockEditModal',
                'script' => 'productStocksDataTable.ajax.reload();'
            ]);
        }

        return redirect()->route('products-view');
    }
}
