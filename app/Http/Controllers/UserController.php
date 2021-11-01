<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function viewUsers()
    {
        return view('users.index');
    }

    public function viewUsersDataTable()
    {
        $users = User::select([
            'users.id',
            'users.name',
            'users.email',
            'roles.name as role_name',
        ])->join('role_user', 'role_user.user_id', 'users.id')
            ->join('roles', 'roles.id', 'role_user.role_id')
            ->orderBy('users.id');

        return Datatables::of($users)
            ->addColumn('actions', function ($users) {
                $buttons = '';
                if ($users->id != 1)
                    $buttons .= "<button onclick=\"deleteRecord('$users->id')\" class='btn btn-xs btn-danger tooltips' data-original-title='Delete user'><i class='fa fa-trash'></i></button>";
                return $buttons;
            })->rawColumns(['actions'])->make(true);
    }

    public function addNewUserPost(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required|string',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|confirmed|min:8',
            'role_id'   => 'required|numeric'
        ]);

        $role = Role::findOrFail($request['role_id']);

        $user = new User();
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = Hash::make($request['password']);
        $user->save();

        $user->roles()->attach($role);

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'New user registered successfully.',
                'form_clean' => true,
                'modal' => 'userRegisterModal',
                'script' => 'dataTable.ajax.reload();$(".tooltips").tooltip();'
            ]);
        }

        return redirect()->route('users-view');
    }

    public function deleteUserPost(Request $request)
    {
        if ($request['id'] == 1) {
            return \Response::json([
                'type' => 'error',
                'message' => 'You are not allowed to delete the selected user.',
                'modal' => 'userDeleteModal'
            ]);
        }

        $user = User::findOrFail($request['id']);

        if (User::join('audits' ,'audits.user_id', 'users.id')->where('users.id', $user->id)->count()) {
            return \Response::json([
                'type' => 'error',
                'message' => 'The selected user performed many tasks in this database, so it is not deletable.',
                'modal' => 'userDeleteModal'
            ]);
        }
        $user->roles()->detach();
        $user->forceDelete();

        if ($request->ajax()) {
            return \Response::json([
                'type' => 'success',
                'message' => 'Selected user has been deleted successfully.',
                'form_clean' => true,
                'modal' => 'userDeleteModal',
                'script' => 'dataTable.ajax.reload();$(".tooltips").tooltip();'
            ]);
        }

        return redirect()->route('users-view');
    }
}
