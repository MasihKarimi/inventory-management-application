<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Transaction;
use App\TransactionView;
use Illuminate\Http\Request;
use DataTables;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function viewTransactions()
    {
        return view('transactions.index');
    }

    public function viewTransactionsDataTable()
    {
        $balancePerCustomers = Transaction::select([
            'customer_id',
            \DB::raw('CASE WHEN transaction_type_id = 1 THEN SUM(amount) END as credit'),
            \DB::raw('CASE WHEN transaction_type_id = 2 THEN SUM(amount) END as debit')
        ])->groupBy( ['customer_id', 'transaction_type_id']);

        $customersTransaction = Customer::select([
            'customers.id',
            'customers.name',
            'customers.phone',
            \DB::raw('COALESCE(SUM(balance.credit), 0) as credit'),
            \DB::raw('COALESCE(SUM(balance.debit), 0) as debit'),
            \DB::raw('(COALESCE(SUM(balance.credit), 0) - COALESCE(SUM(balance.debit), 0)) as balance')
        ])->joinSub($balancePerCustomers, 'balance', function($join) {
            $join->on('balance.customer_id', 'customers.id');
        })->groupBy(['customers.id', 'customers.name', 'customers.phone',])
            ->orderBy('balance');

        return Datatables::of($customersTransaction)
            ->editColumn('balance', function ($customersTransaction) {
                if ($customersTransaction->balance > 0)
                    return 'Cr ' . number_format($customersTransaction->balance, 2);
                elseif ($customersTransaction->balance < 0)
                    return 'Dr ' . number_format($customersTransaction->balance, 2);
                else
                    return $customersTransaction->balance;
            })->addColumn('actions', function ($customersTransaction) {
                return "<button onclick=\"customerTransactions('$customersTransaction->id')\" class='btn btn-xs btn-info tooltips' data-original-title='List customer transactions'><i class='fa fa-info'></i></button>";
            })->rawColumns(['actions'])->make(true);
    }

    public function newTransactionPost(Request $request)
    {
        $this->validate($request, [
            'customer_id'           => 'required|numeric',
            'deal_type_id'          => 'required|numeric',
            'amount'                => 'required|numeric',
            'date'                  => 'required|date',
            'description'           => 'string|nullable'
        ]);

        $transaction = new Transaction();
        $transaction->customer_id = $request['customer_id'];
        $transaction->transaction_type_id = $request['deal_type_id'] == 3 ? 1 : 2;
        $transaction->deal_type_id = $request['deal_type_id'];
        $transaction->amount = $request['amount'];
        $transaction->date = date('Y-m-d', strtotime($request['date']));
        $transaction->description = $request['description'];
        $transaction->save();

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'New transaction has been successfully added.',
                'form_clean' => true,
                'modal' => 'transactionRegisterModal',
                'script' => 'dataTable.ajax.reload();$(".tooltips").tooltip();'
            ]);
        }

        return redirect()->route('transactions-view');
    }

    public function customerTransactionsDataTable($customer_id)
    {
        $customerTransactions = TransactionView::where('customer_id', $customer_id)
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc');

        return Datatables::of($customerTransactions)
            ->editColumn('balance', function ($customerTransactions) {
                if ($customerTransactions->balance > 0)
                    return 'Cr ' . number_format($customerTransactions->balance, 2);
                elseif ($customerTransactions->balance < 0)
                    return 'Dr ' . number_format($customerTransactions->balance, 2);
                else
                    return $customerTransactions->balance;
            })->addColumn('actions', function ($customerTransactions) {
                $button = '';
                if (\Auth::user()->hasRole('Admin')) {
                    $button = "<button onclick=\"deleteTransactionRecord('$customerTransactions->id')\" class='btn btn-xs btn-danger tooltips' data-original-title='Delete transaction'><i class='fa fa-trash'></i></button>";
                }
                return $button;
            })->rawColumns(['actions'])->make(true);
    }

    public function deleteTransactionPost(Request $request)
    {
        $transaction = Transaction::findOrFail($request['id']);

        $transaction->forceDelete();

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'Selected transaction has been deleted successfully.',
                'form_clean' => true,
                'modal' => 'transactionDeleteModal',
                'script' => 'customerTransactions(' . $transaction->customer_id . ');'
            ]);
        }

        return redirect()->route('customers-view');
    }
}
