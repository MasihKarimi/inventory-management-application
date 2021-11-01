<?php

namespace App\Http\Controllers;

use App\Expense;
use App\ExpenseType;
use Illuminate\Http\Request;
use DataTables;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function viewExpenses()
    {
        return view('expenses.index');
    }

    public function viewExpensesDataTable()
    {
        $expenses = Expense::select([
            'expenses.id',
            'expense_types.name as type',
            'expenses.expense_by',
            'expenses.amount',
            'expenses.date',
            \DB::Raw('SUBSTRING(expenses.remark, 1, 50) as remark')
        ])->join('expense_types', 'expense_types.id', 'expenses.expense_type_id')
            ->orderBy('expenses.id', 'desc');

        return Datatables::of($expenses)
            ->addColumn('actions', function ($expenses) {
                $button = '';
                if (\Auth::user()->hasRole('Admin')) {
                    $button .= "<button onclick=\"deleteRecord('$expenses->id')\" class='btn btn-xs btn-danger tooltips' data-original-title='Delete expense'><i class='fa fa-trash'></i></button>";
                }
                return $button;
            })->rawColumns(['actions'])->make(true);
    }

    public function addNewExpensePost(Request $request)
    {
        $this->validate($request, [
            'expense_type_id'   => 'required|numeric',
            'expense_by'        => 'required|string',
            'amount'            => 'numeric|required',
            'date'              => 'date|required',
            'remark'            => 'string|nullable'
        ]);

        $expense = new Expense();
        $expense->expense_type_id = $request['expense_type_id'];
        $expense->expense_by = $request['expense_by'];
        $expense->amount = $request['amount'];
        $expense->date = $request['date'];
        $expense->remark = $request['remark'];
        $expense->save();

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'New expense added successfully.',
                'form_clean' => true,
                'modal' => 'expenseRegisterModal',
                'script' => 'dataTable.ajax.reload();$(".tooltips").tooltip();'
            ]);
        }

        return redirect()->route('expenses-view');
    }

    public function deleteExpensePost(Request $request)
    {
        $expense = Expense::findOrFail($request['expense_id']);
        $expense->forceDelete();

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'Selected expense has been deleted successfully.',
                'form_clean' => true,
                'modal' => 'expenseDeleteModal',
                'script' => 'dataTable.ajax.reload();$(".tooltips").tooltip();'
            ]);
        }

        return redirect()->route('expenses-view');
    }

    public function addNewExpenseTypePost(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $expenseType = new ExpenseType();
        $expenseType->name = $request['name'];
        $expenseType->save();

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'New expense type has been added successfully.',
                'form_clean' => true,
                'modal' => 'expenseTypeRegisterModal',
                'refresh' => true
            ]);
        }

        return redirect()->route('expenses-view');
    }

    public function deleteExpenseTypePost(Request $request)
    {
        $expenseType = ExpenseType::findOrFail($request['id']);

        if (Expense::where('expense_type_id', $expenseType->id)->count() > 0) {
            return \Response::json([
                'type' => 'error',
                'message' => 'Expenses are recorded for the selected expense type, so the expense type is not deletable.',
                'modal' => 'expenseTypeDeleteModal'
            ]);
        }

        $expenseType->forceDelete();

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'Selected expense type has been deleted successfully.',
                'form_clean' => true,
                'modal' => 'expenseTypeDeleteModal',
                'refresh' => true
            ]);
        }

        return redirect()->route('expenses-view');
    }
}
