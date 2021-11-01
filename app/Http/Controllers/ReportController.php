<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Expense;
use App\Transaction;
use App\TransactionView;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\File;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function expensesView()
    {
        return view('reports.expenses');
    }

    public function expensesGenerate(Request $request)
    {
        $this->validate($request, [
            'start_date'        => 'required|date',
            'end_date'          => 'required|date',
            'expense_type_id'   => 'required'
        ]);

        $expenses = Expense::select([
            'expenses.id',
            'expense_types.name as type',
            'expenses.expense_by',
            'expenses.amount',
            'expenses.date',
            'expenses.remark'
        ])->join('expense_types', 'expense_types.id', 'expenses.expense_type_id')
            ->whereBetween('expenses.date', [$request['start_date'], $request['end_date']])
            ->whereIn('expense_types.id', $request['expense_type_id'])
            ->orderBy('expenses.date', 'desc')
            ->get();

        $report = [
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date']
        ];

        $total = 0;
        foreach ($expenses as $item) {
            $total += $item->amount;
            $report['items'][] = [
                'date' => $item->date,
                'type' => $item->type,
                'by' => $item->expense_by,
                'remark' => $item->remark,
                'amount' => __('app.invoice_currency') . number_format($item->amount, 2)
            ];
        }

        $report['total'] = __('app.invoice_currency') . number_format($total, 2);

        return response()->json($report);
    }

    public function expensesExport(Request $request)
    {
        $this->validate($request, [
            'form' => 'required|json'
        ]);

        $form = json_decode($request['form'], true);
        $request['start_date'] = $form['start_date'];
        $request['end_date'] = $form['end_date'];
        $request['expense_type_id'] = $form['expense_type_id'];

        $this->validate($request, [
            'start_date'        => 'required|date',
            'end_date'          => 'required|date',
            'expense_type_id'   => 'required'
        ]);

        $expenses = Expense::select([
            'expenses.id',
            'expense_types.name as type',
            'expenses.expense_by',
            'expenses.amount',
            'expenses.date',
            'expenses.remark'
        ])->join('expense_types', 'expense_types.id', 'expenses.expense_type_id')
            ->whereIn('expense_types.id', $request['expense_type_id'])
            ->orderBy('expenses.date', 'desc')
            ->get();

        $reader = new Xlsx();
        $spreadSheet = $reader->load(storage_path('templates/expenses_report.xlsx'));
        $activeSheet = $spreadSheet->getActiveSheet();
        $activeSheet->setCellValue('A1', __('app.name'));

        $currentRow = 5; $total = 0;
        foreach ($expenses as $row => $expense) {
            $currentRow = $row + 5;
            $activeSheet->setCellValue('A' . $currentRow, $expense->date)
                ->setCellValue('B' . $currentRow, $expense->type)
                ->setCellValue('C' . $currentRow, $expense->expense_by)
                ->setCellValue('D' . $currentRow, $expense->remark)
                ->setCellValue('E' . $currentRow, __('app.invoice_currency') . number_format($expense->amount, 2));
            $total += $expense->amount;
        }
        $activeSheet->setCellValue('A' . ($currentRow + 1), 'Total')
            ->setCellValue('E' . ($currentRow + 1), __('app.invoice_currency') . number_format($total, 2));

        File::setUseUploadTempDirectory(true);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Expenses Report.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');
        $writer->save('php://output');
    }

    public function transactionsView()
    {
        return view('reports.transactions');
    }

    public function transactionsGenerate(Request $request)
    {
        $this->validate($request, [
            'start_date'    => 'required|date',
            'end_date'      => 'required|date',
            'customer_id'   => 'required|numeric'
        ]);

        if ($request['customer_id'] != -1) {
            $customer = Customer::findOrFail($request['customer_id']);

            $transactions = TransactionView::select([
                'date',
                'description',
                'deal_type',
                \DB::raw('CASE WHEN type = "Credit" THEN amount ELSE 0 END AS credit'),
                \DB::raw('CASE WHEN type = "Debit" THEN amount ELSE 0 END AS debit'),
                'balance'
            ])->where('customer_id', $customer->id)
                ->whereBetween('date', [$request['start_date'], $request['end_date']])
                ->orderBy('date')
                ->orderBy('id')
                ->get();

            $report = [
                'type' => 'customer',
                'start_date' => $request['start_date'],
                'end_date' => $request['end_date'],
                'customer' => $customer->name . ' (' . $customer->phone . ')',
                'opening_balance' => $transactions->count() ? ($transactions->first()->balance > 0 ? 'Cr ' . number_format($transactions->first()->balance, 2) : 'Dr ' . number_format($transactions->first()->balance, 2)) : 0,
                'closing_balance' => $transactions->count() ? ($transactions->last()->balance > 0 ? 'Cr ' . number_format($transactions->last()->balance, 2) : 'Dr ' . number_format($transactions->last()->balance, 2)) : 0
            ];

            foreach ($transactions as $key => $item) {
                $report['items'][] = [
                    'number' => $key + 1,
                    'date' => $item->date,
                    'description' => $item->description != NULL ? $item->description : '',
                    'deal_type' => $item->deal_type,
                    'credit' => number_format($item->credit, 2),
                    'debit' => number_format($item->debit, 2),
                    'balance' => $item->balance > 0 ? 'Cr ' . number_format($item->balance, 2) : ($item->balance < 0 ? 'Dr ' . number_format($item->balance, 0) : $item->balance)
                ];
            }
        } else {
            $balancePerCustomers = Transaction::select([
                'customer_id',
                \DB::raw('CASE WHEN transaction_type_id = 1 THEN SUM(amount) END as credit'),
                \DB::raw('CASE WHEN transaction_type_id = 2 THEN SUM(amount) END as debit')
            ])->whereBetween('date', [$request['start_date'], $request['end_date']])
                ->groupBy( ['customer_id', 'transaction_type_id']);

            $transactions = Customer::select([
                'customers.id',
                'customers.name',
                'customers.phone',
                \DB::raw('COALESCE(SUM(balance.credit), 0) as credit'),
                \DB::raw('COALESCE(SUM(balance.debit), 0) as debit'),
                \DB::raw('(COALESCE(SUM(balance.credit), 0) - COALESCE(SUM(balance.debit), 0)) as balance')
            ])->leftJoinSub($balancePerCustomers, 'balance', function($join) {
                $join->on('balance.customer_id', 'customers.id');
            })->groupBy(['customers.id', 'customers.name', 'customers.phone',])
                ->orderBy('balance')
                ->get();

            $report = [
                'type' => 'all_customers',
                'start_date' => $request['start_date'],
                'end_date' => $request['end_date']
            ];

            foreach ($transactions as $key => $item) {
                $report['items'][] = [
                    'number' => $key + 1,
                    'customer' => $item->name . ' (' . $item->phone . ')',
                    'credit' => number_format($item->credit, 2),
                    'debit' => number_format($item->debit, 2),
                    'balance' => $item->balance > 0 ? 'Cr ' . number_format($item->balance, 2) : ($item->balance < 0 ? 'Dr ' . number_format($item->balance, 2) : $item->balance)
                ];
            }
        }

        return response()->json($report);
    }

    public function transactionsExport(Request $request)
    {
        $this->validate($request, [
            'form' => 'required|json'
        ]);

        $form = json_decode($request['form'], true);
        $request['start_date'] = $form['start_date'];
        $request['end_date'] = $form['end_date'];
        $request['customer_id'] = $form['customer_id'];

        $this->validate($request, [
            'start_date'    => 'required|date',
            'end_date'      => 'required|date',
            'customer_id'   => 'required|numeric'
        ]);

        $reader = new Xlsx();
        $spreadSheet = $reader->load(storage_path('templates/transactions_report.xlsx'));
        $activeSheet = $spreadSheet->getActiveSheet();
        $activeSheet->setCellValue('A1', __('app.name'));

        $activeSheet->setCellValue('A3', 'From ' . $request['start_date'] . ' To ' . $request['end_date']);
        if ($request['customer_id'] != -1) {
            $customer = Customer::findOrFail($request['customer_id']);
            $activeSheet->setCellValue('A4', 'Customer: ' . $customer->name . ' (' . $customer->phone . ')');
            $transactions = TransactionView::select([
                'date',
                'description',
                'deal_type',
                \DB::raw('CASE WHEN type = "Credit" THEN amount ELSE 0 END AS credit'),
                \DB::raw('CASE WHEN type = "Debit" THEN amount ELSE 0 END AS debit'),
                'balance'
            ])->where('customer_id', $customer->id)
                ->whereBetween('date', [$request['start_date'], $request['end_date']])
                ->orderBy('date')
                ->orderBy('id')
                ->get();
        } else {
            $activeSheet->setCellValue('A4', 'All Customers Report');
            $balancePerCustomers = Transaction::select([
                'customer_id',
                \DB::raw('CASE WHEN transaction_type_id = 1 THEN SUM(amount) END as credit'),
                \DB::raw('CASE WHEN transaction_type_id = 2 THEN SUM(amount) END as debit')
            ])->whereBetween('date', [$request['start_date'], $request['end_date']])
                ->groupBy( ['customer_id', 'transaction_type_id']);

            $transactions = Customer::select([
                'customers.id',
                'customers.name',
                'customers.phone',
                \DB::raw('COALESCE(SUM(balance.credit), 0) as credit'),
                \DB::raw('COALESCE(SUM(balance.debit), 0) as debit'),
                \DB::raw('(COALESCE(SUM(balance.credit), 0) - COALESCE(SUM(balance.debit), 0)) as balance')
            ])->leftJoinSub($balancePerCustomers, 'balance', function($join) {
                $join->on('balance.customer_id', 'customers.id');
            })->groupBy(['customers.id', 'customers.name', 'customers.phone',])
                ->orderBy('balance')
                ->get();

            $activeSheet->removeColumn('C')->removeColumn('C');
            $activeSheet->setCellValue('A5', 'Customer');
            $activeSheet->getColumnDimension('B')->setWidth(30);
            $activeSheet->getColumnDimension('C')->setWidth(8);
        }

        $currentRow = 6;
        foreach ($transactions as $row => $transaction) {
            if ($request['customer_id'] != -1) {
                $activeSheet->setCellValue('A' . $currentRow, ($row + 1))
                    ->setCellValue('B' . $currentRow, $transaction->date)
                    ->setCellValue('C' . $currentRow, $transaction->description)
                    ->setCellValue('D' . $currentRow, $transaction->deal_type)
                    ->setCellValue('E' . $currentRow, number_format($transaction->credit, 2))
                    ->setCellValue('F' . $currentRow, number_format($transaction->debit, 2))
                    ->setCellValue('G' . $currentRow, number_format($transaction->balance, 2));
            } else {
                $activeSheet->setCellValue('A' . $currentRow, ($row + 1))
                    ->setCellValue('B' . $currentRow, $transaction->name . ' (' . $transaction->phone . ')')
                    ->setCellValue('C' . $currentRow, number_format($transaction->credit, 2))
                    ->setCellValue('D' . $currentRow, number_format($transaction->debit, 2))
                    ->setCellValue('E' . $currentRow,
                        $transaction->balance > 0 ? 'Cr ' . number_format($transaction->balance, 2) :
                            ($transaction->balance < 0 ? 'Dr ' . number_format($transaction->balance, 2) : $transaction->balance));
            }
            $currentRow++;
        }

        if ($request['customer_id'] != -1) {
            $activeSheet->mergeCells('E' . $currentRow . ':' . 'F' . $currentRow);
            $activeSheet->setCellValue('E' . $currentRow, 'Opening Balance')
                ->setCellValue('G' . $currentRow, $transactions->count() ? ($transactions->first()->balance > 0 ? 'Cr ' . $transactions->first()->balance : 'Dr ' . $transactions->first()->balance) : 0);
            $currentRow++;
            $activeSheet->mergeCells('E' . $currentRow . ':' . 'F' . $currentRow);
            $activeSheet->setCellValue('E' . $currentRow , 'Closing Balance')
                ->setCellValue('G' . $currentRow, $transactions->count() ? ($transactions->last()->balance > 0 ? 'Cr ' . $transactions->last()->balance : 'Dr ' . $transactions->last()->balance) : 0);
        }

        File::setUseUploadTempDirectory(true);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Transactions Report.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');
        $writer->save('php://output');
    }
}
