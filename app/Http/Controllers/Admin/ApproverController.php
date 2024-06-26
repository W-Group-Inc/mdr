
<?php

namespace App\Http\Controllers\Admin;

use App\Admin\DepartmentApprovers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Validator;

class ApproverController extends Controller
{
    public function index() {

        $approverList = User::select('id', 'name')
            ->where('account_role', 1)
            ->get();
        
        return view('admin.manage-approver',
            array(
                'approverList' => $approverList
            )
        );
    }

    public function updateApprover(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'noApprover' => 'unique:manage_approvers,status_level'
        ]);

        if ($validator->fails()) {

            return back()->with('errors', $validator->errors()->all());
        }
        else {

            $userData = User::findOrFail($id);

            if ($userData) {

                $approverData = DepartmentApprovers::where('approver_id', $userData->id)->first();
                
                if (empty($approverData)) {
                    $approverData = new DepartmentApprovers;
                    $approverData->approver_id = $userData->id;
                    $approverData->no_approver = $request->noApprover;
                    $approverData->save();

                    return back();
                }
                else {
                    $approverData->no_approver = $request->noApprover;
                    $approverData->save();

                    return back();
                }
                
            }
        }
    }
}
