<?php

namespace App\Http\Controllers;

use App\Customer;
use App\InvoiceItem;
use App\Product;
use App\Stock;
use App\Transaction;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $numberOfCustomers = Customer::count();
        $numberOfProducts = Product::count();

        $soldItems = InvoiceItem::select([
            'invoice_items.product_id',
            \DB::Raw('SUM(invoice_items.quantity) as quantity')
        ])->join('invoices', 'invoices.id', 'invoice_items.invoice_id')
            ->where('invoices.invoice_type_id', 1)
            ->groupBy(['invoice_items.product_id']);

        $stockItems = Stock::select([
            'product_id',
            \DB::Raw('SUM(quantity) as quantity')
        ])->groupBy(['product_id']);

        $numberOfProductsOutOfStock = Product::leftJoinSub($soldItems, 'sold', function($join) {
            $join->on('sold.product_id', 'products.id');
        })->leftJoinSub($stockItems, 'stock', function($join) {
            $join->on('stock.product_id', 'products.id');
        })->whereRaw('(coalesce(stock.quantity, 0) - coalesce(sold.quantity, 0)) <= 0')->count();

        $overallBalance = Transaction::select([
            \DB::raw('SUM(CASE WHEN transaction_type_id = 1 THEN amount END) as debit'),
            \DB::raw('SUM(CASE WHEN transaction_type_id = 2 THEN amount END) as credit')
        ])->first();
        $overallBalance = $overallBalance->credit - $overallBalance->debit;

        return view('home')
            ->with('numberOfCustomers', $numberOfCustomers)
            ->with('numberOfProducts', $numberOfProducts)
            ->with('numberOfProductsOutOfStock', $numberOfProductsOutOfStock)
            ->with('overallBalance', $overallBalance);
    }
}
