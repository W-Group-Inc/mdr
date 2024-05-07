<?php

namespace App\Http\Controllers\DeptHead;

use App\DeptHead\ProcessDevelopment;
use App\DeptHead\ProcessDevelopmentAttachments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProcessDevelopmentController extends Controller
{
    public function add(Request $request) {
        $validator = Validator::make($request->all(), [
            'description' => 'required',
            'accomplishedDate' => 'required',
            'file' => 'required|max:2048'
        ]);

        if($validator->fails()) {

            return back()->with('pdError', $validator->errors()->all());
        }
        else {
            if($request->hasFile('file')) {

                $processDevelopment = new ProcessDevelopment;
                $processDevelopment->department_id = auth()->user()->department_id;
                $processDevelopment->department_group_id = $request->pd_id;
                $processDevelopment->description = $request->description;
                $processDevelopment->accomplished_date = date("Y-m-d", strtotime($request->accomplishedDate));
                $processDevelopment->save();

                $file = $request->file('file');
                $fileName = time() . '-' . $file->getClientOriginalName();
                $file->move(public_path('file'),  $fileName);

                $attachment = new ProcessDevelopmentAttachments;
                $attachment->pd_id = $processDevelopment->id;
                $attachment->filepath = public_path('file') . '/' . $fileName;
                $attachment->filename = $fileName;
                $attachment->save();

                return back();
            }
            else {
                
                return back()->with('pdError', 'You are not selecting a file.');
            }
        }   
    }
}