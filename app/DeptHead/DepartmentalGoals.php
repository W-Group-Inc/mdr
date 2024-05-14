<?php

namespace App\DeptHead;

use App\Admin\Department;
use App\Admin\DepartmentKPI;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class DepartmentalGoals extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $table = 'departmental_goals';

    protected $fillable = ['department_id', 'department_group_id', 'actual', 'remarks', 'date', 'file_path', 'file_name', 'kpi_name', 'target', 'department_kpi_id', 'grade', 'status_level', 'final_approved'];

    // public function attachments() {
        
    //     return $this->hasMany(Attachments::class);
    // }

    public function departments() {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function departmentKpi() {
        return $this->belongsTo(DepartmentKPI::class, 'department_kpi_id');
    }
}
