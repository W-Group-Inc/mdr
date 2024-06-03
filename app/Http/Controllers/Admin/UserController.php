<?php

namespace App\Http\Controllers\Admin;

use App\Admin\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function index() {

        $userList = User::select('id', 'name', 'email', 'account_role', 'account_status', 'password', 'department_id')->get();

        $departmentList = Department::select('id', 'dept_code', 'dept_name')->get();

        return view('admin.users', 
            array(
                'department' => $departmentList,
                'userList' => $userList
            )
        );
    }

    public function addUserAccounts(Request $request) {
        $validator = Validator::make($request->all(), [
            // 'department' => 'required',
            // 'name' => 'required',
            'email' => 'email|unique:users,email',
            'password' => 'confirmed|min:6',
            'accountRole' => 'required',
        ]);

        if ($validator->fails()) {
            
            return back()->with('errors', $validator->errors()->all());
        }
        else {
            $user = new User;
            $user->department_id = $request->department;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->account_role = $request->accountRole;
            $user->account_status = 1;
            $user->save();

            Alert::success('SUCCESS', 'Successfully Added.');
            return back();
        }
    }

    public function updateUserAccounts(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            // 'department' => 'required',
            'email' => 'email|unique:users,email,' . $id,
            'accountRole' => 'required',
        ]);

        if ($validator->fails()) {

            return back()->with('errors', $validator->errors()->all());
        }
        else {
            $user = User::findOrFail($id);
            if ($user) {
                $user->department_id = $request->department;
                $user->email = $request->email;
                $user->account_role = $request->accountRole;
                $user->save();
            }
            
            Alert::success('SUCCESS', 'Successfully Updated');
            return back();
        }
    }

    public function changeAccountStatus(Request $request) {
        $userData = User::findOrFail($request->id);

        if ($userData->account_status == 1) {
            $userData->account_status = 0;
            $userData->save();

            return array('status' => 0);
        }
        else {
            $userData->account_status = 1;
            $userData->save();

            return array('status' => 1);
        }
    }

    public function changePassword(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'password' => 'min:6|confirmed'
        ]);

        if ($validator->fails()) {

            return back()->with('errors', $validator->errors()->all());
        }
        else {
            $userData = User::findOrFail($id);

            if ($userData) {
                $userData->password = bcrypt($request->password);
                $userData->save();

                Alert::success('SUCCESS', 'Your password has been changed.');
                return back();
            }
        }
        
    }
}
