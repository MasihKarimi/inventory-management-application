<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Invoice;
use App\Transaction;
use Illuminate\Http\Request;
use DataTables;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function viewCustomers()
    {
        return view('customers.index');
    }

    public function viewCustomersDataTable()
    {
        $customers = Customer::select([
            'customers.id',
            'customer_types.name as type',
            'customers.name',
            'customers.phone',
            'customers.address',
            'customers.focal_point_person',
            'customers.TIN_number',
            'customers.license_number',
            'customers.registration_number'
        ])->join('customer_types', 'customer_types.id', 'customers.customer_type_id')
            ->orderBy('customers.id', 'desc');

        return Datatables::of($customers)
            ->addColumn('actions', function ($customers) {
                $button = '';
                if (\Auth::user()->hasRole('Admin')) {
                    $button .= "<button onclick=\"showCustomerEditModal('$customers->id')\" class='btn btn-xs btn-success tooltips' data-original-title='Edit customer'><i class='fa fa-edit'></i></button>";
                    $button .= "<button onclick=\"deleteRecord('$customers->id')\" class='btn btn-xs btn-danger tooltips' data-original-title='Delete customer'><i class='fa fa-trash'></i></button>";
                }
                return $button;
            })->rawColumns(['actions'])->make(true);
    }

    public function registerNewCustomerPost(Request $request)
    {
        $this->validate($request, [
            'name'                  => 'required',
            'phone'                 => 'numeric|digits_between:10,14|nullable',
            'address'               => 'string|nullable',
            'focal_point_person'    => 'string|nullable',
            'TIN_number'            => 'numeric|nullable',
            'license_number'        => 'string|nullable',
            'registration_number'   => 'string|nullable',
            'customer_type_id'      => 'required|numeric'
        ]);

        $customer = new Customer();
        $customer->customer_type_id = $request['customer_type_id'];
        $customer->name = $request['name'];
        $customer->phone = $request['phone'];
        $customer->address = $request['address'];
        $customer->focal_point_person = $request['focal_point_person'];
        $customer->TIN_number = $request['TIN_number'];
        $customer->license_number = $request['license_number'];
        $customer->registration_number = $request['registration_number'];
        $customer->save();

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'New customer registered successfully.',
                'form_clean' => true,
                'modal' => 'customerRegisterModal',
                'script' => 'dataTable.ajax.reload();$(".tooltips").tooltip();'
            ]);
        }

        return redirect()->route('customers-view');
    }

    public function deleteCustomerPost(Request $request)
    {
        $customer = Customer::findOrFail($request['id']);

        if (Invoice::where('customer_id', $customer->id)->count() > 0) {
            return \Response::json([
                'type' => 'error',
                'message' => 'Invoices generated for the selected customer, so the customer is not deletable.',
                'modal' => 'customerDeleteModal'
            ]);
        }

        if (Transaction::where('customer_id', $customer->id)->count() > 0) {
            return \Response::json([
                'type' => 'error',
                'message' => 'Transactions done with the selected customer, so the customer is not deletable.',
                'modal' => 'customerDeleteModal'
            ]);
        }

        $customer->forceDelete();

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'Selected customer has been deleted successfully.',
                'form_clean' => true,
                'modal' => 'customerDeleteModal',
                'script' => 'dataTable.ajax.reload(); $(".tooltips").tooltip();'
            ]);
        }

        return redirect()->route('customers-view');
    }

    public function getCustomerBalance($id)
    {
        $customer = Customer::findOrFail($id);

        $customerBalance = Transaction::select([
            \DB::raw('CASE WHEN transaction_type_id = 1 THEN SUM(amount) END as credit'),
            \DB::raw('CASE WHEN transaction_type_id = 2 THEN SUM(amount) END as debit')
        ])->where('customer_id', $customer->id)
            ->groupBy('transaction_type_id')
            ->get();

        $balance = 0;
        if ($customerBalance->count()) {
            $balance =  $customerBalance->sum('credit') - $customerBalance->sum('debit');
            $balance = $balance > 0 ? 'Cr ' . $balance : 'Dr ' . $balance;
        }

        return response()->json([
            'balance' => $balance
        ]);
    }

    public function customerSearch()
    {
        $customers = Customer::select([
            'id',
            \DB::raw("CONCAT(name, ' (', COALESCE(phone, 'No Phone Number'), ')') as name")
        ])->where('name', 'LIKE', '%' . request()->get('term') . '%')
            ->paginate(10);

        $result = array();
        if ((request()->get('page') == 1 || request()->get('page') == null)) {
            if (request()->get('one_time_customer'))
                $result['results'][] = ['id' => -1, 'text' => 'One Time Customer'];

            if (request()->get('unknown_customer'))
                $result['results'][] = ['id' => -1, 'text' => 'Unknown'];

            if (request()->get('all_customers_transaction'))
                $result['results'][] = ['id' => -1, 'text' => 'All Customers Transaction'];
        }

        foreach ($customers as $customer) {
            $result['results'][] = ['id' => $customer->id, 'text' => $customer->name];
        }
        $result['pagination'] = ['more' => !($customers->lastPage() == request()->get('page'))];

        return response()->json($result);
    }

    public function getCustomerInformation(Request $request)
    {
        $this->validate($request, [
            'customer_id' => 'required|numeric'
        ]);

        $customer = Customer::findOrFail($request['customer_id']);

        return response()->json($customer->toArray());
    }

    public function updateCustomer(Request $request)
    {
        $this->validate($request, [
            'customer_id'           => 'required|numeric',
            'name'                  => 'required',
            'phone'                 => 'numeric|digits_between:10,14|nullable',
            'address'               => 'string|nullable',
            'focal_point_person'    => 'string|nullable',
            'TIN_number'            => 'numeric|nullable',
            'license_number'        => 'string|nullable',
            'registration_number'   => 'string|nullable',
            'customer_type_id'      => 'required|numeric'
        ]);

        $customer = Customer::findOrFail($request['customer_id']);
        $customer->customer_type_id = $request['customer_type_id'];
        $customer->name = $request['name'];
        $customer->phone = $request['phone'];
        $customer->address = $request['address'];
        $customer->focal_point_person = $request['focal_point_person'];
        $customer->TIN_number = $request['TIN_number'];
        $customer->license_number = $request['license_number'];
        $customer->registration_number = $request['registration_number'];
        $customer->save();

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'Customer\'s information updated successfully.',
                'form_clean' => true,
                'modal' => 'customerEditModal',
                'script' => 'dataTable.ajax.reload();$(".tooltips").tooltip();'
            ]);
        }

        return redirect()->route('customers-view');
    }
}
