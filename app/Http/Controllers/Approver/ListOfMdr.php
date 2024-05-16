<?php

namespace App\Http\Controllers\Approver;

use App\Admin\Approve;
use App\Admin\Department;
use App\Admin\DepartmentGroup;
use App\Admin\DepartmentKPI;
use App\DeptHead\DepartmentalGoals;
use App\DeptHead\KpiScore;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class ListOfMdr extends Controller
{
    public function index(Request $request) {
        $departmentList = Department::get();

        $departmentData = Department::with('kpi_scores', 'departmentKpi', 'departmentalGoals', 'process_development', 'innovation', 'user', 'approver')
            ->where('id', $request->department)
            ->first();

        return view('approver.list-of-mdr', 
            array(
                'departmentList' => $departmentList , 
                'department' => $request->department,
                'yearAndMonth' => $request->yearAndMonth,
                // 'departmentKpiGroups' => $departmentKpi,
                'data' => $departmentData
            )
        );
    }

    public function returnMdr(Request $request) {
        $departmentData = Department::with(['departmentalGoals', 'process_development', 'kpi_scores', 'approver'])
            ->where('id', $request->department_id)
            ->first();

        foreach($departmentData->approver as $approver) {

            if (auth()->user()->id == $approver->user_id) {
                $departmentalGoalsList = $departmentData->departmentalGoals()
                    ->where('year', date('Y', strtotime($request->monthOf)))
                    ->where('month', date('m', strtotime($request->monthOf)))
                    ->where('status_level', $approver->status_level)
                    ->get();

                $processDevelopmentList = $departmentData->process_development()
                    ->where('year', date('Y', strtotime($request->monthOf)))
                    ->where('month', date('m', strtotime($request->monthOf)))
                    ->where('status_level', $approver->status_level)
                    ->get();

                $kpiScore = $departmentData->kpi_scores()
                    ->where('year', date('Y', strtotime($request->monthOf)))
                    ->where('month', date('m', strtotime($request->monthOf)))
                    ->where('status_level', $approver->status_level)
                    ->get();

                $innovation = $departmentData->innovation()
                    ->where('year', date('Y', strtotime($request->monthOf)))
                    ->where('month', date('m', strtotime($request->monthOf)))
                    ->where('status_level', $approver->status_level)
                    ->get();


                if ($departmentalGoalsList->isNotEmpty() && $processDevelopmentList->isNotEmpty() && $kpiScore->isNotEmpty() && $innovation->isNotEmpty()) {
                    $departmentalGoals = $departmentalGoalsList->when(true, function($q) {
                        return $q->where('final_approved', 1)->isNotEmpty();
                    });
    
                    $kpiScores = $kpiScore->when(true, function($q) {
                        return $q->where('final_approved', 1)->isNotEmpty();
                    });
    
                    $processDevelopment = $processDevelopmentList->when(true, function($q) {
                        return $q->where('final_approved', 1)->isNotEmpty();
                    });
    
                    $innovations = $innovation->when(true, function($q) {
                        return $q->where('final_approved', 1)->isNotEmpty();
                    });
                    
                    if ($departmentalGoals && $kpiScores && $processDevelopment && $innovations) {
                        Alert::error('ERROR', 'Cannot return the MDR because its already been approved.');
                        
                        return back();
                    }
                    
                    $departmentalGoalsList->each(function($item, $key)use($approver) {
                        $item->update([
                            'status_level' => 0
                        ]);
                    });

                    $processDevelopmentList->each(function($item, $key)use($approver) {
                        $item->update([
                            'status_level' => 0
                        ]);
                    });

                    $kpiScore->each(function($item, $key)use($approver) {
                        $item->update([
                            'status_level' => 0
                        ]);
                    });

                    $innovation->each(function($item, $key)use($approver) {
                        $item->update([
                            'status_level' => 0
                        ]);
                    });

                    Alert::success('SUCCESS', 'Successfully Returned.');
                    return back();
                }
                else {
                    
                    return back();
                }
            }
        }        
    }

    public function addRemarks(Request $request) {
        $departmentalGoalsList =  DepartmentalGoals::where('department_id', $request->department_id)
            ->where('year', $request->year)
            ->where('month', $request->month)
            ->get();

        if ($departmentalGoalsList->isNotEmpty()) {
            $departmentalGoalsList->each(function($item, $key)use($request) {
                $item->update([
                    'remarks' => $request->remarks[$key]
                ]);
            });

            Alert::success('SUCCESS', 'Successfully Added.');
            return back();
        }
        else {
            return back()->with('errors', ["Can not add remarks"]);
        }
    }

    public function approveMdr(Request $request) {

        $departmentData = Department::with(['departmentalGoals', 'process_development', 'kpi_scores', 'approver'])
            ->where('id', $request->department_id)
            ->first();

        foreach($departmentData->approver as $approver) {
            
            if (auth()->user()->id == $approver->user_id) {

                $departmentalGoalsList = $departmentData->departmentalGoals()
                        ->where('year', date('Y', strtotime($request->monthOf)))
                        ->where('month', date('m', strtotime($request->monthOf)))
                        ->where('status_level', $approver->status_level)
                        ->get();

                $processDevelopmentList = $departmentData->process_development()
                    ->where('year', date('Y', strtotime($request->monthOf)))
                    ->where('month', date('m', strtotime($request->monthOf)))
                    ->where('status_level', $approver->status_level)
                    ->get();

                $kpiScore = $departmentData->kpi_scores()
                    ->where('year', date('Y', strtotime($request->monthOf)))
                    ->where('month', date('m', strtotime($request->monthOf)))
                    ->where('status_level', $approver->status_level)
                    ->get();

                $innovation = $departmentData->innovation()
                    ->where('year', date('Y', strtotime($request->monthOf)))
                    ->where('month', date('m', strtotime($request->monthOf)))
                    ->where('status_level', $approver->status_level)
                    ->get();

                if ($departmentalGoalsList->isNotEmpty() && $processDevelopmentList->isNotEmpty() && $kpiScore->isNotEmpty() && $innovation->isNotEmpty()) {
                    
                    if($departmentData->approver->last() == $approver) {
                        $departmentalGoalsList->each(function($item, $key)use($approver) {
                            $item->update([
                                'final_approved' => 1
                            ]);
                        });

                        $processDevelopmentList->each(function($item, $key)use($approver) {
                            $item->update([
                                'final_approved' => 1
                            ]);
                        });

                        $kpiScore->each(function($item, $key) use($approver) {
                            $item->update([
                                'final_approved' => 1
                            ]);
                        });

                        $innovation->each(function($item, $key) use($approver) {
                            $item->update([
                                'final_approved' => 1
                            ]);
                        });
                    }
                    else {
                        $departmentalGoalsList->each(function($item, $key)use($approver) {
                            $item->update([
                                'status_level' => $approver->status_level+1
                            ]);
                        });

                        $processDevelopmentList->each(function($item, $key)use($approver) {
                            $item->update([
                                'status_level' => $approver->status_level+1
                            ]);
                        });

                        $kpiScore->each(function($item, $key) use($approver) {
                            $item->update([
                                'status_level' => $approver->status_level+1
                            ]);
                        });

                        $innovation->each(function($item, $key) use($approver) {
                            $item->update([
                                'status_level' => $approver->status_level+1
                            ]);
                        });
                    }

                    Alert::success('SUCCESS', 'Successfully Approved.');
                    return back();
                }
                else {
                    return back();
                }
            }
        }        
    }

    public function submitScores(Request $request) {
        $kpiScoreData = KpiScore::findOrFail($request->id);

        if ($kpiScoreData) {
            $kpiScoreData->score = $request->kpiScores;
            $kpiScoreData->pd_scores = $request->pdScores;
            $kpiScoreData->innovation_scores = $request->innovationScores;
            $kpiScoreData->save();

            Alert::success('SUCCESS', 'Successfully Updated.');
            return back();
        }
    }
}
